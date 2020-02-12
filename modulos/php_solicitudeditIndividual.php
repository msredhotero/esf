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
	header("Location: ../login.php");
	exit();
}

?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];

// Initialize common variables
$x_solicitud_id = Null;
$ox_solicitud_id = Null;
$x_credito_tipo_id = Null;
$ox_credito_tipo_id = Null;
$x_solicitud_status_id = Null;
$ox_solicitud_status_id = Null;
$x_folio = Null;
$ox_folio = Null;
$x_fecha_registro = Null;
$ox_fecha_registro = Null;
$x_promotor_id = Null;
$ox_promotor_id = Null;
$x_importe_solicitado = Null;
$ox_importe_solicitado = Null;
$x_plazo = Null;
$ox_plazo = Null;
$x_contrato = Null;
$ox_contrato = Null;
$x_pagare = Null;
$ox_pagare = Null;

/* nuevos cambios marcos 10/02/2020 */
$x_readonly2 = '';
$x_readonly = '';
$x_telefono_casa_1 = '';
$x_telefono_casa_2 = '';
$x_telefono_casa_3 = '';
$x_telefono_casa_4 = '';
$x_telefono_casa_5 = '';
$x_telefono_casa_6 = '';
$x_telefono_casa_7 = '';
$x_telefono_casa_8 = '';
$x_telefono_casa_9 = '';
$x_telefono_casa_10 = '';

/* fin cambios */

/* nuevo marcos */
$x_minimo = 0;
$x_maximo = 0;
$x_proveedor_nombrecompleto = '';
$x_beneficiario_nombrecompleto = '';
/* fin nuevo */

?>

<?php include("../db.php") ?>
<?php include("../phpmkrfn.php") ?>

<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Load key from QueryString
$x_solicitud_id = @$_GET["solicitud_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
}
$sSqlWrk = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_cliente_id = $datawrk["cliente_id"];
@phpmkr_free_result($rswrk);

$x_win = @$_GET["win"];
if(empty($x_win)){
	$x_win = @$_POST["x_win"];

}




//if (!empty($x_solicitud_id )) $x_solicitud_id  = (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id ) : $x_solicitud_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	foreach($_POST as $campo => $valor){
		$$campo = $valor;
		}


	if(!empty($_POST["x_propietario_renta"])){
		$x_propietario = $_POST["x_propietario_renta"];
	}
	if(!empty($_POST["x_propietario_familiar"])){
		$x_propietario = $_POST["x_propietario_familiar"];
	}
	if(!empty($_POST["x_propietario_ch"])){
		$x_propietario = $_POST["x_propietario_ch"];
	}

	if(!empty($_POST["x_propietario_renta2"])){
		$x_propietario2 = $_POST["x_propietario_renta2"];
	}
	if(!empty($_POST["x_propietario_familiar2"])){
		$x_propietario2 = $_POST["x_propietario_familiar2"];
	}
	if(!empty($_POST["x_propietario_ch2"])){
		$x_propietario2 = $_POST["x_propietario_ch2"];
	}


}

// Check if valid key
if (($x_solicitud_id == "") || (is_null($x_solicitud_id))) {
	ob_end_clean();
	header("Location: php_solicitudlist.php");
	exit();
}

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");

		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$x_mensaje = "<br><br><br><p align='center'><a href='javascript: window.close();' >Los datos han sido actualizados de clic aqui para cerrar esta ventana</a></p>";

			//phpmkr_db_close($conn);
			ob_end_clean();



		}
		break;
	case "D": // DIRECCIONES
		if($_POST["x_delegacion_id_temp"] != ""){
			$x_delegacion_id2 = $_POST["x_delegacion_id_temp"];
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!--googlemaps-->
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>



<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
<!-- <link rel="stylesheet" href="../../../crm.css" type="text/css" /> -->

<link href="../php_project_esf.css" rel="stylesheet" type="text/css" />

<!--googlemaps-->
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="../scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.themeswitcher.js"></script>


<script language="javascript" src="tipoCuenta/formatos/js/carga_telefonos.js"></script>
<script language="javascript">
$(document).ready(function() {
	status_sol = $('#x_solicitud_status_id').val();

	//alert(status_sol);
	if(status_sol == 11){
		 $("#x_div_fecha_supervision_tex").css("display", "block");
		 $("#x_div_fecha_supervision_in").css("display", "block");
		}

	$('#x_realizo_supervision').click(function (evento){
		if ($("#x_realizo_supervision").attr("checked")){
         $('#x_realizo_supervision').val("1");
      }else{
         $('#x_realizo_supervision').val("0");
      }
   });
   $('#x_calculo_capacidad_pago').click( function (evento){
	   if ($("#x_calculo_capacidad_pago").attr("checked")){
		   $('#x_calculo_capacidad_pago').val("1");//attr('value',1);
		   }else{
			   $('#x_calculo_capacidad_pago'). val("0");//attr('value',1);

			   }


	   });

	$('#x_calcula_curp').click(function (evento){
		var nombre = $('#x_nombre').val();
		var paterno = $('#x_apellido_parterno').val();
		var materno = $('#x_apellido_materno').val();
		var fecha = $('#x_fecha_nacimiento').val();
		var sexo = $('#x_sexo').val();
		var nacimiento = $('#x_entidad_nacimiento').val();
		var ff = fecha.split("/");
		var dia = ff[0];
		var mes = ff[1];
		var anio = ff[2];
		alert("CALCULA CURP");
		//alert(ff)
		$("#txtHintcurp").load("generaCurp.php?q1="+nombre+"&q2="+paterno+"&q3="+materno+"&q4="+dia+"&q5="+mes+"&q6="+anio+"&q7="+nacimiento+"&q8="+sexo+"");
		});

	$('#x_calcula_rfc').click(function (evento){
		var nombre = $('#x_nombre').val();
		var paterno = $('#x_apellido_parterno').val();
		var materno = $('#x_apellido_materno').val();
		var fecha = $('#x_fecha_nacimiento').val();
		var sexo = $('#x_sexo').val();
		var nacimiento = $('#x_entidad_nacimiento').val();
		var ff = fecha.split("/");
		var dia = ff[0];
		var mes = ff[1];
		var anio = ff[2];
		alert("CALCULA RFC");
		$("#txtHintrfc").load("generaRfc.php?q1="+nombre+"&q2="+paterno+"&q3="+materno+"&q4="+dia+"&q5="+mes+"&q6="+anio+"&q7="+nacimiento+"&q8="+sexo+"");
		});

});
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
   </script>
</head>

<body onLoad="cargaEventos();">

<script language="javascript"  src="../ew.js"></script>
<script language="javascript"  src="guarda_direccion_antigua.js"></script>

<script src="paisedohint.js"></script>
<script src="lochint.js"></script>
<script src="generaCurpRfc.js"></script>
<script src="mapsNegocio.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator



function muestraMontoSolicitado(){

	val = document.getElementById("temp_x_monto_solicitado").value;
	val =  parseInt(val);
	document.getElementById("x_monto_solicitado").value = val;
	document.getElementById("x_importe_solicitado").value = val;

	}

//-->
</script>
<script type="text/javascript">
<!--

//window.onload = function(){

	function cargaEventos(){
	//al cargar toda la pagina
	//EVE2NTOS PARA ACTUALIZAR FORMATO PYME
	//document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;

//googlemaps
	initialize();

	if(document.getElementById("x_hidden_mapa_negocio").value == 1){
			initialize2();

			//si los mapas son diferentes cargamos el mapa dos.
		}else{
			// quito el ma´pa que esta pintado
			quitaMapaNegocio();
			}

		EW_this2 = document.solicitudeditPYME;

	if(EW_this2.x_inv_neg_var_valor_1){
	document.getElementById("x_inv_neg_var_valor_1").value
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

	}

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









function act(){}

function buscadelegacion(){
EW_this = document.solicitudedit;
	EW_this.a_edit.value = "D";
	EW_this.submit();
}

function buscacliente(){
EW_this = document.solicitudedit;
	EW_this.a_edit.value = "X";
	EW_this.submit();

}
function validaimporte(){

EW_this = document.solicitudedit;

if(EW_this.x_importe_solicitado.value < 1000){
	alert("El importe es incorrecto valor minimo 1000");
	EW_this.x_importe_solicitado.focus();
}


}
}//WINDOW ON LOAD


function EW_onError(form_object, input_object, object_type, error_message) {
	alert(error_message);
	if (object_type == "RADIO" || object_type == "CHECKBOX") {
		if (input_object[0])
			input_object[0].focus();
		else
			input_object.focus();
	}	else if (!EW_isHTMLArea(input_object, object_type)) {
		input_object.focus();
	}
	if (object_type == "TEXT" || object_type == "PASSWORD" || object_type == "TEXTAREA" || object_type == "FILE") {
		if (!EW_isHTMLArea(input_object, object_type))
			input_object.select();
	}
	return false;
}

function EW_hasValue(obj, obj_type) {
	if (obj_type == "TEXT" || obj_type == "PASSWORD" || obj_type == "TEXTAREA" || obj_type == "FILE")	{
		if (obj.value.length == 0)
			return false;
		else
			return true;
	}	else if (obj_type == "SELECT") {
		if (obj.type != "select-multiple" && obj.selectedIndex == 0)
			return false;
		else if (obj.type == "select-multiple" && obj.selectedIndex == -1)
			return false;
		else
			return true;
	}	else if (obj_type == "RADIO" || obj_type == "CHECKBOX")	{
		if (obj[0]) {
			for (i=0; i < obj.length; i++) {
				if (obj[i].checked)
					return true;
			}
		} else {
			return (obj.checked);
		}
		return false;
	}
}





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


function EW_checkMyForm() {
EW_this = document.solicitudeditPYME;
validada = true;

	telefono_casa = 0;
	telefono_cel = 0;

	// validamos los montos de Total

	if(validada == true && EW_this.x_solicitud_status_id.value == 3 ){
		// la solicitud se va a aprobar, entar a revisar el importe contar los gastos




		if(EW_this.x_inv_neg_activos_totales){
		var importe = EW_this.x_importe_solicitado.value;
		var activos_totales = EW_this.x_inv_neg_activos_totales.value;

		if(importe > activos_totales){

			var confirmacion = confirm("EL IMPORTE SOLICITADO ES MAYOR A LOS ACTIVOS TOTALES REGISTRADOS..¿ ESTA SEGURO QUE DESEA CONTINUAR ?");
			if (confirmacion){
				validada = true;
				}else{
					validada = false;
					alert("USTED DEBERA CAMBIAR LOS MONTOS DE ACTIVOS TOTALES PARA PODER CONTINUAR");
					}

			}

			}

		}

if(validada == true && (EW_this.x_solicitud_status_id.value == 3 || EW_this.x_solicitud_status_id.value == 4 || EW_this.x_solicitud_status_id.value == 5 || EW_this.x_solicitud_status_id.value == 12 ) ){
	var tipo_cliente = EW_this.x_cliente_tipo_id.value;
		if(tipo_cliente < 1 ){
			validada = false;
					alert("DEBE INDICAR EL TIPO DE CLIENTE");
			}



	}

if(validada == true && EW_this.x_solicitud_status_id.value == 10 ){
	if (validada == true && EW_this.x_dia_otorga_credito && !EW_hasValue(EW_this.x_dia_otorga_credito, "TEXT")) {
		if (!EW_onError(EW_this, EW_this.x_dia_otorga_credito, "TEXT", "Indique el dia enque se debe otorgar el credito."))
		validada = false;
}

if (validada == true && EW_this.x_numero_cheque && !EW_hasValue(EW_this.x_numero_cheque, "TEXT")) {
		if (!EW_onError(EW_this, EW_this.x_numero_cheque, "TEXT", "Indique el numero de cheque con el que se otorgara el credito."))
		validada = false;
}

if (validada == true && EW_this.x_tarjeta_referenciada && !EW_hasValue(EW_this.x_tarjeta_referenciada, "TEXT")) {
		if (!EW_onError(EW_this, EW_this.x_tarjeta_referenciada, "TEXT", "Indique  la tarjeta que se le asignara al cliente."))
		validada = false;
}




}



if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT")) {
		if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el credito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del credito a solicitar."))
		validada = false;
}

if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el numero de pagos."))
		validada = false;
}

if (validada == true && EW_this.x_plazo_id && EW_hasValue(EW_this.x_plazo_id, "TEXT" )) {
//verifcar si el numero de pagos es correcto
numer_pag = EW_this.x_plazo_id.value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor, MIN 2, MAX 104");
					 validada = false;
					 }
 }
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}

if (validada == true && EW_this.x_zona_id && !EW_hasValue(EW_this.x_zona_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_zona_id, "TEXT", "Indique la ZONA a la que pertenece el cliente."))
		validada = false;
}

//las validacion del formato individual

if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}

/*
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Por favor introduzca el campo requerido - RFC"))
		validada = false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}*/
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}
/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false;
}*/
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Por favor introduzca el campo requerido - genero"))
		validada = false;
}

if (EW_this.x_entidad_nacimiento && !EW_hasValue(EW_this.x_entidad_nacimiento, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_nacimiento, "SELECT", "Seleccione el país de nacimiento, este dato se necesita para calcular el CURP"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Por favor introduzca el campo requerido - integrantes familia"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Valor incorrecto, se espera un entero. - integrantes familia"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Por favor introduzca el campo requerido - dependientes"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Valor incorrecto, se espera un entero. - dependientes"))
		validada = false;
}

if (EW_this.x_estudio_id && !EW_hasValue(EW_this.x_estudio_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estudio_id, "SELECT", "Por seleccione un valor para el campo estudios"))
		validada = false;
}

if (EW_this.x_rol_hogar_id && !EW_hasValue(EW_this.x_rol_hogar_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_hogar_id, "SELECT", "Por el rol del titular en el hogar"))
		validada = false;
}

if (EW_this.x_fecha_ini_act_prod && !EW_hasValue(EW_this.x_fecha_ini_act_prod, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_ini_act_prod, "TEXT", "Por favor introduzca la fecha de inico de actividad productiva del titular"))
		validada = false;
}


if (EW_this.x_destino_credito_id && !EW_hasValue(EW_this.x_destino_credito_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_destino_credito_id, "SELECT", "Por favor indique el destino del credito"))
		validada = false;
}

if (EW_this.x_ppe && !EW_hasValue(EW_this.x_ppe, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_ppe, "SELECT", "Por favor indique si es una PPE "))
		validada = false;
}

if (EW_this.x_ppe && EW_this.x_ppe.value == 1) {
	//debe indicar la relacion y el nombre

if (EW_this.x_parentesco_ppe && !EW_hasValue(EW_this.x_parentesco_ppe, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ppe, "SELECT", "Por favor indique la relacion en la seccion PPE "))
		validada = false;
}

if (EW_this.x_nombre_ppe && !EW_hasValue(EW_this.x_nombre_ppe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_ppe, "TEXT", "Por indique el nombre de la seccion PPE"))
		validada = false;
}

if (EW_this.x_apellido_paterno_ppe && !EW_hasValue(EW_this.x_apellido_paterno_ppe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno_ppe, "TEXT", "Por indique el apellido paterno de la seccion PPE"))
		validada = false;
}

}

/*
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false;
}*/
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Por favor introduzca el campo requerido - calle domicilio"))
		validada = false;
}

if (EW_this.x_numero_exterior && !EW_hasValue(EW_this.x_numero_exterior, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior, "TEXT", "Por favor introduzca el campo requerido - numero exterior"))
		validada = false;
}
if (EW_this.x_numero_exterior && !EW_checkinteger(EW_this.x_numero_exterior.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior, "TEXT", "Valor incorrecto, se espera un entero. - numero exteriror"))
		validada = false;
}

if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Por favor introduzca el campo requerido - entidad domicilio"))
		validada = false;
}

if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion domicilio"))
		validada = false;
}

if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false;
}
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Por favor introduzca el campo requerido - ubicacion domicilio"))
		validada = false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Por favor introduzca el campo requerido - tipo vivienda"))
		validada = false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensula domicilio"))
		validada = false;
}

// telefonos del domicilio




if ( validada && EW_this.x_telefono_casa_1 && EW_this.x_telefono_casa_1.value.length > 0) {
	if((EW_this.x_telefono_casa_1.value.length != 10)){
		alert ("El numero de telefono 1 debe tener 10 digitos");
		validada = false;
	}
 telefono_casa =  telefono_casa + 1 ;
}

if ( validada && EW_this.x_telefono_casa_2 && EW_this.x_telefono_casa_2.value.length > 0) {
	if((EW_this.x_telefono_casa_2.value.length != 10)){
		alert("El numero de telefono 2 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}


if ( validada && EW_this.x_telefono_casa_3 && EW_this.x_telefono_casa_3.value.length > 0) {
	if((EW_this.x_telefono_casa_3.value.length != 10)){
		alert ("El numero de telefono 3 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}

if ( validada && EW_this.x_telefono_casa_4 && EW_this.x_telefono_casa_4.value.length > 0) {
	if((EW_this.x_telefono_casa_4.value.length != 10)){
		alert ("El numero de telefono 4 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}

if ( validada && EW_this.x_telefono_casa_5 && EW_this.x_telefono_casa_5.value.length > 0) {
	if((EW_this.x_telefono_casa_5.value.length != 10)){
		alert ("El numero de telefono 5 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}


if ( validada  && EW_this.x_telefono_casa_6 && EW_this.x_telefono_casa_6.value.length > 0) {
	if((EW_this.x_telefono_casa_6.value.length != 10)){
		alert ("El numero de telefono 6 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}



if ( validada && EW_this.x_telefono_casa_7 && EW_this.x_telefono_casa_7.value.length > 0) {
	if((EW_this.x_telefono_casa_7.value.length != 10)){
		alert ("El numero de telefono 7 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}

if ( validada && EW_this.x_telefono_casa_8 && EW_this.x_telefono_casa_8.value.length > 0) {
	if((EW_this.x_telefono_casa_8.value.length != 10)){
		alert ("El numero de telefono 8 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}


if ( validada && EW_this.x_telefono_casa_9 && EW_this.x_telefono_casa_9.value.length > 0) {
	if((EW_this.x_telefono_casa_9.value.length != 10)){
		alert ("El numero de telefono 9 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}

if ( validada && EW_this.x_telefono_casa_10 && EW_this.x_telefono_casa_10.value.length > 0) {
	if((EW_this.x_telefono_casa_10.value.length != 10)){
		alert ("El numero de telefono 10 debe tener 10 digitos");
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;
}



if ( validada && EW_this.x_telefono_celular_1  && EW_this.x_telefono_celular_1.value.length > 0) {
	if((EW_this.x_telefono_celular_1.value.length != 10)){
		alert ("El numero de celular 1 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_1 && !EW_hasValue(EW_this.x_compania_celular_1, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_1, "SELECT", "Debe seleccionar una compania de telefonos para el celular 1"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_2 && EW_this.x_telefono_celular_2.value.length > 0) {
	if((EW_this.x_telefono_celular_2.value.length != 10)){
		alert ("El numero de celular 2 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_2 && !EW_hasValue(EW_this.x_compania_celular_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_2, "SELECT", "Debe seleccionar una compania de telefonos para el celular 2"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_4 && EW_this.x_telefono_celular_3.value.length > 0) {
	if((EW_this.x_telefono_celular_3.value.length != 10)){
		alert ("El numero de celular 1 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_3 && !EW_hasValue(EW_this.x_compania_celular_3, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_3, "SELECT", "Debe seleccionar una compania de telefonos para el celular 3"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}

if ( validada  && EW_this.x_telefono_celular_4 && EW_this.x_telefono_celular_4.value.length > 0) {
	if((EW_this.x_telefono_celular_4.value.length != 10)){
		alert ("El numero de celular 4 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_4 && !EW_hasValue(EW_this.x_compania_celular_4, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_4, "SELECT", "Debe seleccionar una compania de telefonos para el celular 4"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_5 && EW_this.x_telefono_celular_5.value.length > 0) {
	if((EW_this.x_telefono_celular_5.value.length != 10)){
		alert ("El numero de celular 5 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_5 && !EW_hasValue(EW_this.x_compania_celular_5, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_5, "SELECT", "Debe seleccionar una compania de telefonos para el celular 5"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_6 && EW_this.x_telefono_celular_6.value.length > 0) {
	if((EW_this.x_telefono_celular_6.value.length != 10)){
		alert ("El numero de celular 6 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_6 && !EW_hasValue(EW_this.x_compania_celular_6, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_6, "SELECT", "Debe seleccionar una compania de telefonos para el celular 6"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_7 && EW_this.x_telefono_celular_7.value.length > 0) {
	if((EW_this.x_telefono_celular_7.value.length != 10)){
		alert ("El numero de celular 7 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_7 && !EW_hasValue(EW_this.x_compania_celular_7, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_7, "SELECT", "Debe seleccionar una compania de telefonos para el celular 7"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_8 && EW_this.x_telefono_celular_8.value.length > 0) {
	if((EW_this.x_telefono_celular_8.value.length != 10)){
		alert ("El numero de celular 8 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_8 && !EW_hasValue(EW_this.x_compania_celular_8, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_8, "SELECT", "Debe seleccionar una compania de telefonos para el celular 8"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_9 && EW_this.x_telefono_celular_9.value.length > 0) {
	if((EW_this.x_telefono_celular_9.value.length != 10)){
		alert ("El numero de celular 9 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_9 && !EW_hasValue(EW_this.x_compania_celular_9, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_9, "SELECT", "Debe seleccionar una compania de telefonos para el celular 9"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_10 && EW_this.x_telefono_celular_10.value.length > 0) {
	if((EW_this.x_telefono_celular_10.value.length != 10)){
		alert ("El numero de celular 10 debe tener 10 digitos");
		validada = false;
	}else{
		if (EW_this.x_compania_celular_10 && !EW_hasValue(EW_this.x_compania_celular_10, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_10, "SELECT", "Debe seleccionar una compania de telefonos para el celular 10"))
				validada = false;
		}

		}
		telefono_cel = telefono_cel + 1;

}


if( (telefono_casa == 0)  && (telefono_cel == 0)){
	validada = false;
	alert("Debe introducir por lo menos un telefono en la nueva seccion de telefonos, puede ser fijo o  celular")

	}




if((EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_2.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_3.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0 )){

	if (EW_this.x_referencia_com_1 && EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
		if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 1"))
		validada = false;
		}
			if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 1"))
		validada = false;
		}

	}//valida referncia uno



	if (EW_this.x_referencia_com_2 && EW_hasValue(EW_this.x_referencia_com_2, "TEXT" )) {
		if (EW_this.x_parentesco_ref_2 && !EW_hasValue(EW_this.x_parentesco_ref_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_2, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 2"))
		validada = false;
		}
			if (EW_this.x_tel_referencia_2 && !EW_hasValue(EW_this.x_tel_referencia_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_2, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 2"))
		validada = false;
		}

	}//valida referncia 2



	if (EW_this.x_referencia_com_3 && EW_hasValue(EW_this.x_referencia_com_3, "TEXT" )) {
		if (EW_this.x_parentesco_ref_3 && !EW_hasValue(EW_this.x_parentesco_ref_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_3, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 3"))
		validada = false;
		}
			if (EW_this.x_tel_referencia_3 && !EW_hasValue(EW_this.x_tel_referencia_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_3, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 3"))
		validada = false;
		}

	}//valida referncia 3


	if (EW_this.x_referencia_com_4 && EW_hasValue(EW_this.x_referencia_com_4, "TEXT" )) {
		if (EW_this.x_parentesco_ref_4 && !EW_hasValue(EW_this.x_parentesco_ref_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_4, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 4"))
		validada = false;
		}
			if (EW_this.x_tel_referencia_4 && !EW_hasValue(EW_this.x_tel_referencia_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_4, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 4"))
		validada = false;
		}

	}//valida referncia 4



	//se llenaron almenos dos campos

	}else{
		alert("Debe introducir al menos dos referencias");
		validada = false;

	}






/*
if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Por favor introduzca el campo requerido - giro negocio"))
		validada = false;
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Por favor introduzca el campo requerido - calle negocio"))
		validada = false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia negocio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false;
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensual"))
		validada = false;
}*/
/*
if (EW_this.x_garantias && !EW_hasValue(EW_this.x_garantias, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_garantias, "TEXTAREA", "Por favor introduzca el campo requerido - solicitud compra"))
		validada = false;
}*/

/*
if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Por favor introduzca el campo requerido - referencia com 1"))
		validada = false;
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - tel referencia 1"))
		validada = false;
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco ref 1"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Por favor introduzca el campo requerido - ing fam negocio"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam negocio"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Por favor introduzca el campo requerido - ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Por favor introduzca el campo requerido - ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Por favor introduzca el campo requerido - ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Por favor introduzca el campo requerido - ing fam total"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam total"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Por favor introduzca el campo requerido - flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ingreso negocio"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Por favor introduzca el campo requerido - inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Por favor introduzca el campo requerido - inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Por favor introduzca el campo requerido - inv neg activos totales"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg activos totales"))
		validada = false;
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Por favor introduzca el campo requerido - fecha"))
		validada = false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	//if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha"))
		validada = false;
}*/

/*if(EW_this.x_celular.value.length > 0){
	 	 if((EW_this.x_celular.value.length == 10)){
			 if (EW_this.x_compania_celular_id && !EW_hasValue(EW_this.x_compania_celular_id, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_id, "SELECT", "Debe seleccionar una compañia de telefonos para el campo - celular"))
				validada = false;
				}

			 }else{
				 alert("Debe introducir el numero de celular a 10 digitos");
			 validada = false;

				 }


	 }*/









if (EW_this.x_giro_negocio_id && !EW_hasValue(EW_this.x_giro_negocio_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio_id, "SELECT", "Por debe seleccionar un valor para el campo giro negocio"))
		validada = false;
}

if (EW_this.x_tipo_inmueble_id && !EW_hasValue(EW_this.x_tipo_inmueble_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_inmueble_id, "SELECT", "Por debe seleccionar un valor para el campo tipo negocio"))
		validada = false;
}
if (EW_this.x_atiende_titular && !EW_hasValue(EW_this.x_atiende_titular, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_atiende_titular, "SELECT", "Por debe seleccionar un valor para el campo atiende titular"))
		validada = false;
}
if (EW_this.x_personas_trabajando && !EW_hasValue(EW_this.x_personas_trabajando, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_personas_trabajando, "TEXT", "Por favor indique el numero de personas que trabajan en el negocio"))
		validada = false;
}

if (EW_this.x_personas_trabajando && !EW_checkinteger(EW_this.x_personas_trabajando.value)) {
	if (!EW_onError(EW_this, EW_this.x_personas_trabajando, "TEXT", "Valor incorrecto, se espera un entero. - personas trabajando"))
		validada = false;
}


if (EW_this.x_ingreso_semanal && !EW_hasValue(EW_this.x_ingreso_semanal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_semanal, "TEXT", "Por favor indique el ingreso semanal"))
		validada = false;
}


if (EW_this.x_calle_negocio.length > 0) {
	//si se lleno la calle se valida que llene los demas campos tambien
if (EW_this.x_numero_exterior2 && !EW_hasValue(EW_this.x_numero_exterior2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior2, "TEXT", "Por favor introduzca el campo requerido - numero exterior del negocio"))
		validada = false;
}
if (EW_this.x_numero_exterior2 && !EW_checkinteger(EW_this.x_numero_exterior2.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior2, "TEXT", "Valor incorrecto, se espera un entero. - numero exteriror del negocio"))
		validada = false;
}

if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}

if (EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "TEXT", "Por favor introduzca el campo requerido - delegacion, de la seccion negocio"))
		validada = false;
}

if (EW_this.x_codigo_postal_negocio && !EW_hasValue(EW_this.x_codigo_postal_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Por favor introduzca el campo requerido - codigo postal negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}

}

/*if ( validada == true && EW_this.x_latlong.value.length == 0){
	alert("Debe introducir una direccion en el MAPA y buscarla")
	validada = false;
	}*/


	if ( validada == true && EW_this.x_latlong.value.length == 0){
	alert("Debe introducir una direccion en el MAPA de domicilio y buscarla")
	validada = false;
	}
// si esta el mapa de negocios entonces debe validar que ete lleno.
 if ( validada == true && EW_this.x_hidden_mapa_negocio.value == 1){
	if ( validada == true && EW_this.x_latlong2.value.length == 0){
	alert("Debe introducir una direccion en el MAPA de negocio y buscarla")
	validada = false;
	}
	}


//termina validaciones del formato adquision de maquinaria
if(validada == true){
	EW_this.a_edit.value = "U";
	EW_this.submit();
}
}
//window.onload

function valorMax(elemento){
				//verifaca si le valor de campo para numero de pagos es correcto de ser un valor entre 2 hasta 88

				numer_pag = document.getElementById("x_plazo_id").value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor");

					 }



				}

function 	llenaDatosNegocio(){

	EW_this = document.solicitudeditPYME;
		validada = true;
			var val_actual = document.getElementById("x_hidden_mapa_negocio").value;
			if(EW_this.x_mismos_datos_domiclio.checked == true){
				EW_this.x_hidden_mapa_negocio.value = 1;
					// jalamos los datos del domicilio particular

			if (validada == true && EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Indique la calle del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Indique la colonia del domicilio particular del titular."))
				validada = false;
		}

		if (validada == true && EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Indique C.P. del domicilio particular del titular."))
				validada = false;
		}

		if (validada == true && EW_this.x_numero_exterior && !EW_hasValue(EW_this.x_numero_exterior, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_numero_exterior, "TEXT", "Indique el numero del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "SELECT", "Indique la entidad del domicilio particular del titular."))
				validada = false;
		}

		if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegacion del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_localidad_id && !EW_hasValue(EW_this.x_localidad_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_localidad_id, "SELECT", "Indique la localidad del domicilio particular del titular."))
				validada = false;
		}

		if(validada == false){
			EW_this.x_mismos_datos_domiclio.checked = false;
				EW_this.x_hidden_mapa_negocio.value = 0;
		}else{



			x_calle = EW_this.x_calle_domicilio.value;
			x_numero = EW_this.x_numero_exterior.value;
			x_col = EW_this.x_colonia_domicilio.value;
			x_cp = 	EW_this.x_codigo_postal_domicilio.value;
			//x_ent =	EW_this..value;
			x_del = EW_this.x_delegacion_id.value;
			x_loc = EW_this.x_localidad_id.value;
			x_ubi = EW_this.x_ubicacion_domicilio.value;


			// y los escribimos en la seccion de negocio

			EW_this.x_calle_negocio.value = x_calle ;
			EW_this.x_numero_exterior2.value = x_numero ;
			EW_this.x_colonia_negocio.value = x_col ;
			EW_this.x_codigo_postal_negocio.value = x_cp ;
			//EW_this.x_entidad_negocio.value = x_ent ;

			var indice = EW_this.x_entidad_domicilio.selectedIndex;
			EW_this.x_entidad_negocio.options[indice].selected = true;
			EW_this.x_ubicacion_negocio	.value = x_ubi ;
			showHint(EW_this.x_entidad_negocio,'txtHint2', 'x_delegacion_id2');

			var sig = 0;
			/*while(!EW_this.x_delegacion_id2){
				setTimeout('cargaDelegacion()', 5000);
				sig = 1;
			}*/
			var indice2 = EW_this.x_delegacion_id.selectedIndex;
			//EW_this.x_delegacion_id2.options[indice2].selected = true;
			quitaMapaNegocio();// borra el mapa de la solcitud	, lo dejamos aqui para dar tiempo a que se cargue ele lemento anterior
			//showLoc(EW_this.x_delegacion_id2,'txtHint4', 'x_localidad_id2');

			if(EW_this.x_localidad_id2){
			var indice3 = EW_this.x_localidad_id.selectedIndex;
			EW_this.x_localidad_id2.options[indice3].selected = true;
			}
			/*while(!EW_this.x_localidad_id2){
				setTimeout('cargaDelegacion()', 2000);

			}*/
			var indice3 = EW_this.x_localidad_id.selectedIndex;
			//EW_this.x_localidad_id2.options[indice3].selected = true;
		}
				}else{
					EW_this.x_hidden_mapa_negocio.value = 0;

					EW_this.x_calle_negocio.value = "" ;
					EW_this.x_numero_exterior2.value = "" ;
					EW_this.x_colonia_negocio.value = "" ;
					EW_this.x_codigo_postal_negocio.value = "" ;
					if(EW_this.x_localidad_id2){
						EW_this.x_localidad_id2.value = 0;
						}
					if(EW_this.x_delegacion_id2){
						EW_this.x_delegacion_id2.value = 0;
						}
					if(EW_this.x_localidad_id2){
						EW_this.x_localidad_id2.value = 0;
						}
					cargaMapaNegocio();

					}

}
function cargaDelegacion(){

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
//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>


<form name="solicitudeditPYME" action="php_solicitudeditIndividual.php" method="post" >
<input type="hidden" name="x_win" value="<?php echo $x_win ;?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<input type="hidden" id="x_fecha_investigacion" name="x_fecha_investigacion" value="<?php echo htmlspecialchars(@$currdate); ?>">
<input type="hidden" name="x_actualiza_fecha_investigacion" value="<?php  echo($x_actualiza_fecha_investigacion);?>" />
<input type="hidden" name="x_actualiza_fecha_supervision" value="<?php  echo($x_actualiza_fecha_supervision);?>" />

<input type="hidden" name="x_visita_id_1" value="<?php echo $x_visita_id_1?>" />


<input type="hidden" name="x_visita_id_2" value="<?php echo $x_visita_id_2?>" />
<input type="hidden" name="x_visita_id_3" value="<?php echo $x_visita_id_3?>" />
<input type="hidden" name="x_visita_id_4" value="<?php echo $x_visita_id_4?>" />
<input type="hidden" name="x_ppe_id" value="<?php echo $x_ppe_id ?>" />

<span class="texto_normal">
<?php //echo $x_mensaje;
if(!empty($x_mensaje)){exit();
}
?>

<?php if($x_win == 1){ ?>
<a href="cuentas_view.php?key=<?php echo $x_cliente_id; ?>">Regresar a los datos del Cliente</a>
<?php
}
if($x_win == 2){
	$sSqlWrk = "
	SELECT
		crm_caso_id
	FROM
		crm_caso
	WHERE
		solicitud_id = ".$x_solicitud_id."
		and crm_caso_tipo_id = 1
	";

	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_caso_id = $datawrk["crm_caso_id"];
	@phpmkr_free_result($rswrk);
?>

<a href="casos_view.php?key=<?php echo $x_crm_caso_id; ?>">Regresar a los datos del Caso</a>
<?php
}
if($x_win == 3){
?>
<a href='javascript: window.close();'>De clic aqui para cerrar esta Ventana</a>
<?php } ?>
<?php if($x_win == 4){ ?>
<a href="avales_view.php?key=<?php echo $x_aval_id; ?>">Regresar a los datos del Aval</a>
<?php } ?>
</span>
<input type="hidden" name="x_direccion_id2" value="<?php echo $x_direccion_id2; ?>" />
<input type="hidden" name="x_direccion_id3" value="<?php echo $x_direccion_id3; ?>" />
<input type="hidden" name="x_direccion_id4" value="<?php echo $x_direccion_id4; ?>" />

<input type="hidden" name="x_aval_id" value="<?php echo $x_aval_id; ?>" />
<input type="hidden" name="x_garantia_id" value="<?php echo $x_garantia_id; ?>" />
<input type="hidden" name="x_ingreso_id" value="<?php echo $x_ingreso_id; ?>" />
<input type="hidden" name="x_gasto_id" value="<?php echo $x_gasto_id; ?>" />
<input type="hidden" name="x_ingreso_aval_id" value="<?php echo $x_ingreso_aval_id; ?>" />
<input type="hidden" name="x_gasto_aval_id" value="<?php echo $x_gasto_aval_id; ?>" />
<input type="hidden" name="x_negocio_id" value="<?php echo $x_negocio_id;?>" />
<input type="hidden" name="x_google_maps_id" id="x_google_maps_id" value="<?php  echo $x_google_maps_id; ?>" />
<input type="hidden" name="x_readonly" id="x_readonly" value="<?php echo $x_readonly;?>" />
<input type="hidden" name="temp_x_monto_solicitado" id="temp_x_monto_solicitado" value="<?php echo $x_importe_solicitado?>" />


<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"></td>
    </tr>

  <tr>
    <td colspan="3" align="left" valign="top"><table width="674" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="6" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Solicitud
          </td>
      </tr>
      <tr>
        <td class="texto_normal"></td>
        <td colspan="3">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;

        </td>
      </tr>
      <tr>
        <td class="texto_normal">Folio:</td>
        <td colspan="3"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_folio) ?>
                  <input type="hidden" name="x_folio" value="<?php echo htmlspecialchars(@$x_folio) ?>" />
        </b></div></td>
        <td class="texto_normal"><div align="right">Status:</div></td>
        <td>

            <?php

			if(($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12) and ($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 2 ) ){
				$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\" id=\"x_solicitud_status_id\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
				if($x_solicitud_status_id == 12  ){
				$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (10,12,4) ";
				}else{
					$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (3, 10,12) ";
					}
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;

				}else if($x_solicitud_status_id == 9 and ($_SESSION["php_project_esf_status_UserRolID"] == 12 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 7 || $_SESSION["php_project_esf_status_UserRolID"] == 17)){
					$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
				$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (9, 2) ";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;

					}else{
#echo $x_solicitud_status_id;
		if(($x_solicitud_status_id < 5)||($x_solicitud_status_id == 9) || ($x_solicitud_status_id == 10) || ($x_solicitud_status_id == 11) ){

		$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if(($_SESSION["php_project_esf_status_UserRolID"] == 7)) {
			$sSqlWrk = "SELECT solicitud_status_id, descripcion FROM solicitud_status Where  solicitud_status_id = ".@$x_solicitud_status_id;
		}else if(($_SESSION["php_project_esf_status_UserRolID"] == 12) || ($_SESSION["php_project_esf_status_UserRolID"] == 17)) {

			if ($x_solicitud_status_id  == 1){
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (1, 11) ";
			}else if($x_solicitud_status_id  == 3){
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id in (3, 10) ";
					}else{
						$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id = $x_solicitud_status_id ";
						}

		}else{
			//$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status`";
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id not in (6,7,8) ";
			}
		#echo $sSqlWrk;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		}else{
			$sSqlWrk = "SELECT descripcion FROM solicitud_status Where solicitud_status_id = ".$x_solicitud_status_id;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo $datawrk["descripcion"];
			@phpmkr_free_result($rswrk);

?>
			<input type="hidden" name="x_solicitud_status_id" value="<?php echo $x_solicitud_status_id; ?>"  />

<?php

		}
				}

		?>

        </td>
      </tr>
      <tr>
        <td class="texto_normal">Cliente No:</td>
        <td colspan="3"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_cliente_id) ?>
                  <input type="hidden" name="x_cliente_id" value="<?php echo htmlspecialchars(@$x_cliente_id) ?>" />
        </b></div></td>
        <td><div align="right"><div><span class="texto_normal">Credito No:</span></div></td>
        <td><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_credito_id) ?> </b></div></td>
      </tr>
      <tr>
        <td class="texto_normal">Promotor:</td>
        <td colspan="2"><div class="phpmaker">
            <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		
		$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == @$x_promotor_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["nombre_completo"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>
        </div></td>
        <td>&nbsp;</td>
        <td> <div align="right"></div></td>
        <td><div class="phpmaker"></div></td>
      </tr>
        <tr>
        <td class="texto_normal">Zona:</td>
        <td colspan="3"><?php
		$x_estado_civil_idList = "<select name=\"x_zona_id\"  id=\"x_zona_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 1) {
			$sSqlWrk = "SELECT `zona_id`, `descripcion` FROM `zona`";
		}else{
			if(!empty($x_zona_id)){
			$sSqlWrk = "SELECT `zona_id`, `descripcion` FROM `zona` WHERE zona_id = $x_zona_id";
			}else{
				$sSqlWrk = "SELECT `zona_id`, `descripcion` FROM `zona`";
				}
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["zona_id"] == @$x_zona_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?></td>
        <td class="texto_normal">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;

        </td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="3" class="texto_normal"><b><?php echo($x_tipo_credito_descripcion) ?></b>
		<input type="hidden" name="x_credito_tipo_id" value="<?php echo ($x_credito_tipo_id) ;?>" />
        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo $currdate; ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal"><?php if ($x_solicitud_status_id < 12){ echo "Monto a otorgar:";}else{ echo "Monto otorgado";}?> </span></td>
        <td width="111" colspan="2"><div align="left">
        <?php if($x_solicitud_status_id < 5){?>
            <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php if ($x_solicitud_status_id == 2 and $_SESSION["php_project_esf_status_UserRolID"] != 2 ){echo "----";}else{ echo  FormatNumber(@$x_importe_solicitado,0,0,0,0);}?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" onBlur="validaimporte()" <?php echo $x_readonly;?>/><?php } else{
				echo FormatNumber(@$x_importe_solicitado,0,0,0,0);?>
                <input  type="hidden" name="x_importe_solicitado" id="x_importe_solicitado" value="<?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?>" />
				<?php }  ?>
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos:</span></div></td>
        <td>
        <?php if($x_solicitud_status_id < 5){?>
        <span class="texto_normal"><input type="text" name="x_plazo_id" id="x_plazo_id"  value="<?php echo $x_plazo_id;?>" maxlength="3" size="15" onKeyPress="return solonumeros(this,event)" onChange="valorMax();" <?php echo $x_readonly;?> /><?php } else {  echo $x_plazo_id; ?>
		<input type="hidden" name="x_plazo_id" id="x_plazo_id"  value="<?php echo $x_plazo_id;?>"   />
		<?php }		?>


          <?php
		/*$x_estado_civil_idList = "<select name=\"x_plazo_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `plazo_id`, `descripcion` FROM `plazo` order by valor";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["plazo_id"] == @$x_plazo_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;*/
		?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Capacida de pago(CATORCENAL)</span></td>
        <td colspan="2"><input type="text" class="importe"  size="10" maxlength="10" name="x_monto_maximo_aprobado" value="<?php echo  FormatNumber($x_monto_maximo_aprobado,0,0,0,0);?>"  onchange="muestraMontoSolicitado();" <?php echo $x_readonly;?> /> </td>
        <td>&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
        <td><span class="texto_normal">
          <?php

		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
					$x_valor = $datawrk["descripcion"];
					$x_forma_pago_id = $datawrk["forma_pago_id"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		 if($x_solicitud_status_id < 5){
		echo $x_estado_civil_idList;
		  }else{
			  echo $x_valor;
			  ?>
              <input type="hidden" name="x_forma_pago_id" value="<?php echo $x_forma_pago_id; ?>" />
              <?php

			  }
		?>
        </span></td>
      </tr>
      <tr>
        <td colspan="2"><span class="texto_normal">Monto solicitado</span></td>
        <td colspan="2"><input class="importe"  size="10" maxlength="10" type="text" name="x_monto_solicitado"  <?php echo $x_readonly;?> id="x_monto_solicitado" value="<?php if ($x_solicitud_status_id == 2 and $_SESSION["php_project_esf_status_UserRolID"] != 2 ){echo "----";}else{ echo  FormatNumber(@$x_monto_solicitado,0,0,0,0);}?>" /></td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="2" style="color:#00F"><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?><div align="right"><strong>Indique el d&iacute;a en que se otorgar&aacute; el cr&eacute;dito</strong></div><?php }?></td>
        <td colspan="2"><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?><span class="texto_normal">
              <input name="x_dia_otorga_credito" maxlength="" type="text"  id="x_dia_otorga_credito" value="<?php echo FormatDateTime(@$x_dia_otorga_credito,7); ?>" size="20"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_dia_otorga_credito" onClick="javascript: Calendar.setup(
            {
            inputField : 'x_dia_otorga_credito',
            ifFormat : '%d/%m/%Y',
            button : 'cx_dia_otorga_credito'
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span><?php }?></td>
      </tr>
		<tr>
        <td>&nbsp;</td>
      </tr>
		<tr>
      	<td colspan="2"><span class="texto_normal">Minimo de depositos</span></td>
      	<td colspan="2"><input class="importe"  size="10" maxlength="10" type="text" name="x_minimo" id="x_minimo" value="<?php echo $x_minimo; ?>" <?php echo $x_readonly;?> /></td>
        <td colspan="2"><span class="texto_normal">Maximo de depositos</span></td>
      	<td colspan="2"><input class="importe"  size="10" maxlength="10" type="text" name="x_maximo" id="x_maximo" value="<?php echo $x_maximo; ?>" <?php echo $x_readonly;?> /></td>
      </tr>
      <tr>
      	<td colspan="10"><p>* Recuerde que si ingresa 0 no se tomara en cuenta el minimo o el maximo</p></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
       <tr>
        <td><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?>
        <div ><strong>N&uacute;mero de tarjeta</strong></div>
        <?php }?></td>
        <td colspan="2"><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?><span class="texto_normal">
              <input name="x_tarjeta_referenciada" maxlength="" type="text"  id="x_tarjeta_referenciada" value="<?php echo $x_tarjeta_referenciada; ?>" size="25"  />
              &nbsp;
              </span><?php }?></td>
        <td>&nbsp;</td>
        <td ><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?>
        <div align="right"><strong>N&uacute;mero de cheque </strong></div><?php }?></td>
        <td><?php if ($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12 ){ ?><span class="texto_normal">
              <input name="x_numero_cheque" maxlength="" type="text"  id="x_numero_cheque" value="<?php echo FormatDateTime(@$x_no_cheque,7); ?>" size="20"  />
              &nbsp;
              </span><?php }?></td>
      </tr>
        <tr>
        <td><?php if ($x_solicitud_status_id == 2  || $x_solicitud_status_id == 3 || $x_solicitud_status_id == 12){ ?>
        <div style="color:#F00" ><strong>Se hizo Supervisi&oacute;n?</strong></div>
        <?php }?></td>
        <td colspan="2"><?php if ($x_solicitud_status_id == 2 || $x_solicitud_status_id == 3 || $x_solicitud_status_id == 12  ){ ?> <div ><strong>
              <input type="checkbox" name="x_realizo_supervision" value="<?php echo $x_realizo_supervision;?>" id="x_realizo_supervision"  <?php if ($x_realizo_supervision == 1){?>checked="checked"<?php }?> >Si
              &nbsp;
              </strong></div><?php }?></td>
        <td>&nbsp;</td>
        <td class="texto_normal" align="right" >Tipo cliente:</td>
        <td><?php
		$x_estado_civil_idList = "<select name=\"x_cliente_tipo_id\"  id=\"x_cliente_tipo_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 2) {
			$sSqlWrk = "SELECT `cliente_tipo_id`, `descripcion` FROM `cliente_tipo`";
		}else{
			if(!empty($x_cliente_tipo_id)){
			$sSqlWrk = "SELECT `cliente_tipo_id`, `descripcion` FROM `cliente_tipo` WHERE cliente_tipo_id = $x_cliente_tipo_id";
			}else{
				$sSqlWrk = "SELECT `cliente_tipo_id`, `descripcion` FROM `cliente_tipo`";
				}
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["cliente_tipo_id"] == @$x_cliente_tipo_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?></td>
      </tr>
      <tr>
        <td><?php if ($x_solicitud_status_id == 2 ){ ?>
        <div style="color:#F00" ><strong>Se realiz&oacute; c&aacute;lculo CPC ?</strong></div>
        <?php }?></td>
        <td colspan="2"><?php if ($x_solicitud_status_id == 2  ){ ?> <div ><strong>
              <input type="checkbox" name="x_calculo_capacidad_pago" value="0" id="x_calculo_capacidad_pago">Si
              &nbsp;
              </strong></div><?php }?></td>
        <td>&nbsp;</td>
        <td  >&nbsp;</td>
        <td>&nbsp;</td>
      </tr>


    </table></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr></table>


  <!--aqui inserto el formulario----.................................................................................................-->
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="12"  align="left" valign="top"class="texto_normal_bold"><div></div></td>
    </tr>
     <tr>
      <td colspan="12"  align="left" valign="top"class="texto_normal_bold"><iframe name="aval" src="php_visor_aval.php?key=<?php echo $x_solicitud_id;?>" scrolling="no" style="margin-left:0px; width:150px; height:35px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><iframe name="aval" src="php_visor_galeria_cliente.php?key=<?php echo $x_solicitud_id;?>" scrolling="no" style="margin-left:0px; width:150px; height:35px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><iframe name="aval" src="php_visor_galeria_aval.php?key=<?php echo $x_solicitud_id;?>" scrolling="no" style="margin-left:0px; width:150px; height:35px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe></td>
    </tr>
  <tr>
      <td colspan="12"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales </td>
    </tr>

     <tr>
      <td colspan="12" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="98" class="texto_normal" >Titular</td>
      <td colspan="9" align="center"><table width="98%">
        <tr>
          <td width="33%">
          <input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlentities($x_nombre_completo)?>" maxlength="250" size="35" <?php echo $x_readonly;?>  />
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
      <td width="98" class="texto_normal" >Proveedor de Recursos</td>
      <td colspan="9" align="center">
      	<table width="98%">
	        <tr>
	          <td width="90">
	          <input type="text" name="x_proveedor_nombrecompleto" id="x_proveedor_nombrecompleto" value="<?php echo htmlentities(@$x_proveedor_nombrecompleto) ?>" <?php echo $x_readonly;?> maxlength="250" size="150" required="required"  />
	          </td>

	        </tr>
	        <tr>
	          <td class="texto_normal"> Nombre Completo</td>
	        </tr>
        </table>
    </td>
    </tr>

    <tr>
      <td width="98" class="texto_normal" >Beneficiario</td>
      <td colspan="9" align="center">
      	<table width="98%">
	        <tr>
	          <td width="90">
	          <input type="text" name="x_beneficiario_nombrecompleto" id="x_beneficiario_nombrecompleto" value="<?php echo htmlentities(@$x_beneficiario_nombrecompleto) ?>" <?php echo $x_readonly;?> maxlength="250" size="150" required="required"  />
	          </td>

	        </tr>
	        <tr>
	          <td class="texto_normal"> Nombre Completo</td>
	        </tr>
        </table>
    </td>
    </tr>
     <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" <?php echo $x_readonly;?> id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="25" onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onClick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento',
            ifFormat : '%d/%m/%Y',
            button : 'cx_fecha_nacimiento'
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span></td>
      <td width="66">Genero</td>
      <td width="110"><label>
        <select name="x_sexo" id="x_sexo" <?php echo $x_readonly2;?> onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option>
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
       <td width="317" colspan="2">País de nacimiento</td>
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
      <td><input type="button" value="RFC" id="x_calcula_rfc" /></td>
      <td colspan="2"><div id="txtHintrfc"><input type="text" name="x_rfc" id="x_rfc" size="20" maxlength="150" value="<?php echo htmlentities(@$x_rfc) ?>" <?php echo $x_readonly;?> /></div></td>
      <td width="66"><input type="button" id="x_calcula_curp" value="CURP" /></td>
      <td width="110" align="left"><div id="txtHintcurp"><input type="text" name="x_curp" id="x_curp" size="20" maxlength="150" value="<?php echo htmlentities(@$x_curp) ?>"  <?php echo $x_readonly;?>/></div></td>
      <td>&nbsp;Correo &nbsp;Electronico</td>
      <td>&nbsp;</td>
      <td colspan="5"><input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>"  maxlength="100" size="35" <?php echo $x_readonly;?>/></td>
    </tr>

    <tr>
      <td>Integrantes Familia</td>
      <td colspan="2"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>" maxlength="30" size="25"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
      <td colspan="2">Dependientes</td>
      <td colspan="7"><input type="text" name="x_dependientes" id="x_dependientes" value="<?php echo htmlspecialchars(@$x_dependientes) ?>"  maxlength="30" size="30"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="11"><input type="text" name="x_esposa" id="x_esposa"  value="<?php echo htmlspecialchars(@$x_esposa) ?>" maxlength="250" size="100" <?php echo $x_readonly;?>/></td>
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
      <td colspan="7"><?php
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
      <td colspan="7"><?php
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
    	<td>Actividad Productiva</td>
    	<td colspan="7"><?php
		$x_actividad_idList = "<select name='x_actividad_id' id='x_actividad_id'  >";
		$x_actividad_idList .= "<option value='0'>Seleccione</option>";
		$sSqlWrk = "SELECT catalogo_actividad_economica_is, actividad_economica FROM catalogo_actividad_economica";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_actividad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["catalogo_actividad_economica_is"] == @$x_actividad_id) {
					$x_actividad_idList .= "' selected";
				}
				$x_actividad_idList .= ">" . htmlentities($datawrk["actividad_economica"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_actividad_idList .= "</select>";
		echo $x_actividad_idList;
		?></td>
		<td colspan="2"></td>
		<td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="12" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="12" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="12" id="tableHead"><p></td>
    </tr>

    <tr>
      <td colspan="12"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Personas Pol&iacute;ticamente Expuestas</td>
    </tr>
     <tr>
      <td colspan="11">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="4">Usted o alg&uacute;n familiar  cuenta con cargo pol&iacute;tico, dentro o fuera del territorio M&eacute;xicano? </td>
      <td><select name="x_ppe" id="x_ppe" <?php echo $x_readonly2;?> >
        <option value="0">Seleccione</option>
        <option value="1" <?php  if($x_ppe == 1) {?> selected="selected" <?php }?> >Si</option>
        <option value="2" <?php  if($x_ppe == 2) {?> selected="selected" <?php }?>>No</option>
      </select></td>
      <td colspan="7"><table width="341"  border="0" cellpadding="0" cellspacing="0">
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
      <td colspan="12"  align="left"><table width="71%">
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
    <tr id="puesto_ppe_renglon">
    <td colspan="2">Indicar el puesto de la PPE</td>
      <td colspan="6">
      <?php
		$x_estado_civil_idList = "<select name=\"x_reporte_cnbv_puesto_ppe_id\"  class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";

			$sSqlWrk = "SELECT `reporte_cnbv_puesto_ppe_id`, `id_dependencia` , `dependencia`, `puesto` FROM `reporte_cnbv_puestos_ppe` order by  id_dependencia ASC";

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["reporte_cnbv_puesto_ppe_id"] == @$x_reporte_cnbv_puesto_ppe_id) {
					$x_estado_civil_idList .= "' selected";
				}
				if($x_ppe ==1){
				$x_estado_civil_idList .= ">" . htmlentities(substr(strtoupper($datawrk["puesto"]), 0, 50))." - ".  htmlentities(substr(strtoupper ($datawrk["dependencia"]), 0, 50)). "</option>";
				}else{
					$x_estado_civil_idList .= ">" . htmlentities(substr(strtoupper($datawrk["puesto"]), 0, 50))." - ".  htmlentities(substr(strtoupper ($datawrk["dependencia"]), 0, 20)). "</option>";
					}
				$rowcntwrk++;
			}
		}

		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>

      </td>

    </tr>
    <tr>
      <td colspan="12" id="tableHead"><p></td>
    </tr>

    <tr>
      <td colspan="12" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Domicilio </td>
    </tr>
      <tr>
      <td colspan="12" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="12" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="12" id="tableHead"><input  type="hidden"  name="x_hidden_mapa_negocio" id="x_hidden_mapa_negocio" value="<?php echo $x_hidden_mapa_negocio;?>" />&nbsp;</td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlentities($x_calle_domicilio);?>"  maxlength="100" size="60" <?php echo $x_readonly;?>/></td>
      <td colspan="6">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>" maxlength="100" size="50" <?php echo $x_readonly;?>/></td>
      <td colspan="2">C&oacute;digo Postal </td>
      <td colspan="5"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad_domicilio\" id=\"x_entidad_domicilio\" $x_readonly2 onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			//die(var_dump($x_entidad_domicilio));
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_domicilio) {
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
      <td colspan="7"><div align="left"><span class="texto_normal">

        </span><span class="texto_normal">
          <div id="txtHint1" class="texto_normal">
            Alcaldia:
            <?php
		if($x_entidad_domicilio > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\"  echo $x_readonly2>";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_domicilio";
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
$x_delegacion_idList = "<select name=\"x_localidad_id\" $x_readonly2 >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad where delegacion_id = $x_delegacion_id";
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

echo $x_delegacion_idList;
      ?>
      </div></td>
      <td colspan="2">Ubicacion</td>
      <td colspan="5">
        <strong>
        <input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?>/>
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
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_vivienda) {
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
      <td colspan="2">Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad" id="x_antiguedad"  value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" maxlength="10" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td colspan="2">Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?>" maxlength="20" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td height="24"><?php if(!empty($x_telefono_domicilio)){?>Tel Domicilio<?php }?></td>
      <td width="144"><?php if(!empty($x_telefono_domicilio)){?><input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio"  value="<?php echo htmlspecialchars(@$x_telefono_domicilio) ?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php } ?></td>
      <td width="87"><?php if(!empty( $x_otro_tel_domicilio_1)){?>Otro Tel<?php }?></td>
      <td colspan="2"><?php if(!empty($x_otro_tel_domicilio_1 )){?><input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1"  value="<?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php }?></td>
      <td colspan="2">Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensula_domicilio" id="x_renta_mensula_domicilio" value="<?php echo number_format(@$x_renta_mensula_domicilio, 0, '.', '') ?>" maxlength="25" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td><?php if(!empty($x_celular)){?>Celular<?php }?></td>
      <td><?php if(!empty($x_celular)){?><input type="text" name="x_celular" id="x_celular" value="<?php echo $x_celular;?>"  maxlength="10" size="25" <?php echo $x_readonly;?>/><?php } ?></td>
      <td><?php if(!empty($x_compania_celular_id)){?>Compa&ntilde;ia <?php }?></td>
      <td colspan="2"><?php if(!empty($x_compania_celular_id)){?><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id\" id=\"x_compania_celular_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
      <td><?php if(!empty($x_otro_telefono_domicilio_2)){?>Otro Celular<?php }?></td>
      <td><?php if(!empty($x_otro_telefono_domicilio_2)){?><input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2"  value="<?php echo $x_otro_telefono_domicilio_2;?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php }?></td>
      <td><?php if(!empty($x_compania_celular_id_2)){?>Compa&ntilde;ia<?php }?></td>
      <td colspan="2"><?php if(!empty($x_compania_celular_id_2)){?><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id_2\" id=\"x_compania_celular_id_2\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
      <td colspan="7"><div id="x_guarda_direccion_a"><input type="button" value="Guardar direccion"  onclick="GuardaDireccionAntigua(<?php echo $x_cliente_id;?>)"/></div></td>
    </tr>
      <tr>
      <td colspan="12" id="tableHead"><p></td>
    </tr>
     <tr>
      <td colspan="12"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Telefonos nueva seccion </td>
    </tr>

    <tr>
      <td colspan="12" id="tableHead"><table width="100%" border="0">
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
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
      <td colspan="12"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio</td>
    </tr>
     <tr>
      <td colspan="12" ><strong>Mismos datos que el domicilio <input type="checkbox" name="x_mismos_datos_domiclio" <?php echo $x_checked;?>  onchange="llenaDatosNegocio();" /></strong></td>
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
      <td colspan="2"><p>Tipo Negocio</p></td>
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
      <td colspan="2"><p>No.Personas trabajando</p></td>
      <td colspan="5"><input type="text" name="x_personas_trabajando" value="<?php echo($x_personas_trabajando);?>" maxlength="20" size="10" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_negocio" id="x_calle_negocio" value="<?php echo $x_calle_negocio; ?>"  maxlength="250" size="60"/></td>
      <td colspan="6">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior2" id="x_numero_exterior2" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_negocio" id="x_colonia_negocio"  value="<?php echo htmlspecialchars(@$x_colonia_negocio) ?>" maxlength="250" size="70" <?php echo $x_readonly;?>/></td>
      <td colspan="2"><p>C&oacute;digo Postal</p></td>
      <td colspan="5"> <input type="text" name="x_codigo_postal_negocio" id="x_codigo_postal_negocio" value="<?php echo htmlspecialchars(@$x_codigo_postal_negocio)?>"  maxlength="25" size="30"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList2 = "<select name=\"x_entidad_negocio\" id=\"x_entidad_negocio\" $x_readonly2  onchange=\"showHint(this,'txtHint2', 'x_delegacion_id2')\" >";
		$x_entidad_idList2 .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList2 .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_negocio) {
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
      <td colspan="7"><div align="left"><span class="texto_normal">
        <input type="hidden" name="x_delegacion_id_temp" value="" />

        </span><span class="texto_normal">
          <div id="txtHint2" class="texto_normal">
            Alcaldia:
            <?php
		if($x_entidad_negocio > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id2\" $x_readonly2 >";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_negocio";
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
$x_delegacion_idList = "<select name=\"x_localidad_id2\" $x_readonly2  >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad where delegacion_id = $x_delegacion_id";
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

echo $x_delegacion_idList;
      ?></div></td>
      <td colspan="2">Ubicacion</td>
      <td colspan="5">
        <strong></strong>


      <input type="text" name="x_ubicacion_negocio" id="x_ubicacion_negocio" value="<?php echo htmlspecialchars(@$x_ubicacion_negocio) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
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
      <td colspan="2">Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio"  value="<?php echo htmlspecialchars(@$x_antiguedad_negocio) ?>" maxlength="10" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Ingreso semanal</td>
      <td colspan="4"><input type="text" name="x_ingreso_semanal" id="x_ingreso_semanal" value="<?php echo $x_ingreso_semanal;?>"  maxlength="50" size="20" onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
      <td colspan="2">Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_negocio) ?>" maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><input type="text" name="x_tel_negocio" id="x_tel_negocio"  value="<?php echo htmlspecialchars(@$x_tel_negocio) ?>" maxlength="50" size="25" <?php echo $x_readonly;?>/></td>
      <td colspan="2">Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual" id="x_renta_mensual"  value="<?php echo number_format(@$x_renta_mensual, 0, '.', '') ?>" maxlength="50" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
      <tr>
      <td colspan="12" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="12"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Garantia</td>
    </tr>
     <tr>
      <td colspan="12"  align="left"><table width="90%" height="26" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="171" >Tipo de garantia</td>
          <td width="444" ><input type="text" name="x_tipo_garantia"  value="<?php echo $x_tipo_garantia;?>" maxlength="500" size="60" <?php echo $x_readonly;?>/></td>
          <td width="172" >Modelo, A&ntilde;o</td>
          <td width="276"><input type="text" name="x_modelo_garantia" value="<?php echo $x_modelo_garantia; ?>"maxlength="500" size="60"  <?php echo $x_readonly;?>/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="12"><center><textarea cols="80" rows="4" id="x_garantia_desc" name="x_garantia_desc" <?php echo $x_readonly;?>><?php echo @$x_garantia_desc; ?></textarea></center></td>
    </tr>
    <tr>
      <td colspan="12">&nbsp;</td>
  </tr>

  <tr>
      <td colspan="12"><table width="80%">
        <tr>
          <td>Valor comercial</td>
          <td><input type="text" name="x_garantia_valor" id="x_garantia_valor" value="<?php  echo number_format($x_garantia_valor, 0, '.', '');?>" onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
          <td>Valor factura</td>
          <td><input type="text" name="x_garantia_valor_factura" id="x_garantia_valor_factura"  value="<?php  echo number_format($x_garantia_valor_factura, 0, '.', '');?>" onKeyPress="return solonumeros(this,event)"  <?php echo $x_readonly;?>/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="12"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="250"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_com_1" id="x_referencia_com_1" value="<?php echo htmlspecialchars(@$x_referencia_com_1) ?>"  maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1"  value="<?php echo htmlspecialchars(@$x_tel_referencia_1) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
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
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_1) {
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
        <td width="277"><input type="text" name="x_referencia_com_2" id="x_referencia_com_2"  value="<?php echo htmlspecialchars(@$x_referencia_com_2) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2"  value="<?php echo htmlspecialchars(@$x_tel_referencia_2) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
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
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_2) {
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
      <td width="276"><input type="text" name="x_referencia_com_3" id="x_referencia_com_3"  value="<?php echo htmlspecialchars(@$x_referencia_com_3) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="19">Tel</td>
        <td width="152"><input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3"  value="<?php echo htmlspecialchars(@$x_tel_referencia_3) ?>"  maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
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
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_3) {
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
      <td width="276"><input type="text" name="x_referencia_com_4" id="x_referencia_com_4"  value="<?php echo htmlspecialchars(@$x_referencia_com_4) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="17">Tel</td>
        <td width="131"><input type="text" name="x_tel_referencia_4" id="x_tel_referencia_4"  value="<?php echo htmlspecialchars(@$x_tel_referencia_4) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
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
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_4) {
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
  <?php  if ($x_fecha_registro > "2012-07-02") {
	  // si la fecha de registro es mayor al 28/06/2012 entonces las solcitudes ya se gaurdaron con los nuevos campos de ingresos
	  // y al editar se debe mostrar los campos

	  ?>
  <tr>
    <td colspan="12">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Familiares</td>
      </tr>
      <tr>
        <td colspan="11" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="153">Ingreso del Negocio</td>
        <td colspan="4"><input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio"  value="<?php echo number_format(@$x_ing_fam_negocio, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
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
        <td colspan="4"><input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th" value="<?php echo number_format(@$x_ing_fam_otro_th, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1"   value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?>" maxlength="250" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Familiar 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_1" id="x_ing_fam_1"   value="<?php echo number_format(@$x_ing_fam_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?>" maxlength="250" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Familiar  2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_2" id="x_ing_fam_2"  value="<?php echo number_format(@$x_ing_fam_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_3" id="x_ing_fam_cuales_3"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_3) ?>" maxlength="250" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Deuda 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1"  value="<?php echo number_format(@$x_ing_fam_deuda_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?>" maxlength="250" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Deuda 2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2"  value="<?php echo number_format(@$x_ing_fam_deuda_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?>"  maxlength="250" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Total</td>
        <td colspan="4"><input type="text" name="x_ing_fam_total" id="x_ing_fam_total"  value="<?php echo number_format(@$x_ing_fam_total, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
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
        <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas" value="<?php echo number_format(@$x_flujos_neg_ventas, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="5">&nbsp;</td>
        <td width="13" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1" value="<?php echo number_format(@$x_flujos_neg_proveedor_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  value="<?php echo number_format(@$x_flujos_neg_proveedor_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?>"  maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  value="<?php echo number_format(@$x_flujos_neg_proveedor_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>

      </tr>
      <tr>
        <td width="153">Proveedor 4</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  value="<?php echo number_format(@$x_flujos_neg_proveedor_4, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1" value="<?php echo number_format(@$x_flujos_neg_gasto_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  value="<?php echo number_format(@$x_flujos_neg_gasto_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  value="<?php echo number_format(@$x_flujos_neg_gasto_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?>" maxlength="100" size="35"/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Negocio</td>
        <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  value="<?php echo number_format(@$x_ingreso_negocio, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
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
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?>"  maxlength="250" size="35" /></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  value="<?php echo number_format(@$x_inv_neg_fija_valor_1, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?>" maxlength="250" size="35"/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_1" value="<?php echo number_format(@$x_inv_neg_var_valor_1, 0, '.', '') ?>" id="x_inv_neg_var_valor_1"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2" value="<?php echo number_format(@$x_inv_neg_fija_valor_2, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?>" maxlength="250" size="35"/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_2"  value="<?php echo number_format(@$x_inv_neg_var_valor_2, 0, '.', '') ?>" id="x_inv_neg_var_valor_2"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  value="<?php echo number_format(@$x_inv_neg_fija_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?>" maxlength="250" size="35"/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  value="<?php echo number_format(@$x_inv_neg_var_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4" value="<?php echo number_format(@$x_inv_neg_fija_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?>"  maxlength="250" size="35"/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  value="<?php echo number_format(@$x_inv_neg_var_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          </tr>
        <tr>
          <td width="153" align="right">Total</td>
          <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  value="<?php echo number_format(@$x_inv_neg_total_fija, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td align="right">Total:</td>
          <td colspan="11"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"   value="<?php echo number_format(@$x_inv_neg_total_var, 0, '.', '') ?>"maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
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
          <td colspan="11"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales" value="<?php echo number_format(@$x_inv_neg_activos_totales, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>

      </table>




  </td></tr><?php } ?>
    <tr>
      <td colspan="12">
      <table width="100%" align="center">

      <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Envio de Mail</td>
    </tr>
    <tr>
    <td colspan="2">Enviar a:      </td>
    <td colspan="6"><?php
		$x_entidad_idList = "<select name=\"x_destinatario_mail\"  $x_readonly2 id=\"x_destinatario_mail\"  >";
		$x_entidad_idList .= "<option value=''> Indique a quien enviara el mail de supervision </option>";
		$sSqlWrk = "SELECT `mail_destinatario_id`, `nombre_completo`, direccion_correo FROM `mail_destinatario`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[2]) . "\"";
				if ($datawrk["mail_destinatario_id"] == @$x_destinatario_mail) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre_completo"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>                 </td>
    <td width="5%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="6%">&nbsp;   </td>
   </tr>
     <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center"><textarea cols="100" rows="5" name="x_cuerpo_mail" id="" <?php echo $x_readonly;?>><?php echo $x_cuerpo_mail; ?> </textarea></td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Promotor</td>
    </tr>
    <tr>
    <td width="9%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="14%">&nbsp;   </td>
    <td width="10%">&nbsp;   </td>
    <td width="2%">&nbsp;   </td>
    <td width="12%">&nbsp;   </td>
    <td width="4%">&nbsp;   </td>
    <td width="28%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="6%">&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a domicilio de TH            </td>
    <td><select name="x_v_p_d_th" id="x_v_p_d_th" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_p_d_th == 1){ ?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_p_d_th == 2) {?> selected="selected" <?php } ?>>No</option>
    </select>   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a Negocio de TH             </td>
    <td><select name="x_v_p_n_th" id="x_v_p_n_th" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_p_n_th == 1) {?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_p_n_th == 2){ ?> selected="selected" <?php } ?>>No</option>
    </select>  </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center">Resultado de la visita al TH</td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center"><textarea cols="100" rows="5" name="x_resultado_visita_pro_th" id="" <?php echo $x_readonly;?>><?php echo $x_resultado_visita_pro_th; ?> </textarea></td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a domicilio de Aval</td>
    <td><select name="x_v_p_d_a" id="x_v_p_d_a" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_p_d_a == 1) {?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_p_d_a == 2) {?> selected="selected" <?php } ?>>No</option>
    </select>   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a Negocio de Aval</td>
    <td><select name="x_v_p_n_a" id="x_v_p_n_a" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_p_n_a == 1){ ?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_p_n_a == 2) {?> selected="selected" <?php } ?>>No</option>
    </select>  </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center">Resultado de la               visita al Aval</td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center"><textarea cols="100" rows="5" name="x_resultado_visita_pro_aval" id="" <?php echo $x_readonly;?>><?php echo $x_resultado_visita_pro_aval; ?> </textarea></td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
   <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Supervisor</td>
    </tr>
     <tr>
    <td width="9%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="14%">&nbsp;   </td>
    <td width="10%">&nbsp;   </td>
    <td width="2%">&nbsp;   </td>
    <td width="12%">&nbsp;   </td>
    <td width="4%">&nbsp;   </td>
    <td width="28%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="5%">&nbsp;   </td>
    <td width="6%">&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a domicilio de TH            </td>
    <td><select name="x_v_s_d_th" id="x_v_s_d_th" <?php echo $x_readonly2;?> >
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_s_d_th == 1) {?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_s_d_th == 2) {?> selected="selected" <?php } ?>>No</option>
    </select>   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a Negocio de TH             </td>
    <td><select name="x_v_s_n_th" id="x_v_s_n_th" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_s_n_th == 1) {?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_s_n_th == 2) {?> selected="selected" <?php } ?>>No</option>
    </select>  </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center">Resultado de la               visita alTH</td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center">
    <?php if($x_solicitud_status_id == 9 and ($_SESSION["php_project_esf_status_UserRolID"] == 12 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 17 || $_SESSION["php_project_esf_status_UserRolID"] == 7  )){?>
    <textarea cols="100" rows="5" name="x_resultado_visita_sup_th" id="" ><?php echo $x_resultado_visita_sup_th; ?></textarea>
    <?php } else{?>
		<textarea cols="100" rows="5" name="x_resultado_visita_sup_th" id="" <?php echo $x_readonly;?>><?php echo $x_resultado_visita_sup_th; ?></textarea>
		<?php }?>
    </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a domicilio de Aval</td>
    <td><select name="x_v_s_d_a" id="x_v_s_d_a" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_s_d_a == 1){ ?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_s_d_a == 2){ ?> selected="selected" <?php } ?>>No</option>
    </select> </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="4">Se realizo la visita a Negocio de Aval</td>
    <td><select name="x_v_s_n_a" id="x_v_s_n_a" <?php echo $x_readonly2;?>>
    <option value="0">Seleccione</option>
     <option value="1"   <?php if($x_v_s_n_a == 1){ ?> selected="selected" <?php } ?>>Si</option>
      <option value="2"  <?php if($x_v_s_n_a == 2){ ?> selected="selected" <?php } ?>>No</option>
    </select>    </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>
    <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center">Resultado de la               visita al Aval</td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

   <tr>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td colspan="6" align="center"><textarea cols="100" rows="5" name="x_resultado_visita_sup_aval" id="" <?php echo $x_readonly;?>><?php echo $x_resultado_visita_sup_aval;?> </textarea></td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
    <td>&nbsp;   </td>
   </tr>

    </table>
      </td>
  </tr>

    <tr>
      <td colspan="12">
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
	    <textarea name="x_comentario_promotor" cols="100" rows="5" <?php echo $x_readonly;?>><?php echo $x_comentario_promotor; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Gesti&oacute;n de An&aacute;lisis</span></div></td>
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

       <?php if($x_solicitud_status_id == 2 and ($_SESSION["php_project_esf_status_UserRolID"] == 12 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1||  $_SESSION["php_project_esf_status_UserRolID"] == 2)){?>

          <?php
		$x_entidad_idList = "<select name=\"x_comite_credito_explicacion_id\"  id=\"x_comite_credito_explicacion_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `comite_credito_explicacion_id`, `descripcion` FROM `comite_credito_explicacion`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["comite_credito_explicacion_id"] == @$x_comite_credito_explicacion_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>




        <?php }else{?>

               <?php
		$x_entidad_idList = "<select name=\"x_comite_credito_explicacion_id\" $x_readonly2 id=\"x_comite_credito_explicacion_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `comite_credito_explicacion_id`, `descripcion` FROM `comite_credito_explicacion`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["comite_credito_explicacion_id"] == @$x_comite_credito_explicacion_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>

         <?php } ?>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">
       Comentarios  de An&aacute;lisis
       </span></div></td>
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

	    <textarea name="x_comentario_comite" cols="100" rows="5" <?php echo $x_readonly;?> ><?php echo $x_comentario_comite;?></textarea>

	      </div></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold"><?php if(($x_solicitud_status_id == 4 || $x_solicitud_status_id == 2 ) and ($_SESSION["php_project_esf_status_UserRolID"] == 2 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1||  $_SESSION["php_project_esf_status_UserRolID"] == 4 ||  $_SESSION["php_project_esf_status_UserRolID"] == 13)){?> Comentarios del Comite de Cr&eacute;dito Interno<?php }?></span></div></td>
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
       <?php if(($x_solicitud_status_id == 4 || $x_solicitud_status_id == 2 )  and ($_SESSION["php_project_esf_status_UserRolID"] == 2 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1 ||  $_SESSION["php_project_esf_status_UserRolID"] == 4  ||  $_SESSION["php_project_esf_status_UserRolID"] == 13 )){?>
	    <textarea name="x_comentario_comite_interno" cols="100" rows="5" <?php echo $x_readonly;?> ><?php echo $x_comentario_comite_interno; ?></textarea>

        <?php }else{?>
         &nbsp;

         <?php } ?>
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
      <td colspan="12"></td>
  </tr>

    <tr>
      <td colspan="12"></td>
  </tr>

    <tr>
      <td colspan="12"></td>
  </tr>

    <tr>
      <td colspan="12"></td>
  </tr>

  </table>
  <!-- aqui termina.....................................................................................................................-->











     <table><tr><td>
     <?php if($_SESSION["crm_UserRolID"] < 4){
/*
capacidad de pago mensual TITULAR (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))

capacidad de pago semanal (cps) = (cpm / 4) * .40

Esto solo lo ve el coordinador de credito.
*/
  		/*	$x_ventas_menusuales = ($x_ingresos_negocio != "") ? $x_ingresos_negocio : 0;
  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;
  			$x_otros_ing = ($x_otros_ingresos != "") ? $x_otros_ingresos : 0;
  			$x_ing_fam1 = ($x_ingresos_familiar_1 != "") ? $x_ingresos_familiar_1 : 0;
  			$x_ing_fam2 = ($x_ingresos_familiar_2 != "") ? $x_ingresos_familiar_2 : 0;

  			$x_prov1 = ($x_gastos_prov1 != "") ? $x_gastos_prov1 : 0;
  			$x_prov2 = ($x_gastos_prov2 != "") ? $x_gastos_prov2 : 0;
  			$x_prov3 = ($x_gastos_prov3 != "") ? $x_gastos_prov3 : 0;
  			$x_otrop = ($x_otro_prov != "") ? $x_otro_prov : 0;
  			$x_empleados = ($x_gastos_empleados != "") ? $x_gastos_empleados : 0;
  			$x_ren_neg = ($x_gastos_renta_negocio != "") ? $x_gastos_renta_negocio : 0;
  			$x_ren_cas = ($x_gastos_renta_casa != "") ? $x_gastos_renta_casa : 0;
  			$x_hipo = ($x_gastos_credito_hipotecario != "") ? $x_gastos_credito_hipotecario : 0;
  			$x_gas_otros = ($x_gastos_otros != "") ? $x_gastos_otros : 0;

			$x_dep = ($x_numero_hijos_dep != "") ? $x_numero_hijos_dep : 0;
			$x_ren = $x_ren_cas + $x_hipo;*/


			/*$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));

			$x_ingresos2 = $x_salario;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));

			$x_cps = ($x_cpm / 4) * .40;*/


			$x_ing_fam_total = ($x_ing_fam_total != "") ? $x_ing_fam_total : 0;
			$x_ingreso_negocio = ($x_ingreso_negocio != "") ? $x_ingreso_negocio : 0;
			$x_renta_mensula_domicilio = ($x_renta_mensula_domicilio != "") ? $x_renta_mensula_domicilio : 0;
			$x_renta_mensual = ($x_renta_mensual != "") ? $x_renta_mensual : 0;
			$x_dependientes = ($x_dependientes != "") ? $x_dependientes : 0;


			$x_ingresos = $x_ing_fam_total + $x_ingreso_negocio;
			$x_rentas = $x_renta_mensula_domicilio + $x_renta_mensual +200 +300;
			$x_gasto_hijos = ($x_dependientes * 700);


			$x_cpm = $x_ingresos - ($x_rentas+ $x_gasto_hijos );
			$x_cps = ($x_cpm / 4) * .40;



			echo "<b>
			Capacidad de Pago Mensual: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal: ".FormatNumber(@$x_cps,0,2,0,1)."
			</b>
			";



/*
capacidad de pago mensual AVAL (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))

capacidad de pago semanal (cps) = (cpm / 4) * .40

Esto solo lo ve el coordinador de credito.
*/
  			$x_ventas_menusuales = ($x_ingresos_mensuales != "") ? $x_ingresos_mensuales : 0;
//  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;
  			$x_otros_ing = ($x_otros_ingresos_aval != "") ? $x_otros_ingresos_aval : 0;
  			$x_ing_fam1 = ($x_ingresos_familiar_1_aval != "") ? $x_ingresos_familiar_1_aval : 0;
  			$x_ing_fam2 = ($x_ingresos_familiar_2_aval != "") ? $x_ingresos_familiar_2_aval : 0;

  			$x_prov1 = ($x_gastos_prov1_aval != "") ? $x_gastos_prov1_aval : 0;
  			$x_prov2 = ($x_gastos_prov2_aval != "") ? $x_gastos_prov2_aval : 0;
  			$x_prov3 = ($x_gastos_prov3_aval != "") ? $x_gastos_prov3_aval : 0;
  			$x_otrop = ($x_otro_prov_aval != "") ? $x_otro_prov_aval : 0;
  			$x_empleados = ($x_gastos_empleados_aval != "") ? $x_gastos_empleados_aval : 0;
  			$x_ren_neg = ($x_gastos_renta_negocio_aval != "") ? $x_gastos_renta_negocio_aval : 0;
  			$x_ren_cas = ($x_gastos_renta_casa2 != "") ? $x_gastos_renta_casa2 : 0;
  			$x_hipo = ($x_gastos_credito_hipotecario_aval != "") ? $x_gastos_credito_hipotecario_aval : 0;
  			$x_gas_otros = ($x_gastos_otros_aval != "") ? $x_gastos_otros_aval : 0;

			$x_dep = ($x_numero_hijos_dep_aval != "") ? $x_numero_hijos_dep_aval : 0;
			$x_ren = $x_ren_cas + $x_hipo;


			/*$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));

//			$x_ingresos2 = $x_salario;
			$x_ingresos2 = 0;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));

			$x_cps = ($x_cpm / 4) * .40;
*/
			/*echo "<b>
			Capacidad de Pago Mensual AVAL: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal AVAL: ".FormatNumber(@$x_cps,0,2,0,1)."
			</b>
			";*/
			} ?></td>

    <td><div align="center">

    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table align="center">
<tr><td>

<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 3) ||($_SESSION["php_project_esf_status_UserRolID"] == 4) ||($_SESSION["php_project_esf_status_UserRolID"] == 7)||($_SESSION["php_project_esf_status_UserRolID"] == 12) ||($_SESSION["php_project_esf_status_UserRolID"] == 17)){ ?>
 <?php if(($x_solicitud_status_id != 6) || (($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 3)) || ($_SESSION["php_project_esf_status_UserRolID"] == 12) || ($_SESSION["php_project_esf_status_UserRolID"] == 17) ){ ?>

 <input name="Action2" type="button"class="boton_medium" value="Editar1" onclick="EW_checkMyForm();" /><?php } else { ?>

 <?php if(($x_solicitud_status_id >= 6) && (($_SESSION["php_project_esf_status_UserRolID"] == 3) ||($_SESSION["php_project_esf_status_UserRolID"] == 4) ||($_SESSION["php_project_esf_status_UserRolID"] == 7) )){ ?>
 <input name="Action3"  type="button" class="boton_medium" value="Editar2"  onclick="EW_checkMyForm();" /><?php } else {?>
 <input name="Action4" disabled="disabled" type="button" class="boton_medium" value="Editar2e"   />
 <?php } }
 }else {?>

 <input name="Action4" disabled="disabled" type="button" class="boton_medium" value="Editar3"   /><?php }?></td></tr>
</table>
</form>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

function LoadData($conn)
{
	global $x_solicitud_id;

	$sSql = "SELECT * FROM `solicitud`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
	$sWhere .= "(`solicitud_id` = " . addslashes($sTmp) . ")";
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

		// Get the field contents $x_cliente_id
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $row["solicitud_status_id"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		// se agraga el campo de vendedor
		$GLOBALS["x_vendedor_id"] = $row["vendedor_id"];
		$GLOBALS["x_zona_id"] = $row["zona_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];
		$GLOBALS["x_contrato"] = $row["contrato"];
		$GLOBALS["x_pagare"] = $row["pagare"];
		$GLOBALS["x_comentario_promotor"] = $row["comentario_promotor"];
		$GLOBALS["x_comentario_comite_interno"] = $row["comentario_comite_interno"];
		$GLOBALS["x_actividad_id"] = $row["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $row["actividad_desc"];
		$GLOBALS["x_monto_maximo_aprobado"] = $row["monto_maximo_aprobado"];
		$GLOBALS["x_monto_solicitado"] = $row["monto_solicitado"];
		$GLOBALS["x_cuerpo_mail"] = $row["cuerpo_mail"];
		$GLOBALS["x_destinatario_mail"] = $row["destinatario_mail"];
		$GLOBALS["x_dia_otorga_credito"] = $row["dia_otorga_credito"];
		$GLOBALS["x_fecha_visita_supervision"] = $row["fecha_visita_supervision"];
		$GLOBALS["x_realizo_supervision"] = $row["realizo_supervison"];
		$GLOBALS["x_comite_credito_explicacion_id"] = $row["comite_credito_explicacion_id"];
		$GLOBALS["x_comentario_comite"] = $row["comentario_comite"];
		/* nuevo marcos 10/02/2020 */
		$GLOBALS["x_minimo"] = $row["minimo"];
		$GLOBALS["x_maximo"] = $row["maximo"];
		$GLOBALS["x_proveedor_nombrecompleto"] = $row["proveedor_nombrecompleto"];
		$GLOBALS["x_beneficiario_nombrecompleto"] = $row["beneficiario_nombrecompleto"];








		if(intval($row["solicitud_status_id"]) == 2){
			$GLOBALS["x_actualiza_fecha_investigacion"] = "no";
			}else{
				$GLOBALS["x_actualiza_fecha_investigacion"] = "si";
				}

	 if(intval($row["solicitud_status_id"]) == 9){
			$GLOBALS["x_actualiza_fecha_supervision"] = "no";
			}else{
				$GLOBALS["x_actualiza_fecha_supervision"] = "si";
				}

		if(intval($row["solicitud_status_id"]) == 10){
			$GLOBALS["x_actualiza_fecha_otorgamiento"] = "no";
			}else{
				$GLOBALS["x_actualiza_fecha_investigacion"] = "si";
				}
//echo $x_solicitud_status_id;
		if($GLOBALS["x_solicitud_status_id"] == 10 || $GLOBALS["x_solicitud_status_id"] == 5 || $GLOBALS["x_solicitud_status_id"] == 6 || $GLOBALS["x_solicitud_status_id"] == 7 || $GLOBALS["x_solicitud_status_id"]  == 8 || $GLOBALS["x_solicitud_status_id"]  == 4 || $GLOBALS["x_solicitud_status_id"]  == 3 || $GLOBALS["x_solicitud_status_id"] == 9   || $GLOBALS["x_solicitud_status_id"] == 12){
			$GLOBALS["x_readonly"] = 'readonly = "readonly"';
			$GLOBALS["x_readonly2"] = 'disabled="disabled"';
			}




		//TIPO DE SOLICITUD
		$sqlTC ="SELECT descripcion FROM  credito_tipo JOIN solicitud ON(solicitud.credito_tipo_id = credito_tipo.credito_tipo_id ) ";
		$sqlTC.= "WHERE solicitud.solicitud_id = $x_solicitud_id";

		$rsTC = phpmkr_query($sqlTC,$conn) or die("Failed to execute query: TIPO CREDITO" . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowTC = phpmkr_fetch_array($rsTC);

		$GLOBALS["x_tipo_credito_descripcion"] = $rowTC["descripcion"];


		//CLIENTE
		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];

		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];
		$GLOBALS["x_rfc"] = $row2["rfc"];
		$GLOBALS["x_curp"] = $row2["curp"];
		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];
		$GLOBALS["x_esposa"] = $row2["nombre_conyuge"];
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_integrantes_familia"] = $row2["numero_hijos"];
		$GLOBALS["x_dependientes"] = $row2["numero_hijos_dep"];
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_correo_electronico"] = $row2["email"];
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];
		$GLOBALS["x_empresa"] = $row2["empresa"];
		$GLOBALS["x_puesto"] = $row2["puesto"];
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];
		$GLOBALS["x_rol_hogar_id"] = $row2["rol_hogar_id"];
		$GLOBALS["x_fecha_ini_act_prod"] = $row2["fecha_ini_act_prod"];



		$GLOBALS["x_entidad_nacimiento"] = $row2["entidad_nacimiento_id"];
		$GLOBALS["x_estudio_id"] = $row2["escolaridad_id"];
		$GLOBALS["x_ppe"] = $row2["ppe"];
		$GLOBALS["x_ingreso_semanal"] = $row2["ingreso_semanal"];
		$GLOBALS["x_cliente_tipo_id"] = $row2["cliente_tipo_id"];


		// numero de la tarjeta si existe del cliente
		$sSql = "SELECT credito.tarjeta_num as tarjeta_referenciada  FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where cliente.cliente_id = ".$GLOBALS["x_cliente_id"]." and not isnull(credito.tarjeta_num) limit 1";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_tarjeta_referenciada"] = $row["tarjeta_referenciada"];
		phpmkr_free_result($rs);

			//PPE

			//CLIENTE
		$sSql = "select * from ppe where cliente_id = ".$GLOBALS["x_cliente_id"]." ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_ppe_id"] = $row2["ppe_id"];
		$GLOBALS["x_parentesco_ppe"] = $row2["relacion_id"];
		$GLOBALS["x_nombre_ppe"] = $row2["nombre"];

		$GLOBALS["x_apellido_paterno_ppe"] = $row2["a_paterno"];
		$GLOBALS["x_apellido_materno_ppe"] = $row2["a_materno"];
		$GLOBALS["x_reporte_cnbv_puesto_ppe_id"] = $row2["reporte_cnbv_puesto_ppe_id"];


			//PPE

			//visita
		$sSql = "select * from visita where solicitud_id = $x_solicitud_id and tipo_visita_id = 1 and tipo_domicilio_id = 1 ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_visita_id_1"] = $row2["visita_id"];
		$GLOBALS["x_v_p_d_th"] = $row2["visita_domicilio"];
		$GLOBALS["x_v_p_n_th"] = $row2["visita_negocio"];
		$GLOBALS["x_resultado_visita_pro_th"] = $row2["resultado"];



			//visita
		$sSql = "select * from visita where solicitud_id = $x_solicitud_id and tipo_visita_id = 1 and tipo_domicilio_id = 2 ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_visita_id_2"] = $row2["visita_id"];
		$GLOBALS["x_v_p_d_a"] = $row2["visita_domicilio"];
		$GLOBALS["x_v_p_n_a"] = $row2["visita_negocio"];
		$GLOBALS["x_resultado_visita_pro_aval"] = $row2["resultado"];


			//visita
		$sSql = "select * from visita where solicitud_id = $x_solicitud_id and tipo_visita_id = 2 and tipo_domicilio_id = 1 ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_visita_id_3"] = $row2["visita_id"];
		$GLOBALS["x_v_s_d_th"] = $row2["visita_domicilio"];
		$GLOBALS["x_v_s_n_th"] = $row2["visita_negocio"];
		$GLOBALS["x_resultado_visita_sup_th"] = $row2["resultado"];

			//visita
		$sSql = "select * from visita where solicitud_id = $x_solicitud_id and tipo_visita_id = 2 and tipo_domicilio_id = 2 ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_visita_id_4"] = $row2["visita_id"];
		$GLOBALS["x_v_s_d_a"] = $row2["visita_domicilio"];
		$GLOBALS["x_v_s_n_a"] = $row2["visita_negocio"];
		$GLOBALS["x_resultado_visita_sup_aval"] = $row2["resultado"];








		$sSql = "select * from negocio where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];


		//valor de la llave cliente_id
		//$sqlClienteid = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_cliente ="


		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_tipo_vivienda"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono_domicilio"] = $row3["telefono"];
		$GLOBALS["x_celular"] = $row3["telefono_movil"];
		$GLOBALS["x_otro_tel_domicilio_1"] = $row3["telefono_secundario"];
		$GLOBALS["x_tel_arrendatario_domicilio"] = $row3["propietario"];

		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_compania_celular_id"] = $row3["compania_celular_id"];


		$GLOBALS["x_otro_telefono_domicilio_2"] = $row3["telefono_movil_2"];
		$GLOBALS["x_compania_celular_id_2"] = $row3["compania_celular_id_2"];
		//falta telefono secundario

		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);

			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			//$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];
			$GLOBALS["x_tipo_local_negocio"] = $row4["vivienda_tipo_id"];
			$GLOBALS["x_antiguedad_negocio"] = $row4["antiguedad"];
			$GLOBALS["x_tel_arrendatario_negocio"] = $row4["propietario"];
			//$GLOBALS["x_renta_mensual"] = $row4["renta_mensual"]; //renta mensula esta en gasto...mas bien no esta guardada deberia estar en gastos
			$GLOBALS["x_tel_negocio"] = $row4["telefono"];
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row3["localidad_id"];


		/*$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];

//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];*/


		$sSql = "select * from aval where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row5 = phpmkr_fetch_array($rs5);
		$GLOBALS["x_aval_id"] = $row5["aval_id"];
		$GLOBALS["x_nombre_completo_aval"] = $row5["nombre_completo"];
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];

		$GLOBALS["x_aval_rfc"] = $row5["rfc"];
		$GLOBALS["x_aval_curp"] = $row5["curp"];
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];


		$GLOBALS["x_tipo_negocio_aval"] = $row5["tipo_negocio"];
		$GLOBALS["x_edad_aval"] = $row5["edad"];

		$GLOBALS["x_tit_fecha_nac_aval"] = $row5["fecha_nac"];
		$GLOBALS["x_sexo_aval"] = $row5["sexo"];
		$GLOBALS["x_estado_civil_id_aval"] = $row5["estado_civil_id"];
		$GLOBALS["x_numero_hijos_aval"] = $row5["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep_aval"] = $row5["numero_hijos_dep"];
		$GLOBALS["x_nombre_conyuge_aval"] = $row5["nombre_conyuge"];
		$GLOBALS["x_email_aval"] = $row5["email"];
		$GLOBALS["x_nacionalidad_id_aval"] = $row5["nacionalidad_id"];


		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){

			//notenemos aval en este tipo de solicitud...este codigo no se ejecuta
			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3 order by direccion_id desc limit 1";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id3"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_propietario2"] = $row6["propietario"];
			$GLOBALS["x_entidad_id3"] = $row6["entidad_id"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono3_sec"] = $row6["telefono_movil"];
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];

			//este codigo no se ejecuta
			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 4 order by direccion_id desc limit 1";
			$rs6_2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6_2 = phpmkr_fetch_array($rs6_2);



			$GLOBALS["x_direccion_id4"] = $row6_2["direccion_id"];
			$GLOBALS["x_calle3_neg"] = $row6_2["calle"];
			$GLOBALS["x_colonia3_neg"] = $row6_2["colonia"];
			$GLOBALS["x_delegacion_id3_neg"] = $row6_2["delegacion_id"];
			$GLOBALS["x_propietario2_neg"] = $row6_2["propietario"];
			$GLOBALS["x_entidad_id3_neg"] = $row6_2["entidad_id"];
			$GLOBALS["x_codigo_postal3_neg"] = $row6_2["codigo_postal"];
			$GLOBALS["x_ubicacion3_neg"] = $row6_2["ubicacion"];
			$GLOBALS["x_antiguedad3_neg"] = $row6_2["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2_neg"] = $row6_2["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = $row6_2["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3_neg"] = $row6_2["telefono"];
			$GLOBALS["x_telefono_secundario3_neg"] = $row6_2["telefono_secundario"];

			//la tabla ingreso aval no se usa en esta solicitud
			$sSql = "select * from ingreso_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row8 = phpmkr_fetch_array($rs8);
			$GLOBALS["x_ingreso_aval_id"] = $row8["ingreso_aval_id"];
			$GLOBALS["x_ingresos_mensuales"] = $row8["ingresos_negocio"];
			$GLOBALS["x_ingresos_familiar_1_aval"] = $row8["ingresos_familiar_1"];
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = $row8["parentesco_tipo_id"];
			$GLOBALS["x_ingresos_familiar_2_aval"] = $row8["ingresos_familiar_2"];
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = $row8["parentesco_tipo_id2"];
			$GLOBALS["x_otros_ingresos_aval"] = $row8["otros_ingresos"];
			$GLOBALS["x_origen_ingresos_aval"] = $row8["origen_ingresos"];
			$GLOBALS["x_origen_ingresos_aval2"] = $row8["origen_ingresos_fam_1"];
			$GLOBALS["x_origen_ingresos_aval3"] = $row8["origen_ingresos_fam_2"];

			//la tabla gasto aval no se usa en este tipo de solicitud
			$sSql = "select * from gasto_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row12 = phpmkr_fetch_array($rs12);
			$GLOBALS["x_gasto_aval_id"] = $row12["gasto_aval_id"];
			$GLOBALS["x_gastos_prov1_aval"] = $row12["gastos_prov1"];
			$GLOBALS["x_gastos_prov2_aval"] = $row12["gastos_prov2"];
			$GLOBALS["x_gastos_prov3_aval"] = $row12["gastos_prov3"];
			$GLOBALS["x_otro_prov_aval"] = $row12["otro_prov"];
			$GLOBALS["x_gastos_empleados_aval"] = $row12["gastos_empleados"];
			$GLOBALS["x_gastos_renta_negocio_aval"] = $row12["gastos_renta_negocio"];
			$GLOBALS["x_gastos_renta_casa2"] = $row12["gastos_renta_casa"];
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = $row12["gastos_credito_hipotecario"];
			$GLOBALS["x_gastos_otros_aval"] = $row12["gastos_otros"];


			if(!empty($GLOBALS["x_propietario2"])){
				if(!empty($GLOBALS["x_gastos_renta_casa2"])){
					$GLOBALS["x_propietario_renta2"] = $GLOBALS["x_propietario2"];
					$GLOBALS["x_propietario2"] = "";
				}else{
					if(!empty($GLOBALS["x_gastos_credito_hipotecario_aval"])){
						$GLOBALS["x_propietario_ch2"] = $GLOBALS["x_propietario2"];
						$GLOBALS["x_propietario2"] = "";
					}
				}
			}


		}else{

			$GLOBALS["x_ingreso_aval_id"] = "";
			$GLOBALS["x_ingresos_mensuales"] = "";
			$GLOBALS["x_ingresos_familiar_1_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = "";
			$GLOBALS["x_ingresos_familiar_2_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = "";
			$GLOBALS["x_otros_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval2"] = "";
			$GLOBALS["x_origen_ingresos_aval3"] = "";

			$GLOBALS["x_gasto_aval_id"] = "";
			$GLOBALS["x_gastos_prov1_aval"] = "";
			$GLOBALS["x_gastos_prov2_aval"] = "";
			$GLOBALS["x_gastos_prov3_aval"] = "";
			$GLOBALS["x_otro_prov_aval"] = "";
			$GLOBALS["x_gastos_empleados_aval"] = "";
			$GLOBALS["x_gastos_renta_negocio_aval"] = "";
			$GLOBALS["x_gastos_renta_casa2"] = "";
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = "";
			$GLOBALS["x_gastos_otros_aval"] = "";



			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_propietario2"] = "";
			$GLOBALS["x_entidad_id3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";

			$GLOBALS["x_direccion_id4"] = "";
			$GLOBALS["x_calle3_neg"] = "";
			$GLOBALS["x_colonia3_neg"] = "";
			$GLOBALS["x_delegacion_id3_neg"] = "";
			$GLOBALS["x_propietario2_neg"] = "";
			$GLOBALS["x_entidad_id3_neg"] = "";
			$GLOBALS["x_codigo_postal3_neg"] = "";
			$GLOBALS["x_ubicacion3_neg"] = "";
			$GLOBALS["x_antiguedad3_neg"] = "";
			$GLOBALS["x_vivienda_tipo_id2_neg"] = "";
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = "";
			$GLOBALS["x_telefono3_neg"] = "";
			$GLOBALS["x_telefono_secundario3_neg"] = "";

		}

		//la tabla garantia no se usa ene sta solicitud,,. no hay campo garantia solo existe  solicitud dde compra
		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_id"] = $row7["garantia_id"];
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];
		$GLOBALS["x_tipo_garantia"] = $row7["tipo_garantia"];
		$GLOBALS["x_modelo_garantia"] = $row7["modelo"];
		$GLOBALS["x_garantia_valor_factura"] = $row7["valor_factura"];


		//seleccion de gastos

		$sSQL = "SELECT * FROM gasto WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
		$rsg = phpmkr_query($sSQL,$conn) or die ("Error en gasto".phpmkr_error()."sql".$sSQL);
		$rowg = phpmkr_fetch_array($rsg);
		$GLOBALS["x_gasto_id"] = $rowg["gasto_id"];
		$GLOBALS["x_renta_mensual"]= $rowg["gastos_renta_negocio"]; //negocio
		$GLOBALS["x_renta_mensula_domicilio"]= $rowg["gastos_renta_casa"]; // casa



		//seleccion de formato PYME

		$sSql = "SELECT * FROM formatoindividual WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."" ;


		//$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);

		$GLOBALS["x_otro_telefono_domicilio_2"] = $row8["otro_telefono_domicilio_2"];
		$GLOBALS["x_giro_negocio"] = $row8["giro_negocio"];
		//$GLOBALS["x_propiedad_hipot"]= $row8["prop_hipotec"];
		//$GLOBALS["x_solicitud_compra"] = $row8["solicitud_compra"];
		$GLOBALS["x_ing_fam_negocio"] = $row8["ing_fam_negocio"];
		$GLOBALS["x_ing_fam_otro_th"] = $row8["ing_fam_otro_th"];//este no estaba
		$GLOBALS["x_ing_fam_1"] = $row8["ing_fam_1"];
		$GLOBALS["x_ing_fam_2"] = $row8["ing_fam_2"];
		$GLOBALS["x_ing_fam_deuda_1"] = $row8["ing_fam_deuda_1"];
		$GLOBALS["x_ing_fam_deuda_2"] = $row8["ing_fam_deuda_2"];
		$GLOBALS["x_ing_fam_total"] = $row8["ing_fam_total"];
		$GLOBALS["x_ing_fam_cuales_1"] = $row8["ing_fam_cuales_1"];
		$GLOBALS["x_ing_fam_cuales_2"] = $row8["ing_fam_cuales_2"];
		$GLOBALS["x_ing_fam_cuales_3"] = $row8["ing_fam_cuales_3"];
		$GLOBALS["x_ing_fam_cuales_4"] = $row8["ing_fam_cuales_4"];
		$GLOBALS["x_ing_fam_cuales_5"] = $row8["ing_fam_cuales_5"];
		$GLOBALS["x_flujos_neg_ventas"] = $row8["flujos_neg_ventas"];
		$GLOBALS["x_flujos_neg_proveedor_1"] = $row8["flujos_neg_proveedor_1"];
		$GLOBALS["x_flujos_neg_proveedor_2"] = $row8["flujos_neg_proveedor_2"];
		$GLOBALS["x_flujos_neg_proveedor_3"] = $row8["flujos_neg_proveedor_3"];
		$GLOBALS["x_flujos_neg_proveedor_4"] = $row8["flujos_neg_proveedor_4"];
		$GLOBALS["x_flujos_neg_gasto_1"] = $row8["flujos_neg_gasto_1"];
		$GLOBALS["x_flujos_neg_gasto_2"] = $row8["flujos_neg_gasto_2"];
		$GLOBALS["x_flujos_neg_gasto_3"] = $row8["flujos_neg_gasto_3"];
		$GLOBALS["x_flujos_neg_cual_1"] = $row8["flujos_neg_cual_1"];
		$GLOBALS["x_flujos_neg_cual_2"] = $row8["flujos_neg_cual_2"];
		$GLOBALS["x_flujos_neg_cual_3"] = $row8["flujos_neg_cual_3"];
		$GLOBALS["x_flujos_neg_cual_4"] = $row8["flujos_neg_cual_4"];
		$GLOBALS["x_flujos_neg_cual_5"] = $row8["flujos_neg_cual_5"];
		$GLOBALS["x_flujos_neg_cual_6"] = $row8["flujos_neg_cual_6"];
		$GLOBALS["x_flujos_neg_cual_7"] = $row8["flujos_neg_cual_7"];
		$GLOBALS["x_ingreso_negocio"] = $row8["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row8["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row8["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row8["inv_neg_fija_conc_3"];
		$GLOBALS["x_inv_neg_fija_conc_4"] = $row8["inv_neg_fija_conc_4"];
		$GLOBALS["x_inv_neg_fija_valor_1"] = $row8["inv_neg_fija_valor_1"];
		$GLOBALS["x_inv_neg_fija_valor_2"] = $row8["inv_neg_fija_valor_2"];
		$GLOBALS["x_inv_neg_fija_valor_3"] = $row8["inv_neg_fija_valor_3"];
		$GLOBALS["x_inv_neg_fija_valor_4"] = $row8["inv_neg_fija_valor_4"];
		$GLOBALS["x_inv_neg_total_fija"] = $row8["inv_neg_total_fija"];
		$GLOBALS["x_inv_neg_var_conc_1"] = $row8["inv_neg_var_conc_1"];
		$GLOBALS["x_inv_neg_var_conc_2"] = $row8["inv_neg_var_conc_2"];
		$GLOBALS["x_inv_neg_var_conc_3"] = $row8["inv_neg_var_conc_3"];
		$GLOBALS["x_inv_neg_var_conc_4"] = $row8["inv_neg_var_conc_4"];
		$GLOBALS["x_inv_neg_var_valor_1"] = $row8["inv_neg_var_valor_1"];
		$GLOBALS["x_inv_neg_var_valor_2"] = $row8["inv_neg_var_valor_2"];
		$GLOBALS["x_inv_neg_var_valor_3"] = $row8["inv_neg_var_valor_3"];
		$GLOBALS["x_inv_neg_var_valor_4"] = $row8["inv_neg_var_valor_4"];
		$GLOBALS["x_inv_neg_total_var"] = $row8["inv_neg_total_var"];
		$GLOBALS["x_inv_neg_activos_totales"] = $row8["inv_neg_activos_totales"];



		//gasto de la renta de la casa verificar lso datos............  estos datos no se gauardaorn cuando se levanto la solicitud
		$sSql = "select * from gasto where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row12 = phpmkr_fetch_array($rs12);
		$GLOBALS["x_gasto_id"] = $row12["gasto_id"];
		$GLOBALS["x_gastos_prov1"] = $row12["gastos_prov1"];
		$GLOBALS["x_gastos_prov2"] = $row12["gastos_prov2"];
		$GLOBALS["x_gastos_prov3"] = $row12["gastos_prov3"];
		$GLOBALS["x_otro_prov"] = $row12["otro_prov"];
		$GLOBALS["x_gastos_empleados"] = $row12["gastos_empleados"];
		$GLOBALS["x_renta_mensual"] = $row12["gastos_renta_negocio"]; //RENTA DEL NEGOCIO
		$GLOBALS["x_renta_mensula_domicilio"] = $row12["gastos_renta_casa"]; //RENTA DEL DOMICILIO
		/*$GLOBALS["x_gastos_credito_hipotecario"] = $row12["gastos_credito_hipotecario"];
		$GLOBALS["x_gastos_otros"] = $row12["gastos_otros"];	*/

		if(!empty($GLOBALS["x_propietario"])){
			if(!empty($GLOBALS["x_gastos_renta_casa"])){
				$GLOBALS["x_propietario_renta"] = $GLOBALS["x_propietario"];
				$GLOBALS["x_propietario"] = "";
			}else{
				if(!empty($GLOBALS["x_gastos_credito_hipotecario"])){
					$GLOBALS["x_propietario_ch"] = $GLOBALS["x_propietario"];
					$GLOBALS["x_propietario"] = "";
				}
			}
		}





		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";


		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_referencia_com_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_tel_referencia_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}


		$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = ".$GLOBALS["x_cliente_id"]."  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;

		}





		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = ".$GLOBALS["x_cliente_id"]."  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;

		}




//CREDITO
		if ($GLOBALS["x_solicitud_status_id"] == 6){
			$sSql = "select credito_id from credito where solicitud_id = ".$GLOBALS["x_solicitud_id"];
			$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row10 = phpmkr_fetch_array($rs10);
			$GLOBALS["x_credito_id"] = $row10["credito_id"];
		}else{
			$x_credito_id = "";
		}



//GOOGLEMAPS
		$sSql = "select  * from  google_maps  credito where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$x_latlong = $row8["latlong"];

		$x_coordenadas = explode(",", $x_latlong);
		$GLOBALS["x_google_maps_id"] = $row8["google_maps_id"];
		//echo"google_id".$GLOBALS["x_google_maps_id"];
		//die(var_dump($x_latlong));
		if ($x_latlong != null) {
			$GLOBALS["x_latitud"] = trim($x_coordenadas[0], "(");
			$GLOBALS["x_longitud"] = trim($x_coordenadas[1],")") ;
		} else {
			$GLOBALS["x_latitud"] = 0;
			$GLOBALS["x_longitud"] = 0;
		}

		//$GLOBALS["x_latlong"] = $row8["latlong"];


		//GOOGLEMAPS negocio
		$sSql = "select  * from  google_maps_neg  credito where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row9 = phpmkr_fetch_array($rs9);
		$x_latlong2 = $row9["latlong"];

		$x_coordenadas2 = explode(",", $x_latlong2);
		$GLOBALS["x_google_maps_neg_id"] = $row9["google_maps_neg_id"];
		//echo"google_id".$GLOBALS["x_google_maps_id"];
		if ($x_latlong2 != null) {
			$GLOBALS["x_latitud2"] = trim($x_coordenadas2[0], "(");
			$GLOBALS["x_longitud2"] = trim($x_coordenadas2[1],")") ;
		} else {
			$GLOBALS["x_latitud2"] = 0;
			$GLOBALS["x_longitud2"] = 0;
		}

		//$GLOBALS["x_latlong"] = $row8["latlong"];

if($x_coordenadas2 == $x_coordenadas){
	$GLOBALS["x_checked"]= 'checked="checked"';
	$GLOBALS["x_hidden_mapa_negocio"] = 0;
	// el mapa es el mismo

	}

	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);
	phpmkr_free_result($rs3);
	phpmkr_free_result($rs4);
	phpmkr_free_result($rs5);
	if (isset($rs6)) {
		phpmkr_free_result($rs6);
	}
	if (isset($rs6_2)) {
		phpmkr_free_result($rs6_2);
	}
	if (isset($rs7)) {
		phpmkr_free_result($rs7);
	}
	if (isset($rs8)) {
		phpmkr_free_result($rs8);
	}
	if (isset($rs9)) {
		phpmkr_free_result($rs9);
	}
	if (isset($rs10)) {
		phpmkr_free_result($rs10);
	}
	if (isset($rs11)) {
		phpmkr_free_result($rs11);
	}
	if (isset($rowg)) {
		phpmkr_free_result($rowg);
	}

	return $bLoadData;
}
?>

<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables


function EditData($conn){
	mysql_query ("SET NAMES 'utf8'");
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

	global $x_solicitud_id;
	$x_readonly = $GLOBALS["x_readonly"];

	$x_today = date("Y-m-d");
	$x_time = date("H:i:s", time()+(60*60));
	$sSql = "SELECT * FROM `solicitud`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
	$sWhere .= "(`solicitud_id` = " . addslashes($sTmp) . ")";
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
		$bEditData = false; // Update Failed
	}else{

	//seleccionamos las fechas regsitradas
		$sqlSOlFe = "SELECT * FROM solicitud WHERE solicitud_id = $x_solicitud_id ";
		$rsSOLFe = phpmkr_query($sqlSOlFe,$conn)or die ("Error la seleccionar los datos".phpmkr_error()."sql:".$sqlSOlFe);
		$rowSolFE = phpmkr_fetch_array($rsSOLFe);
		$x_f_fecha_investigacion = $rowSolFE["fecha_investigacion"];
		$x_f_fecha_otorga_credito = $rowSolFE["fecha_otorga_credito"];
		$x_f_fecha_aprobada = $rowSolFE["fecha_aprobada"];
		$x_f_fecha_supervision = $rowSolFE["fecha_supervision"];
		$x_f_fecha_cancelada = $rowSolFE["fecha_cancelada"];
		$x_f_fecha_rechazada = $rowSolFE["fecha_rechazada"];
		$x_f_fecha_preanalisis = $rowSolFE["fecha_preanalisis"];
		$x_f_fecha_en_otorgamiento = $rowSolFE["fecha_en_otorgamiento"];


	// se cual se el estatus se inserta en la nueva tabla de solicitud_fecha_status

	$sqlInsertSolFechaStatus = "INSERT INTO `solicitud_fecha_status` (`solicitud_fecha_status_id`, `solicitud_id`, `status_id`, `fecha`, `usuario_id`)";
	$sqlInsertSolFechaStatus .= " VALUES (NULL, $x_solicitud_id, ".$GLOBALS["x_solicitud_status_id"].", '".ConvertDateToMysqlFormat($x_today)."',". $_SESSION["php_project_esf_status_UserID"].") ";
	$x_result = phpmkr_query($sqlInsertSolFechaStatus, $conn)or die ("Error al inserta en sol_fech_status".phpmkr_error()."sql:".$sqlInsertSolFechaStatus);
	echo "<br>".$sqlInsertSolFechaStatus."<br>";

	if(!$x_result){
			echo phpmkr_error() . '<br> errorSQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			die();
			exit();
		}

			echo "Status :".$GLOBALS["x_solicitud_status_id"]."<br>";
			if($GLOBALS["x_solicitud_status_id"] == 11){
				///enviamos el mensaje
				if (!empty($GLOBALS["x_cliente_id"])){
						 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
						 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
						 $rowCelular = phpmkr_fetch_array($rsCelular);
						 $x_no_celular = $rowCelular["numero"];
						 $x_compania = $rowCelular["compania_id"];
						 //$x_no_celular = "55";

	//seleccionamos el email del cliente
	$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$GLOBALS["x_cliente_id"]." ";
	$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
	$rowmail =  phpmkr_fetch_array($rsMail);
	$email =  $rowmail["email"];
	echo " <br> ".$sqlMail;
	echo "eamail ".$email ."<br>";
	//$email  = "zortiz@financieracrea.com";
		##################################################################################################################
		#####################################################  SMS  ######################################################
		##################################################################################################################
		$x_mensaje = "";
		$tiposms = "";

//$x_mensaje = "FINANCIERA CREA: SU SOLICITUD HA SIDO RECIBIDA POR EL AREA DE ANALISIS DE MANERA EXITOSA TEL.(55) 51350259";
$x_mensaje = "FINANCIERA CREA: SU SOLICITUD HA SIDO RECIBIDA POR EL AREA DE ANALISIS TEL.(55) 51350259";


						$tiposms = "17";

if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";
						// Mail it
						mail($para, $titulo, $x_mensaje, $cabeceras);
						$chip = "";
						#CAMBIAR A CHIP NO 1
						$x_cliente_idl = $GLOBALS["x_cliente_id"];
						$x_credito_id = 0;
						$hoy = date("Y-m-d");
						$x_fecha = ConvertDateToMysqlFormat($hoy);



 $sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '17','" .$x_mensaje."', '0', '".$x_no_celular."', '".$x_fecha."', '1', '1',$x_cliente_idl)";
	 $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '17','" .$mensajeMail."', '0', '".$email."', '".$x_fecha."', '1', '1',$x_cliente_idl)";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}

}




				}
			}

			if((intval($GLOBALS["x_solicitud_status_id"]) == 11)){
				// se guarda la fecha de pre analisis
				// Field fecha_investigacion// TIENE EL VALOR DE HOY

				echo "entra a preanlisis<br>";

			if (empty($x_f_fecha_preanalisis) || ($x_f_fecha_preanalisis < "2013-01-01")){

			$theValue = ($x_today != "") ? " '" . ConvertDateToMysqlFormat($x_today) . "'" : "NULL";
			$fieldList["`fecha_preanalisis`"] = $theValue;

			// Field hora_registro
			$theValue = ($x_time != "") ? " '" . $x_time . "'" : "NULL";
			$fieldList["`hora_preanalisis`"] = $theValue;

			// update
		$sSql = "UPDATE `solicitud` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		$x_result = phpmkr_query($sSql,$conn);
		if(!$x_result){
			echo phpmkr_error() . '<br> errorSQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			die();
			exit();
		}
			}// valida fecha
				echo "preanalisis ".$sSql."<br>";
				}




		if((intval($GLOBALS["x_solicitud_status_id"]) == 9)){

				// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_sup_th"]) :$GLOBALS["x_resultado_visita_sup_th"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

	if($GLOBALS["x_visita_id_3"] != 0 ){

			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_3"];

			$x_result = phpmkr_query($sSql,$conn) or die ("error");





}else {

				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 2;
				$fieldList["`tipo_domicilio_id`"] = 1;
				// insert into database
				$sSql = "INSERT INTO `visita` (";
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
			$fieldList = NULL;
			}

		if($GLOBALS["x_solicitud_status_id"] == 2)	{
			// si esta en aprobada solo se cambia el staus y la fecha en que se mando a enotorgamiento
			$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
			$fieldList["`solicitud_status_id`"] = $theValue;

			if (empty($x_f_fecha_investigacion) || ($x_f_fecha_investigacion < "2013-01-01")){
			echo "entra ".$x_f_fecha_investigacion."---";
			// Field fecha_investigacion// TIENE EL VALOR DE HOY
			$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
			$fieldList["`fecha_investigacion`"] = $theValue;

			// gauradmos los comentarios de supervision

			// Field hora_registro
			$theValue = ($x_time != "") ? " '" . $x_time . "'" : "NULL";
			$fieldList["`hora_comite`"] = $theValue;
			}

			// update
				$sSql = "UPDATE `solicitud` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE " . $sWhere;

				$x_result = phpmkr_query($sSql,$conn) or die ("");


				$fieldList = NULL;
				 echo "sql _ prenalisis ".$sSqlWrk."<br>";
				// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_sup_th"]) :$GLOBALS["x_resultado_visita_sup_th"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

if($GLOBALS["x_visita_id_3"] != 0 ){

			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_3"];

			$x_result = phpmkr_query($sSql,$conn) or die ("error");




}else {

				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 2;
				$fieldList["`tipo_domicilio_id`"] = 1;
				// insert into database
				$sSql = "INSERT INTO `visita` (";
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
				echo "sql visita ".$sSql."<br>";

			$fieldList = NULL;

			}	//si se cambin a en otorgamiento

		if($GLOBALS["x_solicitud_status_id"] == 10)	{
			// si esta en aprobada solo se cambia el staus y la fecha en que se mando a enotorgamiento
			$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
			$fieldList["`solicitud_status_id`"] = $theValue;
				if (empty($x_f_fecha_otorga_credito) || ($x_f_fecha_otorga_credito < "2013-01-01")){
			// Field fecha_investigacion// TIENE EL VALOR DE HOY
			$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
			$fieldList["`fecha_en_otorgamiento`"] = $theValue;


			// Field hora_registro
			$theValue = ($x_time != "") ? " '" . $x_time . "'" : "NULL";
			$fieldList["`hora_otorgamiento`"] = $theValue;

				}

			// Field fecha_investigacion// TIENE EL VALOR DE HOY
			$theValue = ($GLOBALS["x_dia_otorga_credito"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_dia_otorga_credito"]) . "'" : "NULL";
			$fieldList["`dia_otorga_credito`"] = $theValue;

			if($GLOBALS["x_realizo_supervision"] ==  1){
				$theValue = ($GLOBALS["x_dia_otorga_credito"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_dia_otorga_credito"]) . "'" : "NULL";
			$fieldList["`fecha_visita_supervision`"] = $theValue;

				}
			// se agraga el numero de tajeta referenciada y el numero de que con que otorgara el credito.

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tarjeta_referenciada"]) :$GLOBALS["x_tarjeta_referenciada"];
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`tarjeta_referenciada`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_numero_cheque"]) :$GLOBALS["x_numero_cheque"];
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`numero_cheque`"] = $theValue;


			// update
				$sSql = "UPDATE `solicitud` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE " . $sWhere;

				$x_result = phpmkr_query($sSql,$conn);
				if(!$x_result){
					echo phpmkr_error() . '<br> errorSQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					die();
					exit();
				}

			}	//si se cambin a en otorgamiento


		if($GLOBALS["x_solicitud_status_id"] == 12 || $GLOBALS["x_solicitud_status_id"] == 5 || $GLOBALS["x_solicitud_status_id"] == 4 )	{


			// se agraga el numero de tajeta referenciada y el numero de que con que otorgara el credito.

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite_interno"]) : $GLOBALS["x_comentario_comite_interno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite_interno`"] = $theValue;

		$theValue = ($GLOBALS["x_comite_credito_explicacion_id"] != "") ? intval($GLOBALS["x_comite_credito_explicacion_id"]) : "0";
		$fieldList["`comite_credito_explicacion_id`"] = $theValue;



				// update
				$sSql = "UPDATE `solicitud` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE " . $sWhere;

				$x_result = phpmkr_query($sSql,$conn);
				if(!$x_result){
					echo phpmkr_error() . '<br> errorSQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					die();
					exit();
				}


			}	//si se cambin a en otorgamiento
	$fieldList = NULL;
		$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "0";
		$fieldList["`credito_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
		$fieldList["`solicitud_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`folio`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		#$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "0";
		$fieldList["`promotor_id`"] = $theValue;

			$theValue = ($GLOBALS["x_zona_id"] != "") ? intval($GLOBALS["x_zona_id"]) : "0";
		$fieldList["`zona_id`"] = $theValue;
		// se agrega el campo de vendedor
		$theValue = ($GLOBALS["x_vendedor_id"] != "") ? intval($GLOBALS["x_vendedor_id"]) : "0";
		$fieldList["`vendedor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
		$fieldList["`importe_solicitado`"] = $theValue;
		$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "0";
		$fieldList["`plazo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
		$fieldList["`forma_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;


		$theValue = ($GLOBALS["x_actividad_id"] != "") ? intval($GLOBALS["x_actividad_id"]) : "0";
		$fieldList["`actividad_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_actividad_desc"]) : $GLOBALS["x_actividad_desc"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`actividad_desc`"] = $theValue;

		// actualizamos el campo monto maximo aprobado
		$theValue = ($GLOBALS["x_monto_maximo_aprobado"] != "") ? " '" . doubleval($GLOBALS["x_monto_maximo_aprobado"]) . "'" : "NULL";
		$fieldList["`monto_maximo_aprobado`"] = $theValue;



		if((intval($GLOBALS["x_solicitud_status_id"]) == 9)){
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuerpo_mail"]) : $GLOBALS["x_cuerpo_mail"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`cuerpo_mail`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_destinatario_mail"]) : $GLOBALS["x_destinatario_mail"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`destinatario_mail`"] = $theValue;

		$sSqlWrkG = "SELECT galeria_fotografica_id FROM galeria_fotografica Where ".$sWhere." and tipo_galeria_id= 1";
	$rswrkG = phpmkr_query($sSqlWrkG,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkG);
	$rowwrkG = phpmkr_fetch_array($rswrkG);
	$x_galeria_fotografica_id = $rowwrkG["galeria_fotografica_id"];

	@phpmkr_free_result($rswrkG);
	if($x_galeria_fotografica_id > 0 ){
		$x_liga_galeria_1 = "<a href='http://financieracrea.com/esf/php_galeriaedit.php?x_galeria_fotografica_id=$x_galeria_fotografica_id' target='_blank'>Ir a galeria cliente</a>";
	}else{
		$x_liga_galeria_1 = "";

	}

	$sSqlWrkG = "SELECT galeria_fotografica_id FROM galeria_fotografica Where ".$sWhere." and tipo_galeria_id= 2";
	$rswrkG = phpmkr_query($sSqlWrkG,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkG);
	$rowwrkG = phpmkr_fetch_array($rswrkG);
	$x_galeria_fotografica_id_aval= $rowwrkG["galeria_fotografica_id"];

	@phpmkr_free_result($rswrkG);
	if($x_galeria_fotografica_id_aval > 0 ){
		$x_liga_galeria_2 = "<a href='http://financieracrea.com/esf/php_galeriaedit.php?x_galeria_fotografica_id=$x_galeria_fotografica_id' target='_blank'>Ir a galeria aval</a>";
	}else{
		$x_liga_galeria_2 = "";

	}



	// Varios destinatarios
		$para  = $GLOBALS["x_destinatario_mail"]; // atención a la coma
		// subject
		$titulo = " === SUPERVISION DE CLIENTEL  ".$GLOBALS["x_nombre"]." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ===";
		$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= 'From: analisis@financieracrea.com';
		$x_mensaje = $GLOBALS["x_cuerpo_mail"];


		$x_mensaje .= " <BR><BR> http://financieracrea.com/esf/modulos/php_solicitudeditIndividual.php?solicitud_id=".$x_solicitud_id."<BR><BR>";
		if(strlen($x_liga_galeria_1)>5){
		$x_mensaje .= " <br><br>Galeria Cliente<br><br> ";
		$x_mensaje .= "http://financieracrea.com/esf/php_galeriaedit.php?x_galeria_fotografica_id=".$x_galeria_fotografica_id."<br><br>";
		}
		if(strlen($x_liga_galeria_2)>5){
		$x_mensaje .= " <br><br>Galeria Aval<br><br> ";
		$x_mensaje .= "http://financieracrea.com/esf/php_galeriaedit.php?x_galeria_fotografica_id=".$x_galeria_fotografica_id_aval."<br><br>";
		}
		//echo "mensaje ";

		//echo $x_mensaje."<br>";
		// Mail it
		//die();
		$para2= "analisis@financieracrea.com";
		mail($para, $titulo, $x_mensaje, $cabeceras);
		mail($para2, $titulo, $x_mensaje, $cabeceras);

			}

		if((intval($GLOBALS["x_solicitud_status_id"]) == 9)){
			if (empty($x_f_fecha_supervision) || ($x_f_fecha_supervision < "2013-01-01")){

		// Field fecha_investigacion
		$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
		$fieldList["`fecha_supervision`"] = $theValue;
		// Field hora_registro
		$theValue = ($x_time != "") ? " '" . $x_time . "'" : "NULL";
		$fieldList["`hora_supervision`"] = $theValue;
		}

		//


			}

			if((intval($GLOBALS["x_solicitud_status_id"]) == 2)){
				// actualizamos el campo de monto solicitado
		$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
		$fieldList["`monto_solicitado`"] = $theValue;
				}

			//hacemos el envio del mesaje
		//1.- cuando la solicitud es rechazada // id 4
		//2.- cuando la solictud es autorizada // id 3

		if((intval($GLOBALS["x_solicitud_status_id"]) == 3) || (intval($GLOBALS["x_solicitud_status_id"]) == 4) || (intval($GLOBALS["x_solicitud_status_id"]) == 12)){

			if (!empty($GLOBALS["x_cliente_id"])){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 //$x_no_celular = "5540663927";

		 //seleccionamos el email del cliente
	$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$GLOBALS["x_cliente_id"]." ";
	$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
	$rowmail =  phpmkr_fetch_array($rsMail);
	$email =  $rowmail["email"];
	echo " <br> ".$sqlMail;
	echo "eamail ".$email ."<br>";

		 ##################################################################################################################
		#####################################################  SMS  ######################################################
		##################################################################################################################
		$x_mensaje = "";
		$tiposms = "";
		if ((intval($GLOBALS["x_solicitud_status_id"]) == 3) || (intval($GLOBALS["x_solicitud_status_id"]) == 12)){
						//$x_mensaje = "FINANCIERA CREA: NOS ES GRATO INFORMARLE QUE SU SOLICITUD DE CREDITO HA SIDO AUTORIZADA.";
						//$x_mensaje .= " COMUNIQUESE PARA OBTENER MAS DETALLES AL: DF 51350259 O AL 018008376133.";
						$x_mensaje = "FINANCIERA CREA: SU CREDITO HA SIDO APROBADO. NOS COMUNICAREMOS CON USTED PARA ";
						$x_mensaje .= "AGENDAR CITA DE ENTREGA. TEL: DF 51350259 / 018008376133";
						$tiposms = "9";



		if (empty($x_f_fecha_aprobada) || ($x_f_fecha_aprobada < "2013-01-01")){
		//guardamos la fecha de aprobada
		// Field fecha_investigacion// TIENEN EL VALOR DE HOY
		$theValue = ($x_today != "") ? " '" . ConvertDateToMysqlFormat($x_today) . "'" : "NULL";
		$fieldList["`fecha_aprobada`"] = $theValue;


		// Field hora_registro
		$theValue = ($x_time != "") ? " '" . $x_time . "'" : "NULL";
		$fieldList["`hora_aprobada`"] = $theValue;
		}
		$theValue = ($GLOBALS["x_realizo_supervision"] != "") ? intval($GLOBALS["x_realizo_supervision"]) : "0";
		$fieldList["`realizo_supervison`"] = $theValue; //kuka




			}else if((intval($GLOBALS["x_solicitud_status_id"]) == 4)){
						$x_mensaje = "FINANCIERA CREA: LAMENTAMOS INFORMARLE QUE SU SOLICITUD DE CREDITO HA SIDO RECHAZADA.";
						$x_mensaje .= " TELEFONO DE ATENCION A CLIENTES EN DF 51350259 O AL 018008376133.";
						$tiposms = "10";

		if (empty($x_f_fecha_rechazada) || ($x_f_fecha_rechazada < "2013-01-01")){
		// Field fecha_investigacion// TIENE EL VALOR DE HOY
		$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
		$fieldList["`fecha_rechazada`"] = $theValue;
		}
				}
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje

						//echo "MENSAJE :". $x_mensaje."<BR>";
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";

						// Mail it
						mail($para, $titulo, $x_mensaje, $cabeceras);
						$chip = "";
						#CAMBIAR A CHIP NO 1
						$x_cliente_idl = $GLOBALS["x_cliente_id"];
						$x_credito_id = 0;
						$hoy = date("Y-m-d");
						$x_fecha = ConvertDateToMysqlFormat($hoy);


						 $sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, $tiposms,'" .$x_mensaje."', '0', '".$x_no_celular."', '".$x_fecha."', '1', '1',$x_cliente_idl )";
	 						$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);

					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						//    echo "Esta dirección de correo ($email_a) es válida.";
						mail($email, $titulo2, $mensajeMail, $cabeceras2);
						$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`,`cliente_id`) VALUES (NULL, $tiposms,'" .$mensajeMail."', '0', '".$email."', '".$x_fecha."', '1', '1', $x_cliente_idl)";
						$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}



			}
		}

			}// en vio de mensaje

		if ($GLOBALS["x_calculo_capacidad_pago"] == 1){
			$theValue = ($x_today != "") ? " '" . ConvertDateToMysqlFormat($x_today) . "'" : "NULL";
			$fieldList["`fecha_calcula_capacidad_pago`"] = $theValue;
			}
		// update
		$sSql = "UPDATE `solicitud` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;

		if(empty($x_readonly)){

		$x_result = phpmkr_query($sSql,$conn);
		if(!$x_result){
			echo phpmkr_error() . '<br> errorSQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			die();
			exit();
		}



		}


		//CLIENTE
		$fieldList = NULL;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;

		$theValue = ($GLOBALS["x_cliente_tipo_id"] != "") ? intval($GLOBALS["x_cliente_tipo_id"]) : "0";
		$fieldList["`cliente_tipo_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;

		$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
		$fieldList["`fecha_nac`"] = $theValue;

		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "0";
		$fieldList["`sexo`"] = $theValue;

		$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;

		$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
		$fieldList["`numero_hijos_dep`"] = $theValue;


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_esposa"]) : $GLOBALS["x_esposa"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;


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
	$theValue = ($GLOBALS["x_ppe"] != "") ? intval($GLOBALS["x_ppe"]) : "0";
	$fieldList["`ppe`"] = $theValue;

	$theValue = ($GLOBALS["x_ingreso_semanal"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_semanal"]) . "'" : "Null";
	$fieldList["`ingreso_semanal`"] = $theValue;





		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;

		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"] ;
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn)or die("erro_".phpmkr_errro()."sql".$sSql);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			exit();
		}
		}


		//PERSONAS POLITICAMENTE EXPUESTAS
		if($GLOBALS["x_ppe_id"] == 0){
	$fieldList = NULL;
	$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];

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

	//PUESTO
	$theValue = ($GLOBALS["x_reporte_cnbv_puesto_ppe_id"] != "") ? intval($GLOBALS["x_reporte_cnbv_puesto_ppe_id"]) : "0";
	$fieldList["`reporte_cnbv_puesto_ppe_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `ppe` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	if(empty($x_readonly)){
	$x_result = phpmkr_query($sSql, $conn);

	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);
	 	exit();
	}
	}
		}else{

	$fieldList = NULL;


	//RELACION
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

	//PUESTO
	$theValue = ($GLOBALS["x_reporte_cnbv_puesto_ppe_id"] != "") ? intval($GLOBALS["x_reporte_cnbv_puesto_ppe_id"]) : "0";
	$fieldList["`reporte_cnbv_puesto_ppe_id`"] = $theValue;


			$sSql = "UPDATE `ppe` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE ppe_id = ".$GLOBALS["x_ppe_id"] ;
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn)or die("erro_".phpmkr_error()."sql".$sSql);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			exit();
		}
		}

			}










		if($GLOBALS["x_negocio_id"] > 0){
			echo "negocio id" . $GLOBALS["x_negocio_id"]."-";

			//se modifica el registro
			$fieldList = NULL;

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


					// update
					$sSql = "UPDATE `negocio` SET ";
					foreach ($fieldList as $key=>$temp) {
						$sSql .= "$key = $temp, ";
					}
					if (substr($sSql, -2) == ", ") {
						$sSql = substr($sSql, 0, strlen($sSql)-2);
					}
					$sSql .= " WHERE negocio_id = ". $GLOBALS["x_negocio_id"]."";
					if(empty($x_readonly)){
					$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);

					if(!$x_result){
						echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
						phpmkr_query('rollback;', $conn);
						exit();
					}}

			}else{
				// no existia el registro asi que se inserta

				//NEGOCIO
				$fieldList = NULL;
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
					// Field antiguedad
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


				// insert into database
				$sSql = "INSERT INTO `negocio` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";

				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}
				}

				}// fin negocio










		//DIR PARTICULAR


		if($GLOBALS["x_direccion_id"] > 0 ){

			$fieldList = NULL;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;

		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;


		// Field vivienda_tipo_id
		$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
		$fieldList["`compania_celular_id`"] = $theValue;

		$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
		$fieldList["`numero_exterior`"] = $theValue;

			 // Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil_2"]) :$GLOBALS["x_telefono_movil_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil_2`"] = $theValue;

		$theValue = ($GLOBALS["x_compania_celular_id_2"] != "") ? intval($GLOBALS["x_compania_celular_id_2"]) : "0";
		$fieldList["`compania_celular_id_2`"] = $theValue;
			// Field delegacion_id
		$theValue = ($GLOBALS["x_localidad_id"] != "") ? intval($GLOBALS["x_localidad_id"]) : "0";
		$fieldList["`localidad_id`"] = $theValue;

		// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id"];
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn) or die("Error: ".phpmkr_error()."statement :".sSql);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente par: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

		}else{

			if($GLOBALS["x_calle_domicilio"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];

				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;

				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;

				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 1;

				// Field calle

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;

		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
		$fieldList["`delegacion_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;

		// Field vivienda_tipo_id
		$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
		$fieldList["`compania_celular_id`"] = $theValue;

		$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
		$fieldList["`numero_exterior`"] = $theValue;

			 // Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil_2"]) :$GLOBALS["x_telefono_movil_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil_2`"] = $theValue;

		$theValue = ($GLOBALS["x_compania_celular_id_2"] != "") ? intval($GLOBALS["x_compania_celular_id_2"]) : "0";
		$fieldList["`compania_celular_id_2`"] = $theValue;
			// Field delegacion_id
		$theValue = ($GLOBALS["x_localidad_id"] != "") ? intval($GLOBALS["x_localidad_id"]) : "0";
		$fieldList["`localidad_id`"] = $theValue;


				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn) or die("error  direccion:". phpmkr_error()."statement:".$sSql);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}
			}
		}// ELSE DIRECCION

		//DIR NEG

		if($GLOBALS["x_direccion_id2"] > 0 ){

		$fieldList = NULL;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;

		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;

			$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
			$fieldList["`delegacion_id`"] = $theValue;



		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;

		$theValue = ($GLOBALS["x_localidad_id2"] != "") ? intval($GLOBALS["x_localidad_id2"]) : "0";
		$fieldList["`localidad_id`"] = $theValue;




			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE cliente_id = ". $GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

		}else{

			if($GLOBALS["x_calle_negocio"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];

				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;

				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;

				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 2;

				//*------------------------------------------------------------------------------------------------------

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;

		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;

		$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
		$fieldList["`delegacion_id`"] = $theValue;

		$theValue = ($GLOBALS["x_localidad_id2"] != "") ? intval($GLOBALS["x_localidad_id2"]) : "0";
		$fieldList["`localidad_id`"] = $theValue;


				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn) or die("Error  insert domicilio dos:".phpmkr_error()."statement".$sSql);

				if(!$x_result){

					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}
			}
		}


		//GARANTIAS
		if($GLOBALS["x_garantia_id"] != ""){

			$fieldList = NULL;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"];
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`descripcion`"] = $theValue;
			$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
			$fieldList["`valor`"] = $theValue;

			// update
			$sSql = "UPDATE `garantia` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			if(empty($x_readonly)){
			$sSql .= " WHERE garantia_id = " . $GLOBALS["x_garantia_id"];

			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

		}else{

			if($GLOBALS["x_garantia_desc"] != ""){
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];

				// Field descripcion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"];
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`descripcion`"] = $theValue;

				// Field valor
				$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
				$fieldList["`valor`"] = $theValue;


				// insert into database
				$sSql = "INSERT INTO `garantia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}

			}//fin else
		}// fin garatias

			$fieldList = NULL;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_pro_th"]) :$GLOBALS["x_resultado_visita_pro_th"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

		// Field antiguedad
	$theValue = ($GLOBALS["x_v_p_d_th"] != "") ? intval($GLOBALS["x_v_p_d_th"]) : "0";
	$fieldList["`visita_domicilio`"] = $theValue;
		// Field antiguedad
	$theValue = ($GLOBALS["x_v_p_n_th"] != "") ? intval($GLOBALS["x_v_p_n_th"]) : "0";
	$fieldList["`visita_negocio`"] = $theValue;

	$tipo_visita_id = 1 ; #1 promotor 2 supevisor
	$tipo_domicilio_id = 1;  #1 th 2 aval



		if($GLOBALS["x_visita_id_1"] != 0 ){
			echo "entra a update";
			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_1"];
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn);

			echo "sql :".$sSql."<br>";
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

			}else {
				echo "entra a inserta";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 1;
				$fieldList["`tipo_domicilio_id`"] = 1;

				// insert into database
				$sSql = "INSERT INTO `visita` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				echo "sql :".$sSql."<br>";
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}


				}



	$fieldList = NULL;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_pro_aval"]) :$GLOBALS["x_resultado_visita_pro_aval"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

		// Field antiguedad
	$theValue = ($GLOBALS["x_v_p_d_a"] != "") ? intval($GLOBALS["x_v_p_d_a"]) : "0";
	$fieldList["`visita_domicilio`"] = $theValue;
		// Field antiguedad
	$theValue = ($GLOBALS["x_v_p_n_a"] != "") ? intval($GLOBALS["x_v_p_n_a"]) : "0";
	$fieldList["`visita_negocio`"] = $theValue;

	$tipo_visita_id = 1 ; #1 promotor 2 supevisor
	$tipo_domicilio_id = 1;  #1 th 2 aval



		if($GLOBALS["x_visita_id_2"] != 0 ){

			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_2"];
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

			}else {
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 1;
				$fieldList["`tipo_domicilio_id`"] = 2;
				// insert into database
				$sSql = "INSERT INTO `visita` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}


				}


		$fieldList = NULL;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_sup_th"]) :$GLOBALS["x_resultado_visita_sup_th"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

		// Field antiguedad
	$theValue = ($GLOBALS["x_v_s_d_th"] != "") ? intval($GLOBALS["x_v_s_d_th"]) : "0";
	$fieldList["`visita_domicilio`"] = $theValue;
		// Field antiguedad
	$theValue = ($GLOBALS["x_v_s_n_th"] != "") ? intval($GLOBALS["x_v_s_n_th"]) : "0";
	$fieldList["`visita_negocio`"] = $theValue;

	$tipo_visita_id = 1 ; #1 promotor 2 supevisor
	$tipo_domicilio_id = 1;  #1 th 2 aval



		if($GLOBALS["x_visita_id_3"] != 0 ){

			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_3"];
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

			}else {
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 2;
				$fieldList["`tipo_domicilio_id`"] = 1;
				// insert into database
				$sSql = "INSERT INTO `visita` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}


				}


		$fieldList = NULL;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_resultado_visita_sup_aval"]) :$GLOBALS["x_resultado_visita_sup_aval"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`resultado`"] = $theValue;

		// Field antiguedad
	$theValue = ($GLOBALS["x_v_s_d_a"] != "") ? intval($GLOBALS["x_v_s_d_a"]) : "0";
	$fieldList["`visita_domicilio`"] = $theValue;
		// Field antiguedad
	$theValue = ($GLOBALS["x_v_s_n_a"] != "") ? intval($GLOBALS["x_v_s_n_a"]) : "0";
	$fieldList["`visita_negocio`"] = $theValue;

	$tipo_visita_id = 1 ; #1 promotor 2 supevisor
	$tipo_domicilio_id = 1;  #1 th 2 aval



		if($GLOBALS["x_visita_id_4"] != 0 ){

			$sSql = "UPDATE `visita` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE visita_id = " . $GLOBALS["x_visita_id_4"];
			if(empty($x_readonly)){
			$x_result = phpmkr_query($sSql,$conn);

	echo "sql :".$sSql."<br>";
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				exit();
			}}

			}else {
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				$fieldList["`tipo_visita_id`"] = 2;
				$fieldList["`tipo_domicilio_id`"] = 2;
				// insert into database
				$sSql = "INSERT INTO `visita` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);

				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}


				}

		//GASTOS
	if($GLOBALS["x_solicitud_id"] > 0){
		$fieldList = NULL;

		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;


		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;

		// update
		$sSql = "UPDATE `gasto` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE gasto_id = " . $GLOBALS["x_gasto_id"];
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			exit();
		}}

	}else{
		$fieldList = NULL;
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];


		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) : "0";
		$fieldList["`gastos_renta_negocio`"] = $theValue;


		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;



		// insert into database
		$sSql = "INSERT INTO `gasto` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
	if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql, $conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			exit();
		}}
	}



		//referencias


		//REFERENCIAS

		$sSql = " delete from referencia WHERE solicitud_id = " . $GLOBALS["x_solicitud_id"];
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			exit();
		}}

		$x_counter = 1;
		while($x_counter < 6){

			$fieldList = NULL;
			// Field cliente_id
	//		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
			$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];


			if($GLOBALS["x_referencia_com_$x_counter"] != ""){

				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_$x_counter"]) : $GLOBALS["x_referencia_com_$x_counter"];
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;

				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_$x_counter"]) : $GLOBALS["x_tel_referencia_$x_counter"];
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_ref_$x_counter"] != "") ? intval($GLOBALS["x_parentesco_ref_$x_counter"]) : "NULL";
				$fieldList["`parentesco_tipo_id`"] = $theValue;

				// insert into database
				$sSql = "INSERT INTO `referencia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			if(empty($x_readonly)){
				$x_result = phpmkr_query($sSql, $conn);
							if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);
					exit();
				}}

			}


			$x_counter++;
		}





		//TELEFONOS

		$sSql = " delete from telefono WHERE cliente_id = " . $GLOBALS["x_cliente_id"];
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






			//FORMATOINDIVIDUAL

	if($GLOBALS["x_cliente_id"] > 0){
	$fieldList = NULL;
		//no esta el campo en la tabla domicilio por esa razon se inserta en la tabla adquisicionmaquinaria
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_telefono_domicilio_2"]) : $GLOBALS["x_otro_telefono_domicilio_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_telefono_domicilio_2`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_giro_negocio"]) : $GLOBALS["x_giro_negocio"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`giro_negocio`"] = $theValue;



		$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]). "'" : "NULL";
		$fieldList["`ing_fam_negocio`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]). "'" : "NULL";
		$fieldList["`ing_fam_otro_th`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]). "'" : "NULL";
		$fieldList["`ing_fam_1`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]). "'" : "NULL";
		$fieldList["`ing_fam_2`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_1`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_2`"] = $theValue;

		$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
		$fieldList["`ing_fam_total`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_1`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_2`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_3`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_4`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_5`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" .doubleval( $GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
		$fieldList["`flujos_neg_ventas`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"] ). "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_1`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_2`"] = $theValue;

		$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]). "'" : "NULL";
		$fieldList["`flujos_neg_gasto_3`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_1`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_2`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_3`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_4`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_5`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_6`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_7`"] = $theValue;

		$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]). "'" : "NULL";
		$fieldList["`ingreso_negocio`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_3`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
		$fieldList["`inv_neg_total_fija`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_1`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_2`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_3`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_4`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" .doubleval( $GLOBALS["x_inv_neg_var_valor_1"]). "'" : "NULL";
		$fieldList["`inv_neg_var_valor_1`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_2`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_3`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_4`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
		$fieldList["`inv_neg_total_var`"] = $theValue;

		$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
		$fieldList["`inv_neg_activos_totales`"] = $theValue;

		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;

		$sSql = "UPDATE `formatoindividual` SET ";
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

			$sqlBuscaMail = "SELECT COUNT(*) as mail_enviado FROM ppe_listado WHERE solicitud_id = ".$x_solicitud_id." ";
			$rsBusca = phpmkr_query($sqlBuscaMail, $conn) or die ("Error busqueda en ppe tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
			$rowMail = phpmkr_fetch_array($rsBusca);
		    $x_numero_de_emails_enviados = $rowMail["mail_enviado"];


			if($GLOBALS["x_ppe"] ==1 && $x_numero_de_emails_enviados == 0){
		##################################################################################################################
		#####################################################  MAIL PPE  ######################################################
		##################################################################################################################
		$x_mensaje = "";
		$tiposms = "";

		//$x_mensaje = "FINANCIERA CREA: SU SOLICITUD HA SIDO RECIBIDA POR EL AREA DE ANALISIS DE MANERA EXITOSA TEL.(55) 51350259";
		$x_mensaje = "SE REGISTRO UNA SOLICITUD DONDE SE INDICO UNA PPE CON EL CLIENTE ".$GLOBALS["x_nombre"] ." ". $GLOBALS["x_apellido_paterno"]. " ".$GLOBALS["x_apellido_materno"] ."<BR> EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE AL LISTADO DE PPE PARA COMPLETAR EL REGISTRO";





						$para  = 'oficialdecumplimiento@financieracrea.com'; // atención a la coma
						// subject
						$titulo = "== PPE REGISTRADA ==";
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';

						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";
						// Mail it
						mail($para, $titulo, $x_mensaje, $cabeceras);

						$x_cliente_idl = $GLOBALS["x_cliente_id"];

						$hoy = date("Y-m-d");
						$x_fecha = ConvertDateToMysqlFormat($hoy);




	$sqlInsertsms =  "INSERT INTO `ppe_listado` (`ppe_listado_id`, `cliente_id`, `solicitud_id`, `fecha`, `status`, `mail`, `reporte_cnbv_puesto_ppe_id`, `id_usuario_registro`)";
	$sqlInsertsms .=  " VALUES (NULL, '".$x_cliente_idl."', '".$x_solicitud_id."', '".$x_fecha."', '1', '1', '".$GLOBALS["x_reporte_cnbv_puesto_ppe_id"]."', ".$_SESSION["php_project_esf_status_UserID"].")";

	 $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en ppe tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}// si es PPE inserta

	$x_cliente_idl = $GLOBALS["x_cliente_id"];
	################################# buscamos en la lista OFAC #######################################
	$x_nombre_busqueda_OFAC = $GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"].", ".$GLOBALS["x_nombre"]; // formato de la lista RUELAS AVILA, Jose Luis
	$x_nombre_busqueda_OFAC = trim($x_nombre_busqueda_OFAC);
	$sSqlOFAC = "SELECT * FROM `csv_sdn` WHERE `sdn_name` LIKE  _utf8'%".$x_nombre_busqueda_OFAC."%' COLLATE utf8_general_ci";
	$rsOFAC = phpmkr_query($sSqlOFAC,$conn) or die("Error en busqueda en la lista OFAC". phpmkr_error());

	while($rowOFAC = phpmkr_fetch_array($rsOFAC)){
		$x_sdn_name =$rowOFAC["sdn_name"];
		$x_ent_num =$rowOFAC["ent_num"];
		$x_existe=1;
		}
	################################# buscamos en la lista negra CNBV #######################################
	$x_nombre_busqueda_lista_negra = $GLOBALS["x_nombre"]." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]; // formato de la lista RUELAS AVILA, Jose Luis
	$x_nombre_busqueda_lista_negra = trim($x_nombre_busqueda_lista_negra);
	$sSqlNegra = "SELECT * FROM `csv_lista_negra_cnbv` WHERE `nombre_completo` LIKE _utf8 '%".$x_nombre_busqueda_lista_negra."%' COLLATE utf8_general_ci";
	$rsNEGRA = phpmkr_query($sSqlNegra,$conn) or die("Error en busqueda en la lista NEGRA". phpmkr_error());

	while($rowNEGRA = phpmkr_fetch_array($rsNEGRA)){
		$x_nombre_completo_negra =$rowNEGRA["nombre_completo"];
		$x_existe_negra=1;
		}

		if($x_existe || $x_existe_negra){
			// si se encontro el registro se manda un mail al oficial de cumpliento
			// se bloquea la solicitud
			// se genera un registro de tipo OPERACION INUSUAL  ==se espcifica que es de 24 horas
			#MAIL
			$x_mensaje = "";
		    $tiposms = "";
			if($x_existe){
				$x_mensaje = "SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA DE LA LISTA OFAC CON EL CLIENTE ".$GLOBALS["x_nombre"] ." ". $GLOBALS["x_apellido_paterno"]. " ".$GLOBALS["x_apellido_materno"] ."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE AL LISTADO DE OFAC PARA CONFIRMAR LA INFORMACION \n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO, ENT_NUM =  ".$x_ent_num." NOMNBRE => ".$x_sdn_name;
				$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`) VALUES (NULL, '".$x_cliente_idl."', '".$GLOBALS["x_solicitud_id"]."', '2','Reporte de 24 horas == AQUÍ LA DESCRIPCIÓN DE LA OPERACIÓN ==','El nombre del cliente fue encontrado en la lista OFAC con el ENT_NUM = ".$x_ent_num."', '1', '".$x_sdn_name."'  )";
				}
			if($x_existe_negra){
				$x_mensaje = "SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA EN LA LISTA NEGRA DE LA CNBV CON EL CLIENTE ".$GLOBALS["x_nombre"] ." ". $GLOBALS["x_apellido_paterno"]. " ".$GLOBALS["x_apellido_materno"] ."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE A LA LISTA NEGRA DE LA CNBV PARA CONFIRMAR LA INFORMACION \n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO";
				$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`) VALUES (NULL, '".$x_cliente_idl."', '".$GLOBALS["x_solicitud_id"]."', '2','Reporte de 24 horas == AQUÍ LA DESCRIPCIÓN DE LA OPERACIÓN ==','El nombre del cliente fue encontrado en la lista negra de la CNBV ".$x_ent_num."', '1', '".$x_nombre_completo_negra."'  )";
				}


						$para  = 'oficialdecumplimiento@financieracrea.com'; // atención a la coma
						// subject
						$titulo = "== SE HA REGISTRADO UNA OPERACION INUSUAL DE 24 HRS ==";
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';

						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";
						// Mail it
						mail($para, $titulo, $x_mensaje, $cabeceras);
						$hoy = date("Y-m-d");
						$x_fecha = ConvertDateToMysqlFormat($hoy);

			#BLOQUEO DE SOLICITUD
			 $sqlUpdate = "UPDATE solicitud  SET  `solicitud_status_id` =  '13' WHERE  `solicitud`.`solicitud_id` =".$GLOBALS["x_solicitud_id"]." ";
			 phpmkr_query($sqlUpdate, $conn) or die ("Error al modificar el status de la solicitud". phpmkr_error()."sql :". $sqlUpdate);


			#GENERA LA OPERACION INUSUAL DE 24 HORAS
			phpmkr_query($sSqlInsert, $conn) or die ("Error al insertar la operacion inusual 24 horas en la listado reporte cnbv". phpmkr_error()."sql :". $sSqlInsert);



			}


	}//fin else  phpmkr_num_rows($rs) == 0

	//terminamos transaccion


		phpmkr_query('commit;', $conn);

	return true;
	}



?>
