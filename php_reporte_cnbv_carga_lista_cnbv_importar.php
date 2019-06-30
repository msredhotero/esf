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
if (@$_SESSION["php_project_esf_status"]  <> "login") {
	header("Location:  login.php");
}
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
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];
$currdate = $currentdate["year"]."/".$currentdate["mon"]."/".$currentdate["mday"];
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
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString



$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

//obtenemos el archivo .csv
$tipo = $_FILES['archivo2']['type'];
 
$tamanio = $_FILES['archivo2']['size'];
 
$archivotmp = $_FILES['archivo2']['tmp_name'];
 
//cargamos el archivo
$lineas = file($archivotmp);
 $conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
//inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea
$i=0;
 
//Recorremos el bucle para leer línea por línea
foreach ($lineas as $linea_num => $linea)
{ 
   //abrimos bucle
   /*si es diferente a 0 significa que no se encuentra en la primera línea 
   (con los títulos de las columnas) y por lo tanto puede leerla*/
   if($i != 0) 
   { 
       //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
       /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá 
       leyendo hasta que encuentre un ; */
       $datos = explode(",",$linea);
 
       //Almacenamos los datos que vamos leyendo en una variable
       //usamos la función utf8_encode para leer correctamente los caracteres especiales
       $campo1 = strtoupper(utf8_decode($datos[1]));
	   $campo2 = strtoupper(utf8_decode($datos[2]));
	   $campo3 = strtoupper(utf8_decode($datos[3]));
	   $campo4 = strtoupper(utf8_decode($datos[4]));
	   $campo5 = strtoupper(utf8_decode($datos[5]));
	   $campo6 = strtoupper(utf8_decode($datos[6]));
	   
       //guardamos en base de datos la línea leida
       
 		$sSqlWrk = "INSERT INTO csv_lista_negra_cnbv(id_csv_lista_negra_cnbv,nombre_completo,rfc,fecha_reporte,monto,entidad_reporta,notas) VALUES(NULL,'$campo1','$campo2','$campo3','$campo4','$campo5','$campo6')";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("<b><center><span class=\"texto_titulo\">Error al importar los registros, por favor verifique que su archivo tiene el formato correcto.</b><p><br> Contacte al administrador y envie el siguiente error junto con el archivo que intento cargar<br><p> " . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
	#echo $sSqlWrk."<br>";
       //cerramos condición
   }
 
   /*Cuando pase la primera pasada se incrementará nuestro valor y a la siguiente pasada ya 
   entraremos en la condición, de esta manera conseguimos que no lea la primera línea.*/
   $i++;
   //cerramos bucle
}
?>




<?php include("header.php"); ?>
<?php include("utilerias/fecha_letras.php"); ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />

<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript" src="../scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.themeswitcher.js"></script>

<script>
$(document).ready(function() {
	ocultaCampos();
	//alert("entro a jqyery");
	$('#btnAdd').click(function() {	
		var num     = $('.clonedInput').length;
		var newNum  = new Number(num + 1);
		$('#contador').attr('value',num);
		var newElem = $('#cel_' + num).clone().attr('id', 'cel_' + newNum);
		newElem.find('td:eq(0) ').html('Celular ' + newNum);
		newElem.find('td:eq(1) input:eq(0)').attr({'id':'x_celular_'+newNum,'name':'x_celular_'+newNum,'value': 0});
		newElem.find('td:eq(3) select:eq(0)').attr({'id':'x_compania_id_'+newNum,'name':'x_compania_id_'+newNum,'value': 0});	
		 $('#cel_' + num).after(newElem);
		 newElem.find('td:eq(4) button:eq(0)').remove();
		
		
		});// botonadd
		
	//check box
	$("#x_aviso_de_privacidad").change(function(){
			if($(this).is(':checked')){
				//activamos el boton de enviar;
				$('#enviar').removeAttr("disabled");
			}
		});
	
	
	
	function ocultaCampos(){
		
		tipo_reporte_id = $('#x_tipo_reporte_id').val();
			if(tipo_reporte_id == 1){
				
	$(".quita_r").hide();	
				
				}else{
					$(".quita_r").show();
					}
		}
		$('#x_tipo_reporte_id').change(function(){
			
			ocultaCampos();
			
			});
		
	
	});
</script>

<script src="paisedohint.js"></script> 
<script src="muestra_dir_empresa.js"></script>
<script src="muestra_outsourcing.js"></script>
<style type="text/css">
.tabla_mensaje{
	background-color:#ecfafd;
	border:2px solid #b7eff9;
	padding:10px;
	}
	
.REQURIDO{
	color:#F60;
	
	}
</style>

<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script type="text/javascript">
<!-- funciones java script -->


function show_address(empresa_id){
	x_empresa_id = empresa_id.value;
	process(x_empresa_id);	
	process_2(x_empresa_id);	
	}

function solonumeros(myfield, e, dec){
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;
// numbers
else if ((("0123456789").indexOf(keychar) > -1))
   return true;
// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}



function EW_checkMyForm() {
EW_this = document.entrevista_inicial;
validada = true;









if(validada == true){
	EW_this.a_add.value = "U";
	EW_this.submit();
}

}

</script>

<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<form action="importar_registros_a_db.php"  enctype="multipart/form-data" method="post">
  <input type="hidden" name="a_add" value="U" />
  <input type="hidden" name="x_fecha_registro" value="<?php echo $currdate;?>" />
  <input type="hidden" name="x_fecha_afiliacion" value="<?php echo $currdate;?>" />
  <input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id ?>" />
  <input type="hidden" name="x_cliente_id" value="<?php echo $x_cliente_id ;?> " />
  <input type="hidden"  name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
  <input type="hidden" name="x_entrevista_inicial_id" id="x_entrevista_inicial_id" value="<?php echo $x_entrevista_inicial_id;?>" />
  <input type="hidden" name="x_reporte_cnbv_id" value="<?php echo $x_reporte_cnbv_id;?>" />
  
  
  <?php
  $currdate =  date("Y-m-d");
  if( empty($x_fecha_registro)){
	  $x_fecha_registro = $currdate;
	  }
if (@$_SESSION["ewmsg"] <> "") {
		
?>
  <p><span class="ewmsg">
    <?php  echo $_SESSION["ewmsg"] ?>
  </span></p>
  <?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table  border="0" 
   cellpadding="1" cellspacing="0" >
    <tr>
    <td ><a href="php_cnbvlist.php?cmd=resetall">Lista negra CNBV</a></td>
     
      
      
     
    </tr>
</table>
  <p></p>
  <p></p>
  <p></p>
  <br />
  <br />

<p>
<div class="content-box-gray">
<br /><b>Importar registros a la lista negra de la CNBV:</b> Se importaron con existo <b><?php echo ($i -1);?></b> registros en la lista negra de la CNBV<p>
  </div>
  <p>&nbsp;</p>
</form>
<?php include ("footer.php") ?>

<?php
//phpmkr_db_close($conn);
?>
<?php 





?>