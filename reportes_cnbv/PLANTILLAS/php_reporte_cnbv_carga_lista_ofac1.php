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


$x_reporte_cnbv_id = @$_GET["reporte_cnbv_id"];
$x_solicitud_id = @$_GET["solicitud_id"];
$x_cliente_id = @$_GET["cliente_id"];
$x_tipo_id =  @$_GET["tipo"];
$x_tipo_reporte_id =$_GET["tipo"];

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

// Get fields from form
//solicitud



foreach($_POST as $campo => $valor){
	$$campo = $valor;
	}

}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn, $x_cliente_id, $x_solicitud_id,$x_tipo_reporte_id,$x_reporte_cnbv_id)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_alerta_pld_list.php");
			exit();
		}
		break;
	case "U": // Add
		if (EditData($conn)) { // Add New Record
			$_SESSION["ewmsg"] .= "***EL  REPORTE A CNBV FUE REGISTRADO CORRECTAMENTE. ***";
			phpmkr_db_close($conn);
			ob_end_clean();		 
			header("Location: php_reporte_cnbvlist.php");
			exit();
		}
		
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
<form action="php_reporte_cnbv_carga_lista_ofac1_importar.php" enctype="multipart/form-data" method="post">
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

$x_archivo_cnbv_lin1 = "php_reporte_cnbv_descarga_ficheros.php?archivo=".$x_carpeta."PLANTILLAS/plantilla_lista_ofac1.xlsx";
?>
<table  border="0" 
   cellpadding="1" cellspacing="0" >
    <tr>
    <td ><a href="php_ofaclist.php?cmd=resetall">Lista OFAC</a></td>
    <td >&nbsp;</td>
   <td><a href="<?php echo $x_archivo_cnbv_lin1;?>" target="_blank">DESCARGAR PLANTILLA</a></span></td>

      
     
    </tr>
</table>
  <p></p>
  <p></p>
  <p></p>
  <br />
  <br />
<table width="800" border="0" align="center" cellpadding="1" cellspacing="0"  class="texto_normal_SIP">
    <tr>
      <td colspan="7" valign="top"  class="encabezado_crea"> Cargar archivo .csv a la lista OFAC </td>
    </tr>
     
    
   </table><p>
    
    <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
<table  width="800" border="0" align="center" cellpadding="1" cellspacing="0"  class="texto_normal_SIP">
  <tr>
      <td colspan="3"></td>
      </tr>     
      <tr class="quita_r">
      <td></td>
      <td>Seleccionar archivo</td>
      <td>
      <input id="archivo" accept=".csv" name="archivo2" type="file" />
      </td>
        
     <tr >
       <td colspan="3" class="linea_sip"><input   type="submit"  name="enviar"  id="enviar" value="Cargar registros"   /></td>
     </tr>
     
</table>
<p>
<div class="content-box-gray">
<br />
<b>NOTA:</b> Cada campo dentro de un registro esta separado por una ","(coma) es importante verificar que dentro de cada columna no existe este caracter si es necesario se puede sustituir por ";" (punto y coma)
<p><b>Ejemplo: </b>Vessel Registration Identification IMO 8688171; MMSI 249000882; Linked To: WORLD WATER FISHERIES LIMITED.
<P>
<b>PROCEDIMEINTO</b><BR />1.- Es necesario descargar la plantilla(DESCARGAR PLANTILLA) para poder importar el archivo con formato correcto,
<BR />2.-  Cuando se descarga se puede observar que viene un registro de ejemplo, el cual se debera sustituir por los registros que se desean agragar a la lista, los campos sin valor se llenan con -0- 
<BR />3.-  Guardar el archivo como .csv
<br />4.- Abrir el archivo .csv con el block de notas y dar click en Archivo->Guardar como
<br />&nbsp;&nbsp;* Haga clic en el menú desplegable al lado de "Guardar como tipo de archivo" y elija "Todos los archivos."
<br />&nbsp;&nbsp;* Ir a la "Codificación" y seleccione "UTF-8" en el menú desplegable. Haga clic en "Guardar". 
<br />5.- Una vez que esta preparado el archivo en la codificacion correcta se puede importar. 

</div>
  <p>&nbsp;</p>
</form>
<?php include ("footer.php") ?>

<?php
//phpmkr_db_close($conn);
?>
<?php 

function LoadData($conn, $cliente_id, $solicitud_id, $tipo_reporte_id, $id){
		
		$x_load_data = true;
	 	
		
		#echo  $GLOBALS["x_reporte_cnbv_id"]."<br>.. $tipo_reporte_id";
		$reporte_id = $GLOBALS["x_reporte_cnbv_id"];
		#echo "tio.... =>".$tipo_reporte_id;
		#$GLOBALS["x_tipo_reporte_id"] = $tipo_reporte_id;
		$x_today = date("Y-m-d");
		$GLOBALS["x_fecha_registro"] = $x_today;
		
		if($solicitud_id>0){
			
			if (!empty($id)){
				$sql_and =  " AND  reporte_cnbv_id  = ".$id." ";
				}
		#$sSql = " SELECT * FROM reporte_cnbv WHERE reporte_cnbv_id  = ".$reporte_id;
		$sSql = " SELECT * FROM reporte_cnbv WHERE cliente_id = ".$cliente_id." AND solicitud_id = ".$solicitud_id." AND tipo_reporte_id = ".$tipo_reporte_id."" .$sql_and ;
		#33echo "<BR>".$sSql."==><BR>";
		
		$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql);
		//echo  $sSql ;
		if(!$rs ){
			$x_load_data  = false;
			}
		$row = phpmkr_fetch_array($rs);
		
		$sql_Campos = " DESCRIBE reporte_cnbv ";
		$rs_CAMPOS = phpmkr_query($sql_Campos,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql_Campos);	
		while($rowcMPOS = phpmkr_fetch_array($rs_CAMPOS)){
			//
			
			$x_nombre_campo = $rowcMPOS["Field"];
			if($x_nombre_campo != 'tipo_reporte_id' && $x_nombre_campo != 'solicitud_id' && $x_nombre_campo != 'cliente_id' && $x_nombre_campo != 'reporte_cnbv_id' ){
			$x_campo = "x_".$rowcMPOS["Field"];
			$$campo = $row[$x_nombre_campo];
			$GLOBALS[$x_campo] = $row[$x_nombre_campo];
			#echo $x_campo."=>".$$campo;
			}
			}
			$x_tipo_credito = '';
			
			
			
			if(empty($GLOBALS["x_numero_cuenta"]) && ($cliente_id >0 &&  $solicitud_id>0)){
				
				$sqlCreditoNum = "SELECT *  FROM credito WHERE solicitud_id = ".$solicitud_id;
				#echo $sqlCreditoNum."<br>";
				$rs_CAMPOSc = phpmkr_query($sqlCreditoNum,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sqlCreditoNum);	
				$rowcMPOS = phpmkr_fetch_array($rs_CAMPOSc);
			
				$GLOBALS["x_numero_cuenta"] =$rowcMPOS["credito_num"];
				#echo "<br>..".$rowcMPOS["credito_num"];
				$x_tipo_credito = $rowcMPOS["credito_tipo_id"];
				#1==> Nomina 7 ==> Moral 3 ==> cuenta corriente
				
				$GLOBALS["x_tipo_persona_id"] = ($x_tipo_credito==1)?1:2;
				}
				
				if(empty($GLOBALS["x_monto"]) && ($cliente_id >0 &&  $solicitud_id>0)){
					$sqlAlerta = "SELECT *  FROM alerta_pld WHERE solicitud_id = ".$solicitud_id;
				#echo $sqlCreditoNum."<br>";
				$rs_CAMPOSc = phpmkr_query($sqlAlerta,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sqlCreditoNum);	
				$rowcMPOS = phpmkr_fetch_array($rs_CAMPOSc);
			
				$GLOBALS["x_monto"] = $rowcMPOS["monto_pago"];
				$x_feca_pld = $rowcMPOS["fecha"];
				$arrFPLD = explode("/",$x_feca_pld);
				$GLOBALS["x_fecha_operacion"] = $rowcMPOS["fecha"];
				
				if($tipo_reporte_id==1)
				$GLOBALS["x_fecha_deteccion_operacion"]= $rowcMPOS["fecha"];
					
					
					}
					
				if(empty($GLOBALS["x_razon_social"]) || empty($GLOBALS["x_nombre"])){
					
					$sqlAlerta = "SELECT *  FROM cliente WHERE cliente_id = ".$cliente_id;
					#echo $sqlCreditoNum."<br>";
					$rs_CAMPOSc = phpmkr_query($sqlAlerta,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sqlCreditoNum);	
					$rowcMPOS = phpmkr_fetch_array($rs_CAMPOSc);
			
					$GLOBALS["x_razon_social"] = $rowcMPOS["razon_social"];
					$GLOBALS["x_nombre"] = $rowcMPOS["nombre_completo"];
					$GLOBALS["x_paterno"] = $rowcMPOS["apellido_paterno"];
					$GLOBALS["x_materno"] = $rowcMPOS["apellido_materno"];				
					$GLOBALS["x_rfc"] = $rowcMPOS["rfc"];
					$GLOBALS["x_curp"] = $rowcMPOS["curp"];					
					if($x_tipo_credito ==1){
						$GLOBALS["x_fecha_nacimiento"] = $rowcMPOS["fecha_nac"];
						}
					#}
					}
					
					
		
		$sSql = "select * from direccion where cliente_id = ".$cliente_id." AND direccion_tipo_id = 1";
		$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud direccion oficina".phpmkr_error()."sql :".$sql);	
			
		$row = phpmkr_fetch_array($rs);
		//datos de tabla direccion
		$GLOBALS["x_direccion_id_1"] = $row["direccion_id"];
		$GLOBALS["x_domicilio"] = $row["calle"];
		$GLOBALS["x_num_ext"] = $row["num_ext"];
		$GLOBALS["x_num_int"] = $row["num_int"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_entidad_id"] = $row["entidad"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_fax"] = $row["fax"];
		
		
		
		
		
		
		phpmkr_free_result($rs);		
		phpmkr_free_result($rs_CAMPOS);
		}
		
		// si es un reporte inusual o interno preocupante
		if($solicitud_id=='0' && !empty($id) ){
			// viene vacio es un reporte nuevo			
		$sSql = " SELECT * FROM reporte_cnbv WHERE reporte_cnbv_id = ".$id." ";
		$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql);
		if(!$rs ){
			$x_load_data  = false;
			}
		$row = phpmkr_fetch_array($rs);
		
		$sql_Campos = " DESCRIBE reporte_cnbv ";
		$rs_CAMPOS = phpmkr_query($sql_Campos,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql_Campos);	
		while($rowcMPOS = phpmkr_fetch_array($rs_CAMPOS)){
			//
			$x_nombre_campo = $rowcMPOS["Field"];
			$x_campo = "x_".$rowcMPOS["Field"];
			$$campo = $row[$x_nombre_campo];
			$GLOBALS[$x_campo] = $row[$x_nombre_campo];
			}
			
			}
		#echo "tio.... =>".$tipo_reporte_id;
		
	return $x_load_data;	
	
	}



function EditData($conn){
	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
	// Field empresa_outsourcing
		
	// Field promotor_id
	$theValue = ($GLOBALS["x_tipo_reporte_id"] != "") ? intval($GLOBALS["x_tipo_reporte_id"]) : "NULL";
	$fieldList["`tipo_reporte_id`"] = $theValue;
	
	// Field promotor_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;
	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $theValue;
	#echo "<br>periodo=>".$GLOBALS["x_periodo"];
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_periodo"]) : $GLOBALS["x_periodo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`periodo`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_organo_supervisor"]) : $GLOBALS["x_organo_supervisor"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`organo_supervisor`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_clave_sujeto_obligado"]) : $GLOBALS["x_clave_sujeto_obligado"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`clave_sujeto_obligado`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_localidad"]) : $GLOBALS["x_localidad"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`localidad`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cp_sucursal"]) : $GLOBALS["x_cp_sucursal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cp_sucursal`"] = $theValue;
	
	
	$theValue = ($GLOBALS["x_tipo_operacion_id"] != "") ? intval($GLOBALS["x_tipo_operacion_id"]) : "NULL";
	$fieldList["`tipo_operacion_id`"] = $theValue;
	
	$theValue = ($GLOBALS["x_instrumento_monetario_id"] != "") ? intval($GLOBALS["x_instrumento_monetario_id"]) : "NULL";
	$fieldList["`instrumento_monetario_id`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_numero_cuenta"]) : $GLOBALS["x_numero_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`numero_cuenta`"] = $theValue;
	
	$theValue = ($GLOBALS["x_monto"] != "") ? doubleval($GLOBALS["x_monto"]) : "NULL";
	$fieldList["`monto`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_moneda"]) : $GLOBALS["x_moneda"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`moneda`"] = $theValue;
	
	$theValue = ($GLOBALS["x_fecha_operacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_operacion"]) . "'" : "Null";
	$fieldList["`fecha_operacion`"] = $theValue;
	
	$theValue = ($GLOBALS["x_fecha_deteccion_operacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_deteccion_operacion"]) . "'" : "Null";
	$fieldList["`fecha_deteccion_operacion`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nacionalidad"]) : $GLOBALS["x_nacionalidad"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nacionalidad`"] = $theValue;
	
	
	
	$theValue = ($GLOBALS["x_tipo_persona_id"] != "") ? intval($GLOBALS["x_tipo_persona_idd"]) : "NULL";
	$fieldList["`tipo_persona_id`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_razon_social"]) : $GLOBALS["x_razon_social"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`razon_social`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_paterno"]) : $GLOBALS["x_paterno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`paterno`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_materno"]) : $GLOBALS["x_materno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`materno`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;
	
	$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "Null";
	$fieldList["`fecha_nacimiento`"] = $theValue;
	
	
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_domicilio"]) : $GLOBALS["x_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`domicilio`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuidad"]) : $GLOBALS["x_cuidad"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cuidad`"] = $theValue;
	

	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_actividad_economica"]) : $GLOBALS["x_actividad_economica"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_economica`"] = $theValue;
	
	// Field empresa_outsourcing
	$theValue = ($GLOBALS["x_consecutivo_cuentas"] != "") ? intval($GLOBALS["x_consecutivo_cuentas"]) : "NULL";
	$fieldList["`consecutivo_cuentas`"] = $theValue;
	
	// Field empresa_outsourcing
	$theValue = ($GLOBALS["x_numero_contrato"] != "") ? intval($GLOBALS["x_numero_contrato"]) : "NULL";
	$fieldList["`numero_contrato`"] = $theValue;
	
	
	// Field empresa_outsourcing
	$theValue = ($GLOBALS["x_clave_aval"] != "") ? intval($GLOBALS["x_clave_aval"]) : "NULL";
	$fieldList["`clave_aval`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_titular"]) : $GLOBALS["x_nombre_titular"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_titular`"] = $theValue;
	
	// Field empresa_outsourcing
	$theValue = ($GLOBALS["x_paterno_titular"] != "") ? intval($GLOBALS["x_paterno_titular"]) : "NULL";
	$fieldList["`paterno_titular`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_materno_titular"]) : $GLOBALS["x_materno_titular"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`materno_titular`"] = $theValue;
	
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion_operacion"]) : $GLOBALS["x_descripcion_operacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`descripcion_operacion`"] = $theValue;
	
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_razon_reporte"]) : $GLOBALS["x_razon_reporte"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`razon_reporte`"] = $theValue;
	
		
	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	
		if(!empty($GLOBALS["x_reporte_cnbv_id"]) ){
			// si esta lleno se actualiza el registro
			
			$sSql = "UPDATE `reporte_cnbv` SET ";
			foreach ($fieldList as $key=>$temp) {
						$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE reporte_cnbv_id = ".$GLOBALS["x_reporte_cnbv_id"]."";

		$x_result = phpmkr_query($sSql,$conn);
		
		echo "<br>".$sSql;

		if(!$x_result){
			$x_add_data = false;
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
			
			
			}else{
				//si esta vacio se inserta el registro				
				$strsql = "INSERT INTO `reporte_cnbv` (";
				$strsql .= implode(",", array_keys($fieldList));
				$strsql .= ") VALUES (";
				$strsql .= implode(",", array_values($fieldList));
				$strsql .= ")";
				$x_result = phpmkr_query($strsql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $strsql;
					phpmkr_query('rollback;', $conn);	 
				 	exit();
				}	
				
				#echo "<br>".$sSql;
				
				}
	

		
						
			
			
		
	phpmkr_query('commit;', $conn);	 

	return true;	
		
	
	
	
	}
?>