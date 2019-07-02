<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		
$url="http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];

echo "RUTA ACTUAL".$url."<br> ------";

//numero aleatorio para el aval  
      srand(time());  
      $id_solicitud_id_aval = rand(0,1000);
	  $id_solicitud_id_aval = $id_solicitud_id_aval.$currentdate["hours"].$currentdate["minutes"].$currentdate["seconds"]; 

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
/*
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
*/
?>
<?php

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
?>
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php include("tipoCuenta/formatos/altaSolicitud.php");
?>

<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$bCopy = true;
$x_solicitud_id = @$_GET["solicitud_id"];
if (empty($x_solicitud_id)) {
	$bCopy = false;
}

 
if($_POST["envioDatosPost"] == "confirmado" ){
	
	//aqui hacemos el insert a la base de datos........
	$x_Credito = $_POST["x_tipo_credito"];
	
	if($x_Credito == 1){
		insertaCreditoIndividualPersonal();	
		
		}else if($x_Credito == 2){
			insertaCreditoSolidario();
			
			}else if($x_Credito == 3){
				insertaCreditoMaquinaria();
				
				}else{
					//$x_Credito  == 4
					insertaCreditoPYME();
					
					}
	
	
	
	
	
	
	
	}
		
	
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" /> 



<link href="../crm.css" rel="stylesheet" type="text/css" />
<link href="tipoCuenta/cssFormatos/cssFormatos.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
/*
if (@$_SESSION["crm_login_status"] <> "logincrm2009") {
	echo '<script> window.parent.location="login.php"; </script>';
	exit();
}*/




?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="../ew.js"></script>
<script language="javascript" src="tipoCuenta/formatos/js/tipoCuenta.js"></script>
<script src="paisedohint.js"></script>
<script src="mapsNegocio.js"></script>
<script src="generaCurpRfc.js"></script>
<script src="lochint.js"></script>

<script language="javascript" src="tipoCuenta/formatos/js/validaTipoCredito.js"></script> 
<script language="javascript" src="tipoCuenta/formatos/js/eventosTipoCredito.js"></script>
<script language="javascript" src="tipoCuenta/formatos/js/carga_telefonos.js"></script>


<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>


<script type="text/javascript"> 
// esperamos 10 segundos y paintamos el mapa



// codigo para el google maps
  var geocoder;
  var map;
  var markersArray = [];
  
  function initialize() {
	  
    geocoder = new google.maps.Geocoder();
	// lo localizamos en la ciudad de mexico
	
    var latlng = new google.maps.LatLng(19.4270499, -99.12757110000001);
	
	


    var myOptions = {
      zoom: 15,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
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
        alert("La busqueda de la direccion no se realizo exitosamente debido a: " + status);
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
</script> 


<script type="text/javascript"> 
// esperamos 10 segundos y paintamos el mapa



// codigo para el google maps
  var geocoder2;
  var map2;
  var markersArray2 = [];
  
  function initialize2() {
	  
    geocoder2 = new google.maps.Geocoder();
	// lo localizamos en la ciudad de mexico
	
    var latlng2 = new google.maps.LatLng(19.4270499, -99.12757110000001);
	
	


    var myOptions2 = {
      zoom: 15,
      center: latlng2,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map2 = new google.maps.Map(document.getElementById("map_canvas2"), myOptions2);
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
        alert("La busqueda de la direccion no se realizo exitosamente debido a: " + status);
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
</script> 
<script language="javascript">
	window.onload = function(){
		document.getElementById("verFormato").onclick = eventosTipoCredito;
		document.getElementById("x_tipo_credito").onchange = actualizaHiddenTC;
		document.getElementById("registro").onclick = validaFormulario;	
		document.getElementById("seEnvioFormulario").onclick = muestraOculta;
		document.getElementById("x_actividad_id").onchange = act;
		document.getElementById("x_importe_solicitado").onblur = validaimporte;
		
		
		function act(){
		vact = document.frmAddSolicitud.x_actividad_id.value;

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
}
		
	
		function validaimporte(){
				EW_this = document.frmAddSolicitud;
				if(EW_this.x_importe_solicitado.value < 1000){
		alert("El importe es incorrecto valor minimo 1000");
		EW_this.x_importe_solicitado.focus();
	}
}	
	
		function muestraOculta (){
			document.getElementById("seEnvioFormulario").style.display = "none";	
			}
		
		
		function imnprimeV(){
			var doc = document.getElementById("formatoTipoCredito").value;
			//alert("valor"+doc+"...."); imprime el tipo de credito seleccionado
			
			}
			
			
		function actualizaHiddenTC(){
			var doc = document.getElementById("x_tipo_credito").value;
			//alert(doc); imprime el tipo de creditop seleccionado
			document.getElementById("formatoTipoCredito").value = doc;
			 imnprimeV();
			}
			
			
		function validaParteUno(){			
			///aqui la validadcion para la prmera paret del formulario		
			EW_this = document.frmAddSolicitud;
			validada = true;
			//si la valicion es correcta
			if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
				validada = false;
			}

			if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
			validada = false;
			}
			if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
			validada = false;
			}
			if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
			if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
			validada = false;
			}
			if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el plazo solicitado."))
			validada = false;
			}
			if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
			validada = false;
			}
			
			if(validada == true){
				var doc = document.getElementById("x_tipo_credito").value;
			    document.getElementById("registro").style.display = "block";
				tipo_Credito = doc ;
				process(tipo_Credito);	
				
				setTimeout("initialize()",500);
				setTimeout("initialize2()",500);
				
				}			
			
			}
			
		function eventosTipoCredito(){
					
			validaParteUno();
			if(tipo_Credito == 1){
				//eventosFormatoIndividual();
				eventosFormatoIndividualPersonal();
				document.getElementById("aval_visor").style.display= "block";
				}else if(tipo_Credito == 2){
					eventosFormatoSolidario();
					}else if(tipo_Credito == 3){
						document.getElementById("aval_visor").style.display= "block";
						eventosFormatoMaquinaria();
						}else if(tipo_Credito == 4){
							document.getElementById("aval_visor").style.display= "block";
							eventosFormatoPYME();
							}else if(tipo_Credito == 5){
								document.getElementById("aval_visor").style.display= "block";
								eventosFormatoRevolventeM();
								}else{
								 	alert("NO HA SELECCIONADO NINGUN TIPO DE CREDITO, POR FAVOR SELECCIONE UNO Y DESPUES CLICK EN FORMATO");								
									}
				
			}
			
			
		
	
		function validaFormulario(){
			
			tipo_Credito = document.getElementById("x_tipo_credito").value;
		
			//tipo_Credito = document.getElementById("formatoTipoCredito").value;
			
			if(tipo_Credito == 1){
				//validaFrmCreditoInd();
				validaFrmCreditoIndPersonal();
				}else if(tipo_Credito == 2){
					validaFrmCreditoSol();
					}else if(tipo_Credito == 3){
						validadFrmCreditoAdqMaq();
						}else if(tipo_Credito == 4){
							validadFrmCreditoPyme();
							}else if(tipo_Credito == 5){
								validaFrmCreditoRevolventeM();
								}else alert("NO HA SELECCIONADO EL TIPO DE CREDITO");
			
			}//fin validaFormulario	
			
			
			
			
			
			
}//window.onload	



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




function valorMax(elemento){
				//verifaca si le valor de campo para numero de pagos es correcto de ser un valor entre 2 hasta 88
				
				numer_pag = document.getElementById("x_plazo_id").value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor");					 
					 
					 }
				
				
				
				}
				
function 	llenaDatosNegocio(){
	
	EW_this = document.frmAddSolicitud;
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
		setTimeout("initialize2()",500);
	}	 
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
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<link href="../crm.css" rel="stylesheet" type="text/css" />
<p>
<form name="frmAddSolicitud" id="frmAddSolicitud" method="post" action="php_solicitudadd_v1.php">
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_id_solicitud_id_aval" value="<?php echo $id_solicitud_id_aval;?>" />
<input type="hidden" name="envioDatosPost" id="envioDatosPost" value="confirmado" />
<input type="hidden" name="formatoTipoCredito" id="formatoTipoCredito" value="0" />
<?php /* condigo antiguo
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php echo("LineaMensaje REspuesta"); ?>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
	//phpmkr_db_close($conn);
	ob_end_clean();	
	//exit();
}*/ ?>

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


<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	 <tr>
      <td  colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Solicitud</td>
    </tr>
	<tr>
	  <td class="texto_normal">&nbsp;</td>
	  <td colspan="4">&nbsp;</td>
	  </tr>
      <tr>
      <td height="27" class="texto_normal">Vendedor</td>
	  <td colspan="4"><span class="phpmaker">
	    <?php
	
		$x_estado_civil_idList = "<select name=\"x_vendedor_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 7) {
			$sSqlWrk = "SELECT `vendedor_id`, `nombre_completo` FROM `vendedor`";
		}else{
			$sSqlWrk = "SELECT `vendedor_id`, `nombre_completo` FROM `vendedor`";		
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vendedor_id"] == @$x_promotor_id) {
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
	  </span></td>
      
      </tr>
	<tr>
	  <td height="24" class="texto_normal">Promotor:</td>
	  <td colspan="4"><span class="phpmaker">
	    <?php
	
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["crm_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";		
		}
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
	  </span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">&nbsp;</td>
	  <td colspan="4"><span class="phpmaker">
      <input type="hidden" name="x_cliente_id" id="x_cliente_id" value="0" />
	    <?php
		/*
		$x_estado_civil_idList = "<select name=\"x_cliente_id\" onchange=\"buscacliente()\" class=\"texto_normal\" >";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($x_cliente_id == 0){
			$x_estado_civil_idList .= "<option value='0' selected>Nuevo</option>";		
		}else{
			$x_estado_civil_idList .= "<option value='0'>Nuevo</option>";				
		}
		$sSqlWrk = "SELECT `cliente_id`, `nombre_completo`, `apellido_paterno`, `apellido_materno`  FROM `cliente` order by nombre_completo, cliente_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			$x_nom_rec = "";
			while ($datawrk = phpmkr_fetch_array($rswrk)) {

				$x_nom = $datawrk["nombre_completo"]." ".$datawrk["apellido_paterno"]." ".$datawrk["apellido_materno"];
			
				if($rowcntwrk > 0 && $x_nom_rec != $x_nom){
					$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($x_cliente_id_rec) . "\"";
					if ($x_cliente_id_rec == @$x_cliente_id) {
						$x_estado_civil_idList .= "' selected";
					}
					$x_estado_civil_idList .= ">" . htmlspecialchars($x_nom_rec) . "</option>";
				}
				$rowcntwrk++;				
				
				$x_nom_rec = $datawrk["nombre_completo"]." ".$datawrk["apellido_paterno"]." ".$datawrk["apellido_materno"];
				$x_cliente_id_rec = $datawrk["cliente_id"];
			
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		*/
?>
	  </span></td>
	  </tr>
	<tr>
	  <td width="159" class="texto_normal">Tipo de Crédito: </td>
	  <td colspan="2" class="texto_normal"><b><?php
		$x_tipo_credito = "<select name=\"x_tipo_credito\" id=\"x_tipo_credito\" class=\"texto_normal\">";
		$x_tipo_credito .= "<option value=''>Tipo Credito</option>";
		//select
		$sSqlWrk = "SELECT credito_tipo_id, descripcion FROM credito_tipo";			
	
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL select tipo de credito :' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_tipo_credito .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";				
				$x_tipo_credito .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_tipo_credito .= "</select>";
		echo $x_tipo_credito;
?></b>
	 
	  </td>
	  <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
	  <td width="164">
	  <span class="texto_normal">
	  <b>
	  <?php echo $currdate; ?>	  </b>	  </span>
	  <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Importe solicitado: </span></td>
	  <td width="111"><div align="left">
	    <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php echo htmlspecialchars(@$x_importe_solicitado) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"  />
	  </div></td>
	  <td width="10">&nbsp;</td>
	  <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos:</span></div></td>
	  <td><span class="texto_normal"> <input type="text" name="x_plazo_id" id="x_plazo_id" maxlength="3" size="15" onKeyPress="return solonumeros(this,event)" onchange="valorMax();" />

	  </span></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
	  <td><span class="texto_normal">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
	  </span></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="4">&nbsp;</td>
	  </tr>
	<tr>
	  <td><input type="hidden" name="folio_aval" value="" /></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><p></td>
	  <td><input type="button" value="formato" name="verFormato" id="verFormato" /></td>
	  </tr>
	</table>	</td></tr>
    <tr><td></td></table>
  <!-- aqui ternmino la primer tabla que contiene lña infor macion basica  la siguente tabla la mostramos mediante AJAX dependiendo del tipo de credito-->
 <table width="700" border="0" align="center" cellpadding="0">
 <tr><td > <div id="aval_visor" style="display:none"><iframe name="aval"   src="php_visor_aval.php?key=<?php echo $id_solicitud_id_aval;?>" scrolling="no" style="margin-left:0px; width:150px; height:35px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe></div> </td></tr>
 <tr><td><div id="capaTipoCredito"></div> </td></tr></table>
 

 
  
    <table width="700" border="0" align="center" cellpadding="0" ><tr><td align="right"><p>&nbsp;
    <p>&nbsp;<p><p> <div> <input type="button" name="registro" id="registro" value="Registrar Solicitud" class="boton_medium" onmouseover="javascript: this.style.cursor = 'pointer'" style="display:none;" />
    </div></td>
    </tr>
</table>


</form>
</body>
</html>
