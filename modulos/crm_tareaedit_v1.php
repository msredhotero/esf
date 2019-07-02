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
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  ../login.php");
	exit();
}
?>
<?php
function calculaSemanas($conn, $x_fecha_tarea, $x_dia_zona, $x_promotor_id, $x_caso_id, $x_tarea_id){
	// esta funcion sirve para saber con que fecha se guardara la tarea del CRM en la lista de las tareas que se le mostraran diariamente a los promotores.
	 #1.- contar el numero de tareas de la zona que pertenecen al promotor
	 #2.- deben de ser con fecha mayor a la fecha_tarea
	 #3.- al numero resultante lo deividimos entre 25 para saber cuantas semanas ya estan ocupadas
	 #4.- sacamos el modulo de la division ppara saber si la ultima semana ya tiene las 25 tareas completas.. o aun tiene espacio para mas.
	 #5.- regresamos la fecha con la que se debe guardar la tarea en la lista diaria
	 $x_dias_para_agregar = 0;
	 
	 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_promotor WHERE fecha_lista = \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id ";
	 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor A".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
	 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
	 mysql_free_result($rsCuentaTareasDelDia);
	 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	 echo "tareas asignadas para hoy".$sqlCuentaTareasDelDia."<br>";
	 echo "TAREAS ASIGNADAS HOY  PARA ".$x_promotor_id." SON ".$x_tareas_asignadas_de_hoy;
	 
	 if($x_tareas_asignadas_de_hoy < 25){
		 // insertamos la tarea en la lista
		  $x_dias_para_agregar = 0;
		 
		 // else ----> se hace el calculo
		 }else{
			
		$sqlCuentaTareasZona = "SELECT COUNT(*) AS total_tareas_asignadas FROM  tarea_diaria_promotor WHERE fecha_lista > \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id and zona_id = $x_dia_zona ";
	 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
	 $rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona);
	 mysql_free_result($rsCuentaTareasZona);
	 echo "<BR>CUENTA TAREAS ASIGNADAS".$sqlCuentaTareasZona."<BR>";
	 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
	  echo "tareas asignadas por zona".$x_tareas_asignadas_zona_promotor ."<BR>";
			
			
			$x_semanas_llenas = intval($x_tareas_asignadas_zona_promotor/25);
			echo "semanas llenas ".$x_semanas_llenas."<br>";
			
			//$x_modulo_semamas_llenas = 25%$x_tareas_asignadas_zona_promotor;
			echo "modulo semanas llenas ".$x_modulo_semamas_llenas."<br>";
			//kuki
			if($x_semanas_llenas > 0){
				$x_semanas_llenas = $x_semanas_llenas +1; 
				}
			if($x_semanas_llenas == 0){
				 $x_semanas_llenas = 1;
				}
				
				$x_dias_para_nueva_fecha = $x_semanas_llenas * 7; // 7 = a una semana completa..hoy es lunes.. 7 es el sig lunes 
				 $x_dias_para_agregar = $x_dias_para_nueva_fecha;
				 
				#insertamos la tarea; en tareas resagadas
	$x_fe_resago = date("Y-m-d");
	$sqlResago = "INSERT INTO `tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) VALUES (NULL, $x_tarea_id, \"$x_fe_resago\")";
	$rsResago = phpmkr_query($sqlResago,$conn)or die ("error al isertar en resago".phpmkr_error()."sql:".$sqlResago); 
			 
			 }	 
	//1.- agregamos lo dias indicados a la fecha de la lista de tares y agregamos la tarea a la lista dia promotor
	//2.- cambiamos la fecha de vencimiento de la tarea.
	echo "DPA".$x_dias_para_agregar."<br>";
	
	
	
	return $x_dias_para_agregar;
	
	}	
?>
<?php
// Initialize common variables
$x_crm_tarea_id = Null;
$x_crm_caso_id = Null;
$x_crm_tarea_tipo_id = Null;
$x_crm_tarea_prioridad_id = Null;
$x_fecha_registro = Null;
$x_hora_registro = Null;
$x_fecha_ejecuta = Null;
$x_hora_ejecuta = Null;
$x_fecha_recordatorio = Null;
$x_hora_recordatorio = Null;
$x_origen = Null;
$x_destino = Null;
$x_observaciones = Null;
$x_fecha_status = Null;
$x_asunto = Null;
$x_descripcion = Null;
?>
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php include("../php_busca_iniciales_usuario.php");?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	
$x_fecha_limite_pp = date("Y-m-d",strtotime("+2month"));
$x_hoy_pp = date("Y-m-d");
	

$sKey = @$_GET["key"];
if (($sKey == "") || (is_null($sKey))) { $sKey = @$_POST["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {


	foreach( $_POST as $nombre => $valor ){
		$$nombre = $valor;
		}
	// Get fields from form
	
	$x_observaciones = $_POST["x_observaciones_resp"] . "\n\n" . @$_POST["x_observaciones"];
	$x_fecha_status = $currdate;
	
	$x_hora_status = $currtime;


//CV

	
	
	


	if(isset($_POST["x_incobrable"])){
		$x_incobrable = 1;
	}else{
		$x_incobrable = 0;		
	}			

	if(isset($_POST["x_negativa"])){
		$x_negativa = 1;
	}else{
		$x_negativa = 0;		
	}			



	$x_tarea_siguiente = $_POST["x_tarea_siguiente"];
	$x_fectarea = $_POST["x_fectarea"];	


}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: casos_view.php?key=$x_crm_caso_id");
	exit();
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: casos_view.php?key=$x_crm_caso_id");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: casos_view_mensaje.php?key=$x_crm_caso_id");
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link href="../crm.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" media="screen" href="styles/vlaCal-v2.1.css" type="text/css" />
<link rel="stylesheet" media="screen" href="styles/vlaCal-v2.1-adobe_cs3.css" type="text/css" />
<link rel="stylesheet" media="screen" href="styles/vlaCal-v2.1-apple_widget.css" type="text/css" />
<script type="text/javascript" src="jslib/mootools-1.2-core-compressed.js"></script>
<script type="text/javascript" src="jslib/vlaCal-v2.1-compressed.js"></script>

	<script type="text/javascript">
		window.addEvent('domready', function() { 
		if(document.getElementById("calendario1")){
			new vlaDatePicker('calendario1', { prefillDate: false }, { style: 'adobe_cs3', offset: { y: 1 }, format: 'd/m/y', ieTransitionColor: '' }); 
		}
			new vlaDatePicker('fectarea', { prefillDate: false }, { style: 'adobe_cs3', offset: { y: 1 }, format: 'd/m/y', ieTransitionColor: '' }); 
		});	

	</script>


</head>

<body>

<script type="text/javascript" src="../ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--


function validaFechaPP(){
	
	//alert("Entro en valida fecha pp");
	EW_this = document.crm_tareaedit;
	validada = 1;
	if (EW_this.x_fecha_pp && !EW_hasValue(EW_this.x_fecha_pp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pp, "TEXT", "La fecha de PP es requerida. "))
		return false;
		validada = 0;
		
}

var fecha_de_la_promesa = EW_this.x_fecha_pp.value;
//alert(fecha_de_la_promesa);
var ma1 = fecha_de_la_promesa.split("/");
var anio_pp =  ma1[2];  
var mes_pp = (ma1[1] -1);
var dia_pp = ma1[0];
//alert(ma1);
var fecha_pp  = new Date(anio_pp,mes_pp,dia_pp) ;
var fecha_max = new Date(EW_this.x_fecha_limite_pp.value);
var fecha_min = new Date(EW_this.x_hoy_pp.value);
//alert ("fecha promsa de pago "+fecha_pp);
//alert("fecha maxima"+fecha_max);
//alert("fecha minima"+fecha_min);

if( validada == 1 && fecha_pp >  fecha_max){
	//alert("entra al primer if");
	alert("La fecha de la promesa de pago NO puede ser mayo a 2 meses");
	validada = 0;
	}
if( validada == 1 && fecha_pp < fecha_min){
	alert("La fecha de la promesa de pago NO puede ser menor a HOY");
	validada = 0;
	}	


//validada = 0;
	if(validada == 1){
		
		EW_this.submit();
		}
	
	}
function EW_checkMyForm(EW_this) {
if (EW_this.x_crm_caso_id && !EW_hasValue(EW_this.x_crm_caso_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_crm_caso_id, "SELECT", "El caso es requerido."))
		return false;
}
if (EW_this.x_crm_tarea_tipo_id && !EW_hasValue(EW_this.x_crm_tarea_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_crm_tarea_tipo_id, "SELECT", "El tipo de tarea es requerido."))
		return false;
}
if (EW_this.x_crm_tarea_prioridad_id && !EW_hasValue(EW_this.x_crm_tarea_prioridad_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_crm_tarea_prioridad_id, "SELECT", "La prioridad es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false; 
}
if (EW_this.x_hora_registro && !EW_hasValue(EW_this.x_hora_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_hora_registro, "TEXT", "La hora de registro es requerida."))
		return false;
}
/*

*/
if (EW_this.x_asunto && !EW_hasValue(EW_this.x_asunto, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_asunto, "TEXT", "El asunto es requerido."))
		return false;
}
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripcion es requerida."))
		return false;
}
if (EW_this.x_dias_espera_new && !EW_hasValue(EW_this.x_dias_espera_new, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dias_espera_new, "TEXT", "Los dias de espera son requeridos."))
		return false;
}

if(EW_this.x_tarea_siguiente && EW_this.x_crm_tarea_status_id.value == 3 && EW_this.x_tarea_siguiente.value != ""){
if (EW_this.x_fectarea && !EW_hasValue(EW_this.x_fectarea, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fectarea, "TEXT", "La fecha de la tarea es requerida."))
	return false;
}
if (EW_this.x_fectarea && !EW_checkeurodate(EW_this.x_fectarea.value)) {
	if (!EW_onError(EW_this, EW_this.x_fectarea, "TEXT", "La Fecha de tarea es incorrecta = dd/mm/yyyy"))
	return false;
}
}



 x_fecha_promesa_pago = EW_this.x_fecha_pp;
return true;
}


function validapp(casotipoid,tareatipoid,tareastatusid){
ew_frm = document.crm_tareaedit;
ew_frm.cmdEditar.disabled = "";
document.getElementById("siguientetarea").className = "TG_hidden";	
document.getElementById("fectareaid").className = "TG_hidden";	
/*
document.getElementById("fecrecid").className = "TG_hidden";
document.getElementById("fecconfid").className = "TG_hidden";	
*/


if(tareastatusid.value == 3){
	
	xvaldat = true;
	//cartas
	if(casotipoid == 3 && (tareatipoid > 7 && tareatipoid < 12)){
		if(Number(ew_frm.x_carta_impresa.value) == 0){
			ew_frm.cmdEditar.disabled = "disabled";
			alert("No se ha impreso la carta.");
			valdat = false;
		}
	}
	
	if(xvaldat){
		document.getElementById("siguientetarea").className = "TG_visible";	
		document.getElementById("fectareaid").className = "TG_visible";	
	}

}
/*
	ew_frm = document.crm_tareaedit;

	if(ew_frm.x_fec_venc.value != ""){

		var strDate1 = ew_frm.x_fecha_pp.value;
		var strDate2 = ew_frm.x_fec_venc.value;
		
		//Start date split to UK date format and add 31 days for maximum dateDiff
		strDate1 = strDate1.split("/");
		starttime = new Date(strDate1[2],strDate1[1]-1,strDate1[0]);
		starttime = new Date(starttime.valueOf());
		
		//End date split to UK date format
		strDate2 = strDate2.split("/");
		endtime = new Date(strDate2[2],strDate2[1]-1,strDate2[0]);
		endtime = new Date(endtime.valueOf());
		
		if(ew_frm.x_orden.value > 1){
			if(starttime > endtime)
			{
				ew_frm.x_crm_tarea_status_id.selectedIndex = 1;
				ew_frm.x_fecha_pp.value = ew_frm.x_fec_venc.value;
				
				alert("La fecha de PP no puede ser mayor a su siguiente vencimiento, se ha ajustado la fecha de PP. El estado de la tarea se ha regresado a Pendiente.");
			}
		}
	}
*/	
}

function carta(url_carta){
	ew_frm = document.crm_tareaedit;
	url_carta = "http://www.financieracrea.com/esf/" +  url_carta + "&key4=" + ew_frm.x_fecha_entrega.value;
	alert(url_carta);
	if(ew_frm.x_fecha_entrega.value != ""){
		window.open (url_carta,"Cartas","status=1,toolbar=0,menubar=1"); 
		ew_frm.x_carta_impresa.value = 1;
	}else{
		alert("No ha indicado la fecha de entrega");
	}
}


function valida_carta(){

	ew_frm = document.crm_tareaedit;

	if(Number(ew_frm.x_carta_impresa.value) == 0){

		ew_frm.x_crm_tarea_status_id.selectedIndex = 1;
		
		alert("No se ha impreso la carta. El estado de la tarea se ha regresado a Pendiente.");

	}

}



function tipotarea(casotipoid,tareatipoid,tarea){
ew_frm = document.crm_tareaedit;
ew_frm.cmdEditar.disabled = "";
document.getElementById("fecrecid").className = "TG_hidden";
document.getElementById("fecconfid").className = "TG_hidden";	

	
	//promesa de pago validar fechas de recordatorio y confirmacion
	//if(casotipoid == 3 && (tareatipoid > 5 && tareatipoid < 7)){
	tv = tarea.value;
	tarea_num = Number(tv.substring(tv.indexOf("|")+1,tv.length));	
	if(tarea_num == 5){
		document.getElementById("fecrecid").className = "TG_visible";
		document.getElementById("fecconfid").className = "TG_visible";	
	}

}


//-->
</script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<form name="crm_tareaedit" id="crm_tareaedit" action="crm_tareaedit_v1.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<input type="hidden" name="x_origen" value="<?php echo $x_origen; ?>">
<input type="hidden" name="x_orden" value="<?php echo $x_orden; ?>">
<input type="hidden" name="x_crm_caso_tipo_id" value="<?php echo $x_crm_caso_tipo_id; ?>">
<input type="hidden" name="x_crm_tarea_tipo_id" value="<?php echo $x_crm_tarea_tipo_id;?>"/>
<input type="hidden" name="x_fecha_limite_pp" value="<?php echo $x_fecha_limite_pp;?>"/> 
<input type="hidden" name="x_hoy_pp" value="<?php echo $x_hoy_pp;?>"/>


<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center" >  
   <tr>
   <td colspan="7"><p style="padding-left:10px"><span class="phpmaker"><br><br><a href="casos_view.php?key=<?php echo $x_crm_caso_id; ?>">Regresar al detalle del Caso</a></span></p></td>   
  </tr><tr>
    <td width="15%" class="txt_negro_medio"><input type="hidden" name="x_crm_tarea_id" value="<?php echo $x_crm_tarea_id; ?>" />
      <input type="hidden" name="x_crm_caso_id" value="<?php echo $x_crm_caso_id; ?>" /></td>
    <td width="3%" align="center" valign="middle">&nbsp;</td>
    <td width="29%" class="txt_datos_azul">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="15%" class="txt_negro_medio">&nbsp;</td>
    <td width="3%" align="center" valign="middle">&nbsp;</td>
    <td width="34%" class="txt_datos_azul">&nbsp;</td>
  </tr>  
  <tr>
    <td colspan="7" class="txt_negro_medio">    
    
      <?php if($x_crm_caso_tipo_id == 3){ ?>
      <?php if($x_crm_tarea_tipo_id == 5){ //promesas de pago?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">
        <tr>
          <td  colspan="7"class="txt_negro_medio">SOLICITAR PROMESA DE PAGO</td>
          
          </tr>
          <tr>
          <td  colspan="7"class="txt_negro_medio">&nbsp;</td>
          
          </tr>
        <tr>
          <td width="15%" class="txt_negro_medio">Promesa de Pago</td>
          <td width="3%">&nbsp;</td>
          <td width="29%" class="txt_datos_azul">&nbsp;<input name="x_fecha_pp" type="text" id="calendario1" value="<?php echo FormatDateTime($x_fecha_pp,7); ?>" size="12" maxlength="10"  /></td>
          <td width="1%">&nbsp;</td>
          <td width="15%" class="txt_negro_medio">Monto de la PP:</td>
          <td width="3%">&nbsp;</td>
          <td width="34%" class="txt_datos_azul"><input type="text" name="x_monto_pp" id="x_monto_pp" value="" /></td>
          </tr>f
        <tr>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          </tr>
        <tr>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_negro_medio">Agregar telefono </td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          </tr>
        <tr>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">
            <?php if($_SESSION["crm_UserRolID"] == 1 || $_SESSION["crm_UserRolID"] == 9){ ?>
            <input name="x_negativa" type="checkbox" id="x_negativa" value="1" <?php if($x_negativa == 1){echo "checked='checked'"; } ?> />&nbsp;Negativa de Pago
            <?php } ?></td>
          <td>&nbsp;</td>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul"><a href="php_agrega_telefono.php?key=<?=$x_credito_id;?>&key2=<?=$x_solicitud_id?>&key3=1" target="_blank">Agregar Telefono</a></td>
          </tr>
        <tr>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_negro_medio">&nbsp;</td>
          <td>&nbsp;</td>
          <td class="txt_datos_azul">&nbsp;</td>
          </tr>
        </table>
      
      <?php } ?>
      
      
      <?php if(( $x_orden == 4 and $x_crm_tarea_tipo_id == 8 ) || ($x_orden == 5 and $x_crm_tarea_tipo_id == 5 ) || ($x_orden == 8 and $x_crm_tarea_tipo_id == 9 ) || ($x_orden == 9 and $x_crm_tarea_tipo_id == 5 ) ||($x_orden == 12 and $x_crm_tarea_tipo_id == 10 )  || ($x_orden == 13 and $x_crm_tarea_tipo_id == 5 ) || ($x_orden == 20 and $x_crm_tarea_tipo_id == 12 ) || ($x_orden == 24 and $x_crm_tarea_tipo_id == 5 ) ){ //cartas ?>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
        <tr>
          <td width="15%" class="txt_negro_medio">Fecha de Entrega</td>
          <td width="3%">&nbsp;</td>
          <td width="29%" class="txt_datos_azul"><input name="x_fecha_entrega" type="text" id="calendario1" value="<?php echo FormatDateTime($x_fecha_entrega,7); ?>" size="12" maxlength="10" /></td>
          <td width="1%">&nbsp;</td>
          <td class="txt_negro_medio">&nbsp;    
            <?php 	
	//seleccionamos si el credito tiene o no garantia liquida
	$sqlGaratia =  "SELECT * FROM credito WHERE credito_id = $x_credito_id ";
	$rsGarantia =  phpmkr_query($sqlGaratia,$conn) or die ("Error la seleccionar los dtaos del credito".phpmkr_error()	."sql:".$sqlGaratia);
	$rowGarantia =  phpmkr_fetch_array($rsGarantia);
	$x_garantia_liquida =  $rowGarantia["garantia_liquida"];
	if($x_crm_tarea_tipo_id == 8 || ($x_orden == 5 and $x_crm_tarea_tipo_id == 5 )){ // carta 1 
	if ($x_garantia_liquida > 0){
    	$x_carta_url = "'php_imprime_carta_1_garantia.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";
	}else{
    	$x_carta_url = "'php_imprime_Carta_1.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";	
	}
	}
	if($x_crm_tarea_tipo_id == 9 || ($x_orden == 9 and $x_crm_tarea_tipo_id == 5 )){ // carta 2 
    	if ($x_garantia_liquida > 0){
		
    	$x_carta_url = "'php_imprime_Carta_2_garantia.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";
	}else{
    	$x_carta_url = "'php_imprime_Carta_2.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";	
	}	
	}
	if($x_crm_tarea_tipo_id == 10 || ($x_orden == 13 and $x_crm_tarea_tipo_id == 5 )){ // carta 3
    	if ($x_garantia_liquida > 0){
    	$x_carta_url = "'php_imprime_Carta_3_garantia.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";
	}else{
    	$x_carta_url = "'php_imprime_Carta_3.php?credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";	
	}
	}
	if($x_crm_tarea_tipo_id == 11 || ($x_orden == 20 and $x_crm_tarea_tipo_id == 12 )){ // carta D 
    	if ($x_garantia_liquida > 0){
    	$x_carta_url = "'php_demanda_exhorto_print.php?solicitud_id=$x_solicitud_id&credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";
	}else{
    	$x_carta_url = "'php_demanda_exhorto_print.php?solicitud_id=$x_solicitud_id&credito_id=$x_credito_id&caso_id=$x_crm_caso_id'";	
	}		
	}

	?>
            <input type="button" name="x_carta" value="Imprimir Carta" onclick="carta(<?php echo $x_carta_url; ?>)" />
            &nbsp;&nbsp;
            <input type="hidden" name="x_carta_impresa" id="x_carta_impresa" value="0" />
            
            </td>
          </tr>
        </table>
      
      <?php } ?>
      
      <?php if ($x_crm_tarea_tipo_id == 6){?>
		  <tr>
          <td  colspan="7"class="txt_negro_medio">RECORDATORIO DE PROMESA DE PAGO</td>
          
          </tr>
          <tr>
          <td  colspan="7"class="txt_negro_medio">&nbsp;</td>
          
          </tr>
		  
		  <?php }?>
      <?php if($x_crm_tarea_tipo_id == 7){ //confirmacion de pago ?>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
        <tr>
          <td width="15%" class="txt_negro_medio">Pago completo</td>
          <td width="3%">&nbsp;</td>
          <td class="txt_datos_azul"><input type="radio" name="x_pago_completo" id="x_pago_completo" value="1" <?php if($x_pago_completo == 1){echo "checked='checked'"; } ?> /> 
            SI 
            <input type="radio" name="x_pago_completo" id="x_pago_completo" value="2" <?php if($x_pago_completo == 2){echo "checked='checked'"; } ?> /> 
            INCOMPLETO 
            <input type="radio" name="x_pago_completo" id="x_pago_completo" value="3" <?php if($x_pago_completo == 3){echo "checked='checked'"; } ?> />
            NO PAGO </td>
          <td width="34%" class="txt_datos_azul">&nbsp;
            </td>
          </tr>
        </table>
      
      <?php } ?>
      
      
      <?php if($x_crm_tarea_tipo_id == 13){ //confirmacion de pago ?>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
        <tr>
          <td width="15%" class="txt_negro_medio">Resultado Demanda</td>
          <td width="3%">&nbsp;</td>
          <td width="29%" class="txt_datos_azul"><input type="radio" name="x_resultado_demanda" id="x_resultado_demanda" value="1" <?php if($x_resultado_demanda == 1){echo "checked='checked'"; } ?> /> 
            SI 
            <input type="radio" name="x_resultado_demanda" id="x_resultado_demanda2" value="2" <?php if($x_resultado_demanda == 2){echo "checked='checked'"; } ?> /> 
            NO</td>
          <td width="1%">&nbsp;</td>
          <td width="15%" class="txt_negro_medio"></td>
          <td width="3%">&nbsp;
            </td>
          <td width="34%" class="txt_datos_azul">&nbsp;
            </td>
          </tr>
        </table>
      
      <?php } ?>
      
      
      
      <?php if($x_crm_tarea_tipo_id == 14){ //fecha cita actuario ?>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
        <tr>
          <td width="15%" class="txt_negro_medio">Fecha Cita</td>
          <td width="3%">&nbsp;</td>
          <td width="29%" class="txt_datos_azul">
            <input name="x_fecha_cita" type="text" id="calendario1" value="<?php echo FormatDateTime($x_fecha_cita,7); ?>" size="12" maxlength="10" />
            </td>
          <td width="1%">&nbsp;</td>
          <td width="15%" class="txt_negro_medio"></td>
          <td width="3%">&nbsp;
            </td>
          <td width="34%" class="txt_datos_azul">&nbsp;
            </td>
          </tr>
        </table>
      
      <?php } ?>
      
      
      
      <?php if($_SESSION["crm_UserRolID"] == 1 || $_SESSION["crm_UserRolID"] == 9){ ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
        <tr>
          <td width="15%" class="txt_negro_medio">Incobrable</td>
          <td width="3%">&nbsp;</td>
          <td width="29%" class="txt_datos_azul"><input name="x_incobrable" type="checkbox" id="x_incobrable" value="1" <?php if($x_incobrable == 1){echo "checked='checked'"; } ?> /></td>
          <td width="1%">&nbsp;</td>
          <td width="15%" class="txt_negro_medio"></td>
          <td width="3%">&nbsp;
            </td>
          <td width="34%" class="txt_datos_azul">&nbsp;
            </td>
          </tr>
        </table>
      <?php } ?>
      
      <?php } ?>
      
      
      
      
      
      
      </td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="txt_negro_medio">Bitacora:</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul"><textarea name="x_observaciones_act" cols="34" rows="8" id="x_observaciones" disabled="disabled" ><?php echo htmlspecialchars(@$x_bitacora) ?></textarea>
	<input type="hidden" name="x_observaciones_resp" value="<?php echo @$x_bitcora; ?>"  />
    </td>
    <td>&nbsp;</td>
    <td align="left" valign="top" class="txt_negro_medio">Nueva Observaci&oacute;n:</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td align="left" valign="top" class="txt_datos_azul"><textarea name="x_observaciones" cols="34" rows="8" id="x_observaciones2"></textarea></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td align="left" valign="top" class="txt_datos_azul">
	<input type="hidden" name="x_crm_tarea_status_id"  id="x_crm_tarea_status_id" value="3" /></td>
    <td>&nbsp;</td>
    <td colspan="3"  class="txt_negro_medio" >

</td>
    </tr>
  <tr id="fectareaid" class="TG_visible">
    <td height="100" class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td colspan="3" class="txt_datos_azul" align="right"><?php if(($x_crm_tarea_tipo_id == 100) || ($x_crm_tarea_tipo_id == 200)){?><p>Mandar a siguiente tarea</p><?php }?></td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul"><?php if(($x_crm_tarea_tipo_id == 105) || ($x_crm_tarea_tipo_id == 106)){?><select name="x_manda_carta" >
      <option value="0">Seleccione</option>
      <option value="1">Carta 1</option>
      <option value="2">Carta 2</option>
      <option value="3">Carta 3</option>
      <option value="4">Demanda</option>
      
      
      </select><?php }?></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul"><input type="button" name="cmdEditar" id="cmdEditar" value="GUARDAR" onmouseover="javascript: this.style.cursor='pointer'"  onclick="validaFechaPP();" /></td>
    <td>&nbsp;</td>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;    
    </td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="txt_negro_medio">&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td class="txt_datos_azul">&nbsp;</td>
  </tr>
  </table>
<p>
<p>
</form>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `crm_tarea`";
	$sSql .= " WHERE `crm_tarea_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_crm_tarea_id"] = $row["crm_tarea_id"];
		
		//echo "crm_tarea_id ".$GLOBALS["x_crm_tarea_id"]."<br>";
		$GLOBALS["x_crm_caso_id"] = $row["crm_caso_id"];
		$GLOBALS["x_crm_tarea_tipo_id"] = $row["crm_tarea_tipo_id"];
		$GLOBALS["x_crm_tarea_status_id"] = $row["crm_tarea_status_id"];		
		$GLOBALS["x_crm_tarea_prioridad_id"] = $row["crm_tarea_prioridad_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_hora_registro"] = $row["hora_registro"];
		$GLOBALS["x_fecha_ejecuta"] = $row["fecha_ejecuta"];
		$GLOBALS["x_hora_ejecuta"] = $row["hora_ejecuta"];
		$GLOBALS["x_fecha_recordatorio"] = $row["fecha_recordatorio"];
		$GLOBALS["x_hora_recordatorio"] = $row["hora_recordatorio"];
		$GLOBALS["x_origen_rol"] = $row["origen_rol"];
		$GLOBALS["x_destino_rol"] = $row["destino_rol"];
		$GLOBALS["x_origen"] = $row["origen"];
		$GLOBALS["x_destino"] = $row["destino"];		
		$GLOBALS["x_observaciones"] = $row["observaciones"];
		$GLOBALS["x_fecha_status"] = $row["fecha_status"];
		$GLOBALS["x_asunto"] = $row["asunto"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_orden"] = $row["orden"];		


		$sSqlWrk = "
		SELECT 
			crm_caso_tipo_id, credito_id, solicitud_id
		FROM 
			crm_caso
		WHERE 
			crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
		";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$GLOBALS["x_crm_caso_tipo_id"] = $datawrk["crm_caso_tipo_id"];	
		//echo "caso id ".$GLOBALS["x_crm_caso_id"]."<br>";
		$GLOBALS["x_credito_id"] = $datawrk["credito_id"];													
		$GLOBALS["x_solicitud_id"] = $datawrk["solicitud_id"];	
		//echo "solcicitud_id = ".$GLOBALS["x_solicitud_id"]."<br>";
		//echo "credito_id = ".$GLOBALS["x_credito_id"]."<br>";
		phpmkr_free_result($rswrk);


		if(empty($GLOBALS["x_solicitud_id"])){
			//echo "sol id vacio<br>";
			$sSqlWrk = "
			SELECT 
				solicitud_id
			FROM 
				credito 
			WHERE 
				credito_id = ".$GLOBALS["x_credito_id"]."
			";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				//echo $sSqlWrk ."<br>";
			$datawrk = phpmkr_fetch_array($rswrk);
			$GLOBALS["x_solicitud_id"] = $datawrk["solicitud_id"];											
			//echo "sol id lleno".$GLOBALS["x_solicitud_id"]."<br>";
			phpmkr_free_result($rswrk);
		}
		
		
		// seleccionamos el formato de la solitud para saber si es una solcitud con formato nuevo o es una solcitud antigua
		
		$sqlFormato = "SELECT formato_nuevo FROM solicitud WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
		$rsFormato = phpmkr_query($sqlFormato, $conn) or die ("Error al seleccionar el formato de la solicitud". phpmkr_error(). "sql:".$sqlFormato);
		$rowFormato = phpmkr_fetch_array($rsFormato);
		$x_formato_nuevo = $rowFormato["formato_nuevo"];
		
				
		$sqlTC = "SELECT credito_tipo_id,importe,fecha_otrogamiento,plazo_id,num_pagos,forma_pago_id, cliente_num FROM credito WHERE credito_id = ".$GLOBALS["x_credito_id"]." ";
		$response = phpmkr_query($sqlTC, $conn) or die("error a seleccioanr tipo de credito".phpmkr_error()."sql:".$sqlTC);
		$rowtc = phpmkr_fetch_array($response);
		$GLOBALS["x_tipo_credito_id"] = $rowtc["credito_tipo_id"];
		//echo "tipo creito _id -". $GLOBALS["x_tipo_credito_id"]."<br>";
		$GLOBALS["x_importe_total_credito"] = $rowtc["importe"];
		$GLOBALS["x_fecha_otrogamiento"] = $rowtc["fecha_otrogamiento"];
		$GLOBALS["x_plazo_id"] = $rowtc["plazo_id"];
		$GLOBALS["x_num_pagos"] = $rowtc["num_pagos"];
		$GLOBALS["x_forma_pago_id"] = $rowtc["forma_pago_id"];
		$GLOBALS["x_cliente_num"] = $rowtc["cliente_num"];
	//	echo "---".$GLOBALS["x_cliente_num"];
		//echo "credito id. =".$GLOBALS["x_credito_id"]."<br>";
		//echo "x_forma_pago_id =".$GLOBALS["x_forma_pago_id"]."<br>";
		//echo "<br>importe del credito<br>".$GLOBALS["x_importe_total_credito"]."<br>";
		//echo "tipo_credtio".$rowtc["credito_tipo_id"]."";
		phpmkr_free_result($response);
		
		$GLOBALS["x_link_view_a"] = "";
		if($GLOBALS["x_tipo_credito_id"] == 1){
			
			if($x_formato_nuevo == 0){				
				$GLOBALS["x_link_view_a"] = "php_solicitudedit.php";
				$x_link_print = "php_solicitud_print.php";
				}else if($x_formato_nuevo == 1){	
							
					$GLOBALS["x_link_view_a"] = "php_solicitudviewIndividual.php";
					$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudIndividualP_print.php";
					}
			}else if($GLOBALS["x_tipo_credito_id"] == 2){
				
				if($x_formato_nuevo == 0){				
					$x_link_view = "";
					$x_link_print = "";
					}else if($x_formato_nuevo == 1){						
						$GLOBALS["x_link_view_a"] = "php_solicitudeditSolidario.php"; // existe esta opcion
						$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudSolidario_print.php";
						}
				}else if($GLOBALS["x_tipo_credito_id"]  == 3){
					 if($x_formato_nuevo == 1){							
							$GLOBALS["x_link_view_a"] = "php_solicitudviewMaquinaria.php";
							$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudMaquinaria_print.php";
							}
					}else if($GLOBALS["x_tipo_credito_id"]  == 4){
						 if($x_formato_nuevo == 1){								
								$GLOBALS["x_link_view_a"]= "php_solicitudviewPyme.php";
								$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudPYME_print.php";
								}
						}
		
		
		
		
		if(!empty($GLOBALS["x_cliente_num"]) && $GLOBALS["x_cliente_num"] != "" ){
		$sSqlCrL ="SELECT credito_num FROM credito WHERE cliente_num =".$GLOBALS["x_cliente_num"]." AND credito_status_id = 3";
		$responseCrL = phpmkr_query($sSqlCrL, $conn) or die ("error al seleccionar  los credito liquidados".phpmkr_error()."sql:".$sSqlCrL);
		//echo "sql busca creditos".$sSqlCrL;
		while($rowc = phpmkr_fetch_array($responseCrL)){
			$x_lista_creditos_liquidados .= $rowc["credito_num"].", ";			
			}
			phpmkr_free_result($responseCrL);
			$GLOBALS["creditos_liquidados"]= substr($x_lista_creditos_liquidados,0,strlen($x_lista_creditos_liquidados)-2);
		
		$sSqlForP = "SELECT valor FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"]." ";
		$rsForP = phpmkr_query($sSqlForP,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlForP);
		$rowForP = phpmkr_fetch_array($rsForP);
		$x_forma_pago = $rowForP["valor"];
		phpmkr_free_result($rsForP);	
		}
		include_once("datefunc.php");
		$GLOBALS["x_dias_diferencia_mayor"] = 0; 
		$sqlVencimientos = "SELECT * FROM vencimiento WHERE credito_id = ".$GLOBALS["x_credito_id"]." AND vencimiento_status_id  = 2 ORDER BY `vencimiento`.`vencimiento_id` ASC 
";
		$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		$x_inicio = 1;
		while ($rowvenc = phpmkr_fetch_array($responseVenc)){
			$x_vencimiento_id = $rowvenc["vencimiento_id"];
			//echo "vencimeinto _id =".$x_vencimiento_id."<br>";
			$x_credito_id = $rowvenc["credito_id"];
			$x_fecha_vencimiento = $rowvenc["fecha_vencimiento"];
			//echo "fecha venciamiento".$x_fecha_vencimiento."<br>";
			$sSqlFP = "SELECT  fecha_pago  FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id WHERE recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
			$responseFP = phpmkr_query($sSqlFP,$conn) or die("Erorr al seleccionar la fecha de pago".phpmkr_error()."sql:".$sSqlFP);
			$rowFP = phpmkr_fetch_array($responseFP);
			$x_fecha_pago = $rowFP["fecha_pago"];
			//echo "fecha pago =".$x_fecha_pago."<br>";
			if($x_inicio == 1){
			if($x_fecha_vencimiento >= $x_fecha_pago){
				//pago anticipado
				//echo "fecha de pago menor  o iagual a fecha de venciemiento...pago anticipado<br>";
				$x_fecha_comparacion = $x_fecha_vencimiento;				
				}else{
					$x_fecha_comparacion = $x_fecha_pago;
					// calcular dias de atraso
					//echo "fecha pago mayor a la fecha  a fecha vencimiento<br>";
					$par = "y";
					$bol = "false";
					//$x_dias_diferencia = datediff($par,$x_fecha_vencimiento,$x_fecha_pago);
					//echo "dias de diferencia =".$x_dias_diferencia."<br>";
					
					if($x_dias_diferencia > $GLOBALS["x_dias_diferencia_mayor"] ){
						$GLOBALS["x_dias_diferencia_mayor"] =  $x_dias_diferencia;
						//echo "dias de diferencia mayor =".$GLOBALS["x_dias_diferencia_mayor"]."<br>";
						}
					}				
			}
			if($x_inicio == 2){
				//echo "entro a inicio dos<br>";
			// se comparan los demas vencimientos menos el priemro
			$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_comparacion));
			$temptime = DateAdd('w',$x_forma_pago,$temptime);
			$x_fecha_comparacion = strftime('%Y-%m-%d',$temptime);
			$x_dia = strftime('%A',$temptime);

			//Validar domingos
			if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_act);
			$temptime = DateAdd('w',1,$temptime);
			$x_fecha_comparacion = strftime('%Y-%m-%d',$temptime);
			}
			
			//echo "fecha pago comparacion=".$x_fecha_comparacion."<br>";
			
			if($x_fecha_pago < $x_fecha_comparacion){
				// el vencimeinto se pago antes
				$x_dias_diferencia = 0;
				//echo "fecha pago vs fecha comparacion la fecha de pago es menor pago anticipado<br>";
				}else if($x_fecha_pago > $x_fecha_comparacion){
					// el vencimiento tiene dias de atraso
					$par = "y";
					$x_dias_diferencia = datediff($par,$x_fecha_comparacion,$x_fecha_pago);
						//echo "dias de diferencia ="	.$x_dias_diferencia."<br>";
					}
			if($x_dias_diferencia > $GLOBALS["x_dias_diferencia_mayor"] ){
						$GLOBALS["x_dias_diferencia_mayor"] =  $x_dias_diferencia;	
						
						}
			//echo "dias mayor".$GLOBALS["x_dias_diferencia_mayor"]."<br> ";
			}// fin if $x_fecha_comparacion==2
			
			
			
			
					
					
			$x_inicio = 2;
			}
		phpmkr_free_result($responseVenc);
		
		if($GLOBALS["x_dias_diferencia_mayor"] == 0){
			$GLOBALS["x_tipo_cliente"] = "BUENO";
			}else if($GLOBALS["x_dias_diferencia_mayor"] < 11){
				$GLOBALS["x_tipo_cliente"] = "REGULAR";
				}else if($GLOBALS["x_dias_diferencia_mayor"]< 31){
					$GLOBALS["x_tipo_cliente"] = "ACEPTABLE";
					}else{
						$GLOBALS["x_tipo_cliente"] = "MALO";
						}
		//echo "Tipo de cliente :".$GLOBALS["x_tipo_cliente"]."<br>" ;
		
		
		
		if($GLOBALS["x_tipo_cliente"]== "BUENO"){
			if(($GLOBALS["x_importe_total_credito"] >= 3000) && ($GLOBALS["x_importe_total_credito"]<= 9999)){
				$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 3000;
				//echo "importe mayor  de 3 a 9 + 3000<br>";
				}else if(($GLOBALS["x_importe_total_credito"] >= 10000) && ($GLOBALS["x_importe_total_credito"]<= 15999)){
					$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 4000;
					//echo "importe mayor  de 10 a 15 + 4000<br>";
					}else if(($GLOBALS["x_importe_total_credito"] >= 16000) && ($GLOBALS["x_importe_total_credito"]<= 30999)){
						$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 5000;
						//echo "importe mayor  de 16 a 30 + 5000<br>";
						}else if(($GLOBALS["x_importe_total_credito"] >= 31000) && ($GLOBALS["x_importe_total_credito"]<= 50999)){
						//	echo "importe mayor  de 31 a 50 + 7000<br>";
						$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 7000;	
							
							}else if(($GLOBALS["x_importe_total_credito"] >= 51000) && ($GLOBALS["x_importe_total_credito"]<= 99999)){
								$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 10000;
								//echo "importe mayor  de 51 a 99 + 10000<br>";
								}else{
									$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 20000;									
									//echo "importe mayor  de 100 + 20000<br>";
									}
			
			}else if($GLOBALS["x_tipo_cliente"] == "REGULAR"){
						if(($GLOBALS["x_importe_total_credito"] >= 3000) && ($GLOBALS["x_importe_total_credito"]<= 9999)){
							$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 1000;
						//echo "importe mayor  de 3 a 9 + 1000<br>";
						}else if(($GLOBALS["x_importe_total_credito"] >= 10000) && ($GLOBALS["x_importe_total_credito"]<= 15999)){
							$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 2000;
							//echo "importe mayor  de 10 a 15 + 2000<br>";
							
							}else if(($GLOBALS["x_importe_total_credito"] >= 16000) && ($GLOBALS["x_importe_total_credito"]<= 30999)){
								$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 3000;
							//echo "importe mayor  de 16 a 30 + 3000<br>";
								}else if(($GLOBALS["x_importe_total_credito"] >= 31000) && ($GLOBALS["x_importe_total_credito"]<= 50999)){
									
								$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 5000;	
									//echo "importe mayor  de 31 a 50 + 5000<br>";
									}else if(($GLOBALS["x_importe_total_credito"] >= 51000) && ($GLOBALS["x_importe_total_credito"]<= 99999)){
										$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 7000;
										//echo "importe mayor  de 51 a 99 + 7000<br>";
										}else {
											$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"] + 10000;									
											//echo "importe mayor  de 100 + 10000<br>";
											}
				
				
				}else if($GLOBALS["x_tipo_cliente"] == "ACEPTABLE"){
					
					$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"];
					}else{
						$GLOBALS["x_importe_total_credito"] = $GLOBALS["x_importe_total_credito"];
						
						}
						
						//echo "importe del credito mas el aunmento<br>".$GLOBALS["x_importe_total_credito"]."";
		//aqui se comparan las fechas del vencie
		
		
		if($GLOBALS["x_tipo_credito_id"] == 2){
			// se trata de un credito solidario la solcitud que se debe mostrar es la del representante del grupo
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			$GLOBALS["x_links_solcitudes"] = "";
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				// todas las solcitudes 
				
				
								
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 2){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					
					
					
					}
				
				$x_cont_g++;
				}		
			
			phpmkr_free_result($rowGrupo);
			
			}
		
		
		
		
		
		
		
		
		
		
		
		
		
		

		if($GLOBALS["x_crm_caso_tipo_id"] == 3){

			$sSqlWrk = "
			SELECT 
				*
			FROM 
				crm_tarea_cv
			WHERE 
				crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"]."
			";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
			$datawrk = phpmkr_fetch_array($rswrk);
			$GLOBALS["x_fecha_pp"] = $datawrk["promesa_pago"];											
			$GLOBALS["x_pp_por"] = $datawrk["pp_por"];														
			$GLOBALS["x_tel_titular"] = $datawrk["tel_titular"];
			$GLOBALS["x_tel_aval"] = $datawrk["tel_aval"];			
			$GLOBALS["x_fecha_entrega"] = $datawrk["fecha_entrega"];			
			$GLOBALS["x_pago_completo"] = $datawrk["pago_completo"];			
			$GLOBALS["x_resultado_demanda"] = $datawrk["resultado_demanda"];						
			$GLOBALS["x_incobrable"] = $datawrk["incobrable"];					
			$GLOBALS["x_fecha_cita"] = $datawrk["fecha_cita"];					
			$GLOBALS["x_negativa"] = $datawrk["negativa_pago"];					

			phpmkr_free_result($rswrk);

		
		}
		
	
		if(!empty($GLOBALS["x_credito_id"])){
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$GLOBALS["x_credito_id"]."
			LIMIT 1
			";
	
		}else{
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				solicitud_comment
			WHERE 
				solicitud_id = ".$GLOBALS["x_solicitud_id"]."
			LIMIT 1
			";
	
		}
	
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$GLOBALS["x_bitacora"] = $datawrk["comentario_int"];
		@phpmkr_free_result($rswrk);

		$sSqlWrk = "select promotor.nombre_completo from promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$GLOBALS["x_promotor"] = $datawrk["nombre_completo"];
		@phpmkr_free_result($rswrk);






	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($sKey,$conn)
{
	
	
	echo "x_pp_por ".$GLOBALS["x_pp_por"]."<br>";

	// Open record
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `crm_tarea`";
	$sSql .= " WHERE `crm_tarea_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$EditData = false; // Update Failed
	}else{
/*		
		$theValue = ($GLOBALS["x_crm_caso_id"] != "") ? intval($GLOBALS["x_crm_caso_id"]) : "NULL";
		$fieldList["`crm_caso_id`"] = $theValue;
		
		$theValue = ($GLOBALS["x_crm_tarea_tipo_id"] != "") ? intval($GLOBALS["x_crm_tarea_tipo_id"]) : "NULL";
		$fieldList["`crm_tarea_tipo_id`"] = $theValue;
*/
		$GLOBALS["x_crm_tarea_status_id"] =  3;
		$theValue = ($GLOBALS["x_crm_tarea_status_id"] != "") ? intval($GLOBALS["x_crm_tarea_status_id"]) : "NULL";
		$fieldList["`crm_tarea_status_id`"] = $theValue;


		$theValue = ($GLOBALS["x_crm_tarea_prioridad_id"] != "") ? intval($GLOBALS["x_crm_tarea_prioridad_id"]) : "NULL";
		$fieldList["`crm_tarea_prioridad_id`"] = $theValue;


/*
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_hora_registro"]) : $GLOBALS["x_hora_registro"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`hora_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_ejecuta"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_ejecuta"]) . "'" : "NULL";
		$fieldList["`fecha_ejecuta`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_hora_ejecuta"]) : $GLOBALS["x_hora_ejecuta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`hora_ejecuta`"] = $theValue;
*/		
		
		
		$theValue = ($GLOBALS["x_fecha_recordatorio"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_recordatorio"]) . "'" : "NULL";
		$fieldList["`fecha_recordatorio`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_hora_recordatorio"]) : $GLOBALS["x_hora_recordatorio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`hora_recordatorio`"] = $theValue;
/*		
		$theValue = ($GLOBALS["x_origen"] != "") ? intval($GLOBALS["x_origen"]) : "NULL";
		$fieldList["`origen`"] = $theValue;
		$theValue = ($GLOBALS["x_destino"] != "") ? intval($GLOBALS["x_destino"]) : "NULL";
		$fieldList["`destino`"] = $theValue;
*/		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_observaciones"]) : $GLOBALS["x_observaciones"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`observaciones`"] = $theValue;
		
		$theValue = ($GLOBALS["x_fecha_status"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]) . "'" : "NULL";
		$fieldList["`fecha_status`"] = $theValue;
/*		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_asunto"]) : $GLOBALS["x_asunto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`asunto`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
*/
		// update
		$sSql = "UPDATE `crm_tarea` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `crm_tarea_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
		echo "sql update tarea " .$sSql."<br>";



//DATOS GENRALES DEL CASO
$sSqlWrk = "
SELECT 
	crm_caso.crm_caso_tipo_id,
	crm_caso_tipo.descripcion,
	crm_caso.solicitud_id,
	crm_caso.credito_id
	
FROM 
	crm_caso join crm_caso_tipo
	on crm_caso_tipo.crm_caso_tipo_id = crm_caso.crm_caso_tipo_id
WHERE 
	crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_crm_caso_tipo_id = $datawrk["crm_caso_tipo_id"];	
$x_crm_caso_tipo_desc = $datawrk["descripcion"];				
$x_solicitud_id = $datawrk["solicitud_id"];							
$x_credito_id = $datawrk["credito_id"];										
@phpmkr_free_result($rswrk);


//BITACORA DATOS GENRALES
//$x_comentario_bitacora = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_observaciones"]) : $GLOBALS["x_observaciones"];  kuki

$sSqlWrk = "
SELECT nombre
FROM 
	usuario
WHERE 
	usuario_id = ".$_SESSION["php_project_esf_status_UserID"] ."  
	
";
//usuario_id = ".$_SESSION["crm_UserID"]." 

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL1:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_usuario_nombre = $datawrk["nombre"];
@phpmkr_free_result($rswrk);


$sSqlWrk = "
SELECT descripcion
FROM 
	crm_tarea_status
WHERE 
	crm_tarea_status_id = ".$GLOBALS["x_crm_tarea_status_id"]."  
";

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL1:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_tarea_status = $datawrk["descripcion"];
@phpmkr_free_result($rswrk);




include_once("datefunc.php");




if($x_crm_caso_tipo_id < 3){
//ORIGINACION Y SEGUIMIENTO CREDITOS

		$sSqlWrk = "
		SELECT 
			bitacora,
			crm_caso_tipo.descripcion
		FROM 
			crm_caso join crm_caso_tipo
			on crm_caso_tipo.crm_caso_tipo_id = crm_caso.crm_caso_tipo_id
		WHERE 
			crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_bitacora_ant = $datawrk["bitacora"];
		$x_crm_caso_tipo_desc = $datawrk["descripcion"];		
		@phpmkr_free_result($rswrk);
	
		$x_bitacora = $x_bitacora_ant . "\n\n------------------------------\n";
		//$x_bitacora .= "$x_crm_caso_tipo_desc - (".FormatDateTime($GLOBALS["x_fecha_status"],7)." - ".$GLOBALS["x_hora_status"].")";
		//$x_bitacora .= "\n";
		$x_bitacora .=  "\n$x_comentario_bitacora";	
		$x_bitacora .= "\n------------------------------";

		$sSqlWrk = "
		UPDATE
			crm_caso
		SET 
			bitacora = '$x_bitacora'
		WHERE 
			crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
		";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


		//Bitacora solicitud
		$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
		phpmkr_query($sSql, $conn);		
		//GENERA SIGUIENTE TAREA
		if($GLOBALS["x_crm_tarea_status_id"] > 2){

			if($GLOBALS["x_tarea_siguiente"] != ""){
				$x_tarea_siguiente_id = intval(substr($GLOBALS["x_tarea_siguiente"],0,strpos($GLOBALS["x_tarea_siguiente"],"|")));
			}else{
				$x_tarea_siguiente_id = 0;
			}
			if($x_tarea_siguiente_id > 0){
			//si especifico una tarea siguiente
				$sSqlWrk = "
				SELECT 
					orden
				FROM 
					crm_playlist
				WHERE 
					crm_playlist_id = $x_tarea_siguiente_id 
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_orden_sig = $datawrk["orden"];
				@phpmkr_free_result($rswrk);
			
			}else{
			//si no busca siguiente tarea en paylist
				$sSqlWrk = "
				SELECT 
					orden
				FROM 
					crm_playlist
				WHERE 
					crm_caso_tipo_id = $x_crm_caso_tipo_id			
					and orden = ".$GLOBALS["x_orden"]."
	
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_orden_act = $datawrk["orden"];
				@phpmkr_free_result($rswrk);
				
				$x_orden_sig = $x_orden_act + 1;				
			}

				
			//Busca siguiente tarea si no la encuentra cierra el caso
			$sSqlWrk = "
			SELECT 
				*
			FROM 
				crm_playlist
			WHERE 
				crm_caso_tipo_id = $x_crm_caso_tipo_id
				and orden = $x_orden_sig
			ORDER BY crm_playlist_id				
			";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

			$nTotalRecs = phpmkr_num_rows($rswrk);

			if($GLOBALS["x_crm_tarea_status_id"]  == 4){
				$nTotalRecs = 0;
			}



			if($nTotalRecs == 0){
//			total de registro == 0 cierra caso

				$sSqlWrk = "
				SELECT 
					bitacora
				FROM 
					crm_caso
				WHERE 
					crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_bitacora_ant = $datawrk["bitacora"];
				@phpmkr_free_result($rswrk);
			
				$x_bitacora = $x_bitacora_ant . "\n\n";
				$x_bitacora .= "$x_crm_caso_tipo_desc - (".FormatDateTime($GLOBALS["x_fecha_status"],7)." - ".$GLOBALS["x_hora_status"].")";
				$x_bitacora .= "\n";
				$x_bitacora .= "CIERRE DE CASO - $x_usuario_nombre\n\n$x_comentario_bitacora";	


				$sSqlWrk = "
				UPDATE
					crm_caso
				SET 
					crm_caso_status_id = 2,
					fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."',
					bitacora = '$x_bitacora'
				WHERE 
					crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);



				//Bitacora solicitud
				$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
				phpmkr_query($sSql, $conn);

				
			}else{
				
				
				while($datawrk = phpmkr_fetch_array($rswrk)){
					$x_crm_playlist_id = $datawrk["crm_playlist_id"];
					$x_prioridad_id = $datawrk["prioridad_id"];	
					$x_asunto = $datawrk["asunto"];	
					$x_descripcion = $datawrk["descripcion"];		
					$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
					$x_destino_rol_id = $datawrk["destino_id"];							
					$x_orden_new = $datawrk["orden"];						
					$x_dias_espera = $datawrk["dias_espera"];											

					//busca siguiente tarea si ya exsiste nog hace nada
					$sSqlWrk = "
					SELECT 
						*
					FROM 
						crm_tarea
					WHERE 
						crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
						and crm_tarea_tipo_id = $x_tarea_tipo_id
						and crm_tarea_status_id in (1,2)
						
					";
					$rswrk_ext = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
					$nTotalRecs = phpmkr_num_rows($rswrk_ext);
					
					if(intval($nTotalRecs) > 0){
						return true;						
					}
			
					if($x_destino_rol_id == 7){

						if(!empty($x_solicitud_id)){
							$sSqlWrk = "
							SELECT promotor.usuario_id
							FROM 
								solicitud join promotor
								on promotor.promotor_id = solicitud.promotor_id
							WHERE 
								solicitud.solicitud_id = $x_solicitud_id
							LIMIT 1
							";
						}else{
							$sSqlWrk = "
							SELECT promotor.usuario_id
							FROM 
								solicitud join credito
								on credito.solicitud_id = solicitud.solicitud_id join
								promotor on promotor.promotor_id = solicitud.promotor_id
							WHERE 
								credito.credito_id = $x_credito_id
							LIMIT 1
							";
						}

					}else{

						$sSqlWrk = "
						SELECT usuario_id
						FROM 
							usuario
						WHERE 
							usuario.usuario_rol_id = $x_destino_rol_id
						LIMIT 1
						";
						

					}

					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
					$datawrk = phpmkr_fetch_array($rswrk);
					$x_usuario_id = $datawrk["usuario_id"];
					@phpmkr_free_result($rswrk);


					if($x_dias_espera > 0){ //NORMALES
						
						//Fecha Vencimiento
						$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if(strtoupper($x_dia) == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$temptime = strtotime($fecha_venc);
		
						$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
					
						$x_result = phpmkr_query($sSql, $conn);
						
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sSql;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}
						
						
					}else{ //Recordatorios cv
/*

						//Busca tarea actual si dias de espera es 0 no se generan recordatorios
						$sSqlWrk = "
						SELECT 
							*
						FROM 
							crm_playlist
						WHERE 
							crm_caso_tipo_id = $x_crm_caso_tipo_id
							and orden = $x_orden_act
						ORDER BY crm_playlist_id				
						";
						$rswrkrec = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
						$datawrkrec = phpmkr_fetch_array($rswrkrec);
						$x_dias_espera_act = $datawrkrec["dias_espera"];
						@phpmkr_free_result($rswrkrec);

						if($x_dias_espera_act > 0){

							//Fecha Vencimiento
							$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]));	
							$temptime = DateAdd('w',-1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);			
							$x_dia = strftime('%A',$temptime);
							if($x_dia == "SUNDAY"){
								$temptime = strtotime($fecha_venc);
								$temptime = DateAdd('w',1,$temptime);
								$fecha_venc = strftime('%Y-%m-%d',$temptime);
							}
							$temptime = strtotime($fecha_venc);
	
							//recordatorio pago
							$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
						
							$x_result = phpmkr_query($sSql, $conn);
			
	
							$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]));	
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);			
							$x_dia = strftime('%A',$temptime);
							if($x_dia == "SUNDAY"){
								$temptime = strtotime($fecha_venc);
								$temptime = DateAdd('w',1,$temptime);
								$fecha_venc = strftime('%Y-%m-%d',$temptime);
							}
							$temptime = strtotime($fecha_venc);
	
							$x_orden_sig = $x_orden_sig + 1;
	
							$sSqlWrk = "
							SELECT 
								*
							FROM 
								crm_playlist
							WHERE 
								crm_caso_tipo_id = $x_crm_caso_tipo_id
								and orden = $x_orden_sig
							ORDER BY crm_playlist_id				
							";
							$rswrk2 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
							$datawrk2 = phpmkr_fetch_array($rswrk2);
							$x_crm_playlist_id = $datawrk2["crm_playlist_id"];
							$x_prioridad_id = $datawrk2["prioridad_id"];	
							$x_asunto = $datawrk2["asunto"];	
							$x_descripcion = $datawrk2["descripcion"];		
							$x_tarea_tipo_id = $datawrk2["tarea_fuente"];	
							$x_destino_rol_id = $datawrk2["destino_id"];							
							$x_orden_new = $datawrk2["orden"];						
							$x_dias_espera = $datawrk2["dias_espera"];											
	
							//confirmacion pago
							$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
	
							$x_result = phpmkr_query($sSql, $conn);
							@phpmkr_free_result($rswrk2);
							
						}

							
				*/	}
				}
				@phpmkr_free_result($rswrk);
			}
			
		}



}else{
//CARTERA VENCIDA

echo "...cartera vencida<br>";
	//actualiza datos complementarios de tarea
	
	$sSqlWrk = "
	SELECT 
		count(*) as tarea_comp
	FROM 
		crm_tarea_cv
	WHERE 
		crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"]." ";
	$rswrk2 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk2 = phpmkr_fetch_array($rswrk2);
	$x_tarea_comp = $datawrk2["tarea_comp"];
	@phpmkr_free_result($rswrk2);

	$fieldList = NULL;
	
	$theValue = ($GLOBALS["x_fecha_pp"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]) . "'" : "NULL";
	$fieldList["`promesa_pago`"] = $theValue;

	$theValue = ($GLOBALS["x_pp_por"] != "") ? intval($GLOBALS["x_pp_por"]) : "NULL";
	$fieldList["`pp_por`"] = $theValue;

	$theValue = ($GLOBALS["x_tel_titular"] != "") ? intval($GLOBALS["x_tel_titular"]) : "NULL";
	$fieldList["`tel_titular`"] = $theValue;
	$theValue = ($GLOBALS["x_tel_aval"] != "") ? intval($GLOBALS["x_tel_aval"]) : "NULL";
	$fieldList["`tel_aval`"] = $theValue;
	$theValue = ($GLOBALS["x_fecha_entrega"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_entrega"]) . "'" : "NULL";
	$fieldList["`fecha_entrega`"] = $theValue;
	$theValue = ($GLOBALS["x_pago_completo"] != "") ? intval($GLOBALS["x_pago_completo"]) : "NULL";
	$fieldList["`pago_completo`"] = $theValue;
	$theValue = ($GLOBALS["x_resultado_demanda"] != "") ? intval($GLOBALS["x_resultado_demanda"]) : "NULL";
	$fieldList["`resultado_demanda`"] = $theValue;
	$theValue = ($GLOBALS["x_fecha_cita"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_cita"]) . "'" : "NULL";
	$fieldList["`fecha_cita`"] = $theValue;
	$theValue = ($GLOBALS["x_negativa"] != "") ? intval($GLOBALS["x_negativa"]) : "NULL";
	$fieldList["`negativa_pago`"] = $theValue;
	$theValue = ($GLOBALS["x_incobrable"] != "") ? intval($GLOBALS["x_incobrable"]) : "NULL";
	$fieldList["`incobrable`"] = $theValue;


	if($x_tarea_comp == 0){
		$fieldList["`crm_tarea_id`"] = $GLOBALS["x_crm_tarea_id"];
		
		$sSql = "INSERT INTO `crm_tarea_cv` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
		phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
//		$x_solicitud_id = mysql_insert_id();

	}else{

		$sSql = "UPDATE `crm_tarea_cv` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `crm_tarea_id` =". $GLOBALS["x_crm_tarea_id"];
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


	}
		
	$sSqlWrk = "
	SELECT 
		count(*) as tarea_monto_pago
	FROM 
		crm_tarea_cv_monto
	WHERE 
		crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"]."
	";
	$rswrk2 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk2 = phpmkr_fetch_array($rswrk2);
	$x_tarea_monto_pago = $datawrk2["tarea_monto_pago"];
	@phpmkr_free_result($rswrk2);
	$fieldList = NULL;
	$theValue = ($GLOBALS["x_monto_pp"] != "") ? doubleval($GLOBALS["x_monto_pp"]) : "NULL";
	$fieldList["`monto`"] = $theValue;

	if($x_tarea_comp == 0){
		$fieldList["`crm_tarea_id`"] = $GLOBALS["x_crm_tarea_id"];
		
		$sSql = "INSERT INTO `crm_tarea_cv_monto` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
		phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
//		$x_solicitud_id = mysql_insert_id();

	}else{

		$sSql = "UPDATE `crm_tarea_cv_monto` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `crm_tarea_id` =". $GLOBALS["x_crm_tarea_id"];
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


	}


	//datos generales de la tarea
	$sSqlWrk = "
	SELECT 
		*	
	FROM 
		crm_tarea
	WHERE 
		crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"]."
	";

	$rswrk2 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
	$datawrk2 = phpmkr_fetch_array($rswrk2);
	$x_orden_act = $datawrk2["orden"];
	$x_fecha_ejecuta = $datawrk2["fecha_ejecuta"];
	$x_hora_ejecuta = $datawrk2["hora_ejecuta"];
	$x_fecha_recordatorio = $datawrk2["fecha_recordatorio"];
	$x_hora_recordatorio = $datawrk2["hora_recordatorio"];
	$x_origen_rol_id = $datawrk2["origen_rol"];
	$x_origen = $datawrk2["origen"];
	$x_destino_rol_id = $datawrk2["destino_rol"];
	$x_destino = $datawrk2["destino"];
	$x_observaciones = $datawrk2["observaciones"];
	$x_fecha_status = $datawrk2["fecha_status"];
	$x_asunto = $datawrk2["asunto"];
	$x_descripcion = $datawrk2["descripcion"];
	$x_crm_tarea_status_id = $datawrk2["crm_tarea_status_id"];
	@phpmkr_free_result($rswrk2);


	//caso id
	$x_crm_caso_id =  $datawrk2["crm_caso_id"];
	
	
	
	$currentdate = getdate(time());
	$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
	$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		


//accion segun tipo de tarea kuki

	if((($GLOBALS["x_observaciones"] != "") || (!empty($GLOBALS["x_fecha_pp"])) || (!empty($GLOBALS["x_pp_por"])))){
		//solo guarda comentarios en bitacora
		echo "<br><br> ENTRA AL PRIMER COMENTARIO <BR><BR>";	
		$sSqlWrk = "
		SELECT 
			bitacora,
			credito_id,
			crm_caso_tipo.descripcion
		FROM 
			crm_caso join crm_caso_tipo
			on crm_caso_tipo.crm_caso_tipo_id = crm_caso.crm_caso_tipo_id
		WHERE 
			crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
		LIMIT 1
		";		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_bitacora_ant = $datawrk["bitacora"];
		$x_credito_id = $datawrk["credito_id"];
		$x_crm_caso_tipo_desc = $datawrk["descripcion"];		
		@phpmkr_free_result($rswrk);
		$x_bitacora = $x_comentario_bitacora;
		$sSqlWrk = "
		UPDATE
			crm_caso
		SET 
			bitacora = '$x_bitacora'
		WHERE 
			crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
		";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


		//Bitacora creditos
		$sSqlWrk = "
		SELECT 
			comentario_int
		FROM 
			credito_comment
		WHERE 
			credito_id = ".$x_credito_id."
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_comment_ant = $datawrk["comentario_int"];
		@phpmkr_free_result($rswrk);


		$x_bitacora = "";
		$x_usuaio_registrado = registraUsuario($_SESSION["php_project_esf_status_UserID"],$conn);
		echo "comentario 1".$GLOBALS["x_observaciones"]."<br>";
		echo "usuario 1".$x_usuaio_registrado."<br>";
		$x_hora = date("H:i:s");
		 $x_comment_ant = str_replace("'","-", $x_comment_ant);
		 $x_comment_ant = str_replace("\"","-", $x_comment_ant);
		
		
		$x_bitacora = $x_comment_ant . "\n" . $x_usuaio_registrado." ".$currdate. " ".$x_hora." ".$GLOBALS["x_observaciones"];
		if((!empty($GLOBALS["x_fecha_pp"])) || (!empty($GLOBALS["x_pp_por"]))){
			$x_bitacora = $x_comment_ant . "\n" . $x_usuaio_registrado." ".$currdate. " ".$x_hora." FECHA DE PP ".$GLOBALS["x_fecha_pp"]." MONTO ".$GLOBALS["x_monto_pp"]." ". $GLOBALS["x_observaciones"];
			
			}else{
				$x_bitacora = $x_comment_ant . "\n" . $x_usuaio_registrado." ".$currdate. " ".$x_hora." ".$GLOBALS["x_observaciones"];
				
				
				}

		$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
		phpmkr_query($sSql, $conn) or die ("Error al insertar en cometario".phpmkr_error()."sql:".$sSql);
		echo "comentario". $sSql."<br>";
	
	}

echo "<br>status ".$x_crm_tarea_status_id."<br>";
	if($x_crm_tarea_status_id > 2){
		echo "status mayor a dos<br>";
		//se recibio pago y cierra caso		
		if($x_crm_tarea_status_id == 4){		

	
			$x_complemento_bitacora = "Pago completo, el caso ha sido cerrado.";				
			//cierra caso

			$sSqlWrk = "
			UPDATE
				crm_caso
			SET 
				crm_caso_status_id = 2,
				fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
			WHERE 
				crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
			";
			phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			//cierra tareas pendientes
			$sSqlWrk = "
			UPDATE
				crm_tarea
			SET 
				crm_tarea_status_id = 4,
				fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
			WHERE 
				crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
			";
			phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			//bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "TAREA CERRADA", "Se marco la tarea y el caso cerrado.", $x_destino_rol_id, $x_destino, $x_observaciones );			
			return true;
		
		}
		
		

		//incobrable genera tarea a administrador
		if($GLOBALS["x_incobrable"] == 1){
			//genera tarea 17	

			$sSqlWrk = "
			SELECT 
				*
			FROM 
				crm_playlist
			WHERE 
				crm_caso_tipo_id = 3
				and orden = 27
			ORDER BY crm_playlist_id				
			";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];	
			$x_asunto = $datawrk["asunto"];	
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
			$x_destino_rol_id = $datawrk["destino_id"];							
			$x_orden_new = $datawrk["orden"];						
			$x_dias_espera = $datawrk["dias_espera"];											


			if($x_destino_rol_id == 7){
				$sSqlWrk = "
				SELECT promotor.usuario_id
				FROM 
					solicitud join credito
					on credito.solicitud_id = solicitud.solicitud_id join
					promotor on promotor.promotor_id = solicitud.promotor_id
				WHERE 
					credito.credito_id = $x_credito_id
				LIMIT 1
				";
			}else{
				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = $x_destino_rol_id
				LIMIT 1
				";
			}

			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
				
			//Fecha Vencimiento
			$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
			$temptime = DateAdd('w',$x_dias_espera,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);			
			$x_dia = strftime('%A',$temptime);
			if(strtoupper($x_dia) == "SUNDAY"){
				$temptime = strtotime($fecha_venc);
				$temptime = DateAdd('w',1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);
			}
			$temptime = strtotime($fecha_venc);

			$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

			bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "INCOBRABLE", "El responsable determino el credito como incobrable.", $x_destino_rol_id, $x_destino, $x_observaciones );


		}else{
	
	echo "no incobrable<br>";
			//si el usuario cambia orden de tarea
			if($GLOBALS["x_tarea_siguiente"] != ""){
				$x_tarea_siguiente_id = intval(substr($GLOBALS["x_tarea_siguiente"],0,strpos($GLOBALS["x_tarea_siguiente"],"|")));
			}else{
				$x_tarea_siguiente_id = 0;
			}


			if($x_tarea_siguiente_id > 0){
			//si especifico una tarea siguiente
				$sSqlWrk = "
				SELECT 
					orden
				FROM 
					crm_playlist
				WHERE 
					crm_playlist_id = $x_tarea_siguiente_id 
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_orden_sig = $datawrk["orden"];
				@phpmkr_free_result($rswrk);
			
			}else{
				$x_orden_sig = 0;
			}


	echo "tarea_tipo_id ".$GLOBALS["x_crm_tarea_tipo_id"]."<br>";
	
			if(($GLOBALS["x_crm_tarea_tipo_id"] == 5) ){//|| ($GLOBALS["x_crm_tarea_tipo_id"] == 6)){ //Solicitud de pp o recordatorio de pp
				
				echo "entra a  solcitud de promesa de pago<br>";
				//2 dias mas de gracia
				if($GLOBALS["x_negativa"] == 1){

					//2 dias mas de gracia
					echo "entra a negativa<br>";
					$temptime = strtotime(ConvertDateToMysqlFormat($currdate));	
					$temptime = DateAdd('w',30,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					if(strtoupper($x_dia) == "SUNDAY"){
						$temptime = strtotime($fecha_venc);
						$temptime = DateAdd('w',1,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);
					}
					$temptime = strtotime($fecha_venc);


					if($x_tarea_comp == 0){
						$sSql = "update crm_tarea set fecha_ejecuta = '$fecha_venc', crm_tarea_status_id = 1 where crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"];
					}else{
						$sSql = "update crm_tarea set crm_tarea_status_id = 1 where crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"];
					}

					phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


					//bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "NEGATIVA DE PAGO", "No se ha obtenido Promesa de Pago.", $x_destino_rol_id, $x_destino, $x_observaciones );

					return true;

				}

				if($GLOBALS["x_manda_carta"] == 1){
					// abor una tarea nueva para carta 1					
					if($x_carta_1 == 0){						
//seleccionamos los datos del credito ?
$sqCredito = "SELECT credito.*, solicitud.solicitud_id as sol_id, solicitud.promotor_id, crm_caso.* FROM credito JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id JOIN crm_caso ON crm_caso.credito_id = credito.credito_id WHERE crm_caso_id = $x_crm_caso_id ";
#echo "sql credito".$sqCredito."<br>";
$rsCredito = phpmkr_query($sqCredito,$conn)or die("Error al buscar el credito".phpmkr_error()."sql :".$sqCredito);
$rowCredito = phpmkr_fetch_array($rsCredito);		
$x_promotor_id_c = $rowCredito["promotor_id"];
$x_credito_id = $rowCredito["credito_id"];
#echo "credito_id". $rowCredito["credito_id"]."<br>";
$x_solicitud_id_c = $rowCredito["solicitud_id"];
$x_sol_id = $rowCredito["sol_id"];		
#	echo "sol id++++".$rowCredito["sol_id"]."<br>";					
						
##############################################################################################################	##############################################################################################################
#####################################               CARTA 1              #####################################	##############################################################################################################	##############################################################################################################
	//PRMIER CASO CARATA 1		
	// antes de insertar la tarea de carta 1 verificamos que no existe dicha tarea.		
		#echo "entra a CARTA 1<br>";		
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 4 "; // orden 4 CARTA  1		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);				
				$currdate = date("Y-m-d");				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 2  CARTA 1 --El responsable, no localizaba al cliente- (".FormatDateTime($currdate,7)." - $currtime)";			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";
				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		
		
		@phpmkr_free_result($rswrk);
		// seleccionamos nuevamente el usuario
		$sqlUsuario = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn)or die ("Erroro al aseleccionar el usuario".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);
		//$x_usuario_id = $rowUsuario["usuario_id"];
		$x_usuario_id = $_SESSION["php_project_esf_status_UserID"] ;
		@phpmkr_free_result($rsUsuario);
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			//$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		#echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			//phpmkr_query($sSql, $conn);		
			}// tareas registradas = 0
			
		#die();
	}//CARTA 1					
					
					}	
					
				if($GLOBALS["x_manda_carta"] == 2){
					// abor una tarea nueva para carta 1
					
					if($x_carta_2 == 0){
						
//seleccionamos los datos del credito ?

$sqCredito = "SELECT credito.*, solicitud.solicitud_id as sol_id, solicitud.promotor_id, crm_caso.* FROM credito JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id JOIN crm_caso ON crm_caso.credito_id = credito.credito_id WHERE crm_caso_id = $x_crm_caso_id ";
#echo "sql credito".$sqCredito."<br>";
$rsCredito = phpmkr_query($sqCredito,$conn)or die("Error al buscar el credito".phpmkr_error()."sql :".$sqCredito);
$rowCredito = phpmkr_fetch_array($rsCredito);		
$x_promotor_id_c = $rowCredito["promotor_id"];
$x_credito_id = $rowCredito["credito_id"];
#echo "credito_id". $rowCredito["credito_id"]."<br>";
$x_solicitud_id_c = $rowCredito["solicitud_id"];	

$x_sol_id = $rowCredito["sol_id"];		
#	echo "sol id++++".$rowCredito["sol_id"]."<br>";					
						
##############################################################################################################	##############################################################################################################
#####################################               CARTA 2              #####################################	##############################################################################################################	##############################################################################################################
	//PRMIER CASO CARTA 2	
	
	// antes de insertar la tarea de carta 2 verificamos que no existe dicha tarea.
		
		#echo "entra a CARTA 2<br>";		
			
			
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 8 "; // orden 8 CARTA  2		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				$currdate = date("Y-m-d");
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A   CARTA 2 --El gestor,  cambia la tarea a carta 2- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];		
		@phpmkr_free_result($rswrk);	
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		
		
		
		
		@phpmkr_free_result($rswrk);
	// seleccionamos nuevamente el usuario
		$sqlUsuario = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn)or die ("Erroro al aseleccionar el usuario".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);
		$x_usuario_id = $rowUsuario["usuario_id"];
		@phpmkr_free_result($rsUsuario);	
		$x_usuario_id = $_SESSION["php_project_esf_status_UserID"] ;
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			//$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		#echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			//phpmkr_query($sSql, $conn);
		
		
		
			}// tareas registradas = 0
			
		#die();
	}//CARTA 2
					
					
					
					
					
					}		
					
				if($GLOBALS["x_manda_carta"] == 3){
					// abor una tarea nueva para carta 3
					
					if($x_carta_3 == 0){
						
//seleccionamos los datos del credito ?

$sqCredito = "SELECT credito.*, solicitud.solicitud_id as sol_id, solicitud.promotor_id, crm_caso.* FROM credito JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id JOIN crm_caso ON crm_caso.credito_id = credito.credito_id WHERE crm_caso_id = $x_crm_caso_id ";
#echo "sql credito".$sqCredito."<br>";
$rsCredito = phpmkr_query($sqCredito,$conn)or die("Error al buscar el credito".phpmkr_error()."sql :".$sqCredito);
$rowCredito = phpmkr_fetch_array($rsCredito);		
$x_promotor_id_c = $rowCredito["promotor_id"];
$x_credito_id = $rowCredito["credito_id"];
#echo "credito_id". $rowCredito["credito_id"]."<br>";
$x_solicitud_id_c = $rowCredito["solicitud_id"];	

$x_sol_id = $rowCredito["sol_id"];		
#	echo "sol id++++".$rowCredito["sol_id"]."<br>";					
						
##############################################################################################################	##############################################################################################################
#####################################               CARTA 3              #####################################	##############################################################################################################	##############################################################################################################
	//PRMIER CASO CARATA 3	
	
	// antes de insertar la tarea de carta 3 verificamos que no existe dicha tarea.
		
		#echo "entra a CARTA 3<br>";		
			
			
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 12 "; // orden 12 CARTA  3		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				$currdate = date("Y-m-d");
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A  CARTA 3 --El gestor, manda a carta 3 - (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];	
		@phpmkr_free_result($rswrk);
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		// seleccionamos nuevamente el usuario
		$sqlUsuario = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn)or die ("Erroro al aseleccionar el usuario".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);
		$x_usuario_id = $rowUsuario["usuario_id"];
		@phpmkr_free_result($rsUsuario);
		
			$x_usuario_id = $_SESSION["php_project_esf_status_UserID"] ;
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			//$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		#echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			//phpmkr_query($sSql, $conn);
		
		
			}// tareas registradas = 0
			
		#die();
	}//CARTA 3
					
					
					
					
					
					}		
				
				if($GLOBALS["x_manda_carta"] == 4){
					// abor una tarea nueva para carta 1
					
					if($x_carta_D == 0){
						



//seleccionamos los datos del credito ?

$sqCredito = "SELECT credito.*, solicitud.solicitud_id as sol_id, solicitud.promotor_id, crm_caso.* FROM credito JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id JOIN crm_caso ON crm_caso.credito_id = credito.credito_id WHERE crm_caso_id = $x_crm_caso_id ";
#echo "sql credito".$sqCredito."<br>";
$rsCredito = phpmkr_query($sqCredito,$conn)or die("Error al buscar el credito".phpmkr_error()."sql :".$sqCredito);
$rowCredito = phpmkr_fetch_array($rsCredito);		
$x_promotor_id_c = $rowCredito["promotor_id"];
$x_credito_id = $rowCredito["credito_id"];
#echo "credito_id". $rowCredito["credito_id"]."<br>";
$x_solicitud_id_c = $rowCredito["solicitud_id"];	

$x_sol_id = $rowCredito["sol_id"];		
#	echo "sol id++++".$rowCredito["sol_id"]."<br>";		


// cambiamos el gestor del credito; el getro que se encarga de la demandas siempre es rodrigo
// el usario del gestor rodrigo es el numero  ==>7180 Rodrigo Sanchez (Gestor Cobranza)
$sqlUpdateGestorDemanda = " UPDATE gestor_credito SET usuario_id = 7180 WHERE credito_id = $x_credito_id ";
$rsUpdateGestor = phpmkr_query($sqlUpdateGestorDemanda,$conn)or die ("Error al actualizarel gestor para demanda".phpmkr_error()."sql:".$sqlUpdateGestorDemanda);			
						
##############################################################################################################	##############################################################################################################
#####################################               CARTA D              #####################################	##############################################################################################################	##############################################################################################################
	//PRMIER CASO CARATA 1	
	
	// antes de insertar la tarea de carta 1 verificamos que no existe dicha tarea.
		
		#echo "entra a CARTA 1<br>";		
			
			
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 20 "; // orden 20 CARTA  D	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				$currdate = date("Y-m-d");
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A CARTA D --El gestor, manda a DEMANDA- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		
		
		@phpmkr_free_result($rswrk);
		
	// seleccionamos nuevamente el usuario
		$sqlUsuario = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn)or die ("Erroro al aseleccionar el usuario".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);
		$x_usuario_id = $rowUsuario["usuario_id"];
		$x_usuario_id = $_SESSION["php_project_esf_status_UserID"];		
		@phpmkr_free_result($rsUsuario);
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			//$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		#echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			//phpmkr_query($sSql, $conn);	
		
			}// tareas registradas = 0
			
		#die();
	}//CARTA D				
					
					
					}
					
					echo "tarea tipo id". $GLOBALS["x_crm_tarea_tipo_id"] ."<br> pp ".$GLOBALS["x_fecha_pp"];

			if(($GLOBALS["x_crm_tarea_tipo_id"] == 5) && (!empty($GLOBALS["x_fecha_pp"]))){
				// se cambia el status de del caso				
				$sqlBuscaStatus = "select * from crm_credito_status where credito_id = $x_credito_id ";
						$rsBuscaStatus = phpmkr_query($sqlBuscaStatus,$conn) or die ("Error al seleccioanr los datos del ststaus".phpmkr_error()."sql:".$sqlBuscaStatus);
						$rowBuscaStatus = phpmkr_fetch_array($rsBuscaStatus);
						$x_credito_status_id = $rowBuscaStatus["credito_id"];
						$x_fecha_h = date("Y-m-d");
						if(empty($x_credito_status_id)){
							// si no existe lo insertamos							
							$sqlInsert = " INSERT INTO crm_credito_status (`crm_credito_status_id`, `credito_id`, `crm_tarea_id`, `crm_cartera_status_id`, `fecha`) ";
							$sqlInsert .=  " VALUES (NULL, $x_credito_id, $x_tarea_ida,'1', '$x_fecha_h') ";							
							}else{
								// si existe actualizamos el ststus
								$sqlInsert = " UPDATE  `crm_credito_status` SET  `crm_cartera_status_id` =  '3', `fecha` = '$x_fecha_h'  WHERE  `credito_id` =$x_credito_id " ;
								}
								
						phpmkr_query($sqlInsert,$conn) or die("error al inbsertar en crm_credito_staus".phpmkr_error()."sql;".$sqlInsert);	
						echo $sqlInsert;
						
						$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id";
			#echo "sql cliente".$sqlCliente."<br>";
			$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
			$rowClientet = phpmkr_fetch_array($rsCliente);
			$x_cliente_idt = 	$rowClientet["cliente_id"];
						
						if (!empty($x_cliente_idt) ){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idt  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 //$x_no_celular = "5540663927";
		 echo  "numero de celular ".$x_no_celular."<br>";
		 //seleccionamos el email del cliente
		$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_idt." ";
		$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
		$rowmail =  phpmkr_fetch_array($rsMail);
		$email =  $rowmail["email"];
		echo " <br> ".$sqlMail;
		echo "eamail ".$email ."<br>";

		 
		 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_mensaje = "CREA CRED ".$x_credito_num." AGRADECEMOS SU INTERÉS EN REGULARIZAR SU CUENTA";
						$x_mensaje .= " CONTAMOS CON SU PAGO POR ".$GLOBALS["x_monto_pp"]." EL DIA ".$GLOBALS["x_fecha_pp"];
							 
						echo "MENSAJE :". $x_mensaje."<BR>";										
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
						mail($para, $titulo, $x_mensaje, $cabeceras);	
						
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '24','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idt)";
						
							
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);	
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`,`cliente_id` ) VALUES (NULL, '24','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha."', '1', '1',$x_cliente_idt)";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}		
						
			}	
		 }// while telefonos
		}	
								
				}

// abierto ----------------------------------------------------
			//ya no existe tarea siguiente, ahora la siguiente tarea la determina el sistema
			// la tarea de recordatorio si se genera; pero el procnoc valida si el cliente tiene celualr con el nuevo formato para enviarle un mensaje ; si lo tiene; cierrra la tarea
			// si no lo tiene la responsable de surcursal hara la llamada para recordarle al cliente la pp 
			#SELECCIONAMOS LOS DATOS DEL RESPONSABLE DE SUCURSAL YA QUE SERA EL ENCARGO DE HACER LAS LLAMASDAS A LOS CLIENTES PARA RECORDARLES EL PAGO$sSqlWrk = "
			$sSqlWrk = "SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	#echo "comentarios anteriores".$x_comment_ant."<br>";
	#echo "contenido botacora".$x_bitacora."<br>";
	$x_usuaio_registrado = registraUsuario($_SESSION["php_project_esf_status_UserID"],$conn);
	echo "usuario registrado" .$x_usuaio_registrado."---<br>";
	echo "coemtario bitacora ".$x_comentario_bitacora."---<br>";
	$x_bitacora = $x_usuaio_registrado." ".$currdate.$x_comment_ant . "** \n\n" . $GLOBALS["x_observaciones"];
	echo "bitacora final".$x_bitacora ."<br>";
	
						
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
			
			echo "sql vit".$sSql."<br>";
	
			//phpmkr_query($sSql, $conn) or die ("ERROR".phpmkr_error()."sql :".$sSql);
				$sqlCaso = "SELECT * FROM crm_caso WHERE crm_caso_id = ".$GLOBALS["x_crm_caso_id"]." ";
				$rscaso = phpmkr_query($sqlCaso,$conn) or die("error al selccionar los datos del caso".phpmkr_error()."sql:".$sqlCaso);
				$rowCaso = phpmkr_fetch_array($rscaso);
				$x_cred_id = $rowCaso["credito_id"];
				$SQLrESPONSABLEsUCURSAL = "SELECT * FROM credito WHERE credito_id = $x_cred_id ";
				$rsResponsabelSuc = phpmkr_query($SQLrESPONSABLEsUCURSAL,$conn)or die ("Error al selec los datos del credito".phpmkr_fetch_array()."sql:".$SQLrESPONSABLEsUCURSAL);
				$rowResponsableSuc = phpmkr_fetch_array($rsResponsabelSuc);
				$x_sol_id = $rowResponsableSuc["solicitud_id"];
				
				echo "solictud id".$x_sol_id;
				
				// seleccionamos el promotor
				$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_sol_id";
				$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
				$rowPromotor = phpmkr_fetch_array($rsPromotor);
				$x_promotor_id_c = $rowPromotor["promotor_id"];
				echo "promotor id".$x_promotor_id_c."<br>";	
				if($x_promotor_id_c > 0){
					// buscamos a que sucursal pertence el promotor
					$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
					$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
					$rowSucuersal = phpmkr_fetch_array($rsSucursal);
					$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
					echo "sucursal id".$x_sucursal_id_c."<br>";
			
				if($x_sucursal_id_c > 0){
					// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
					$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
					$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
					$rowResponsable = phpmkr_fetch_array($rsResponsable);
					$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];		
				echo "responsable_sucursal id".$x_responsable_susursal_usuario_id."<br>";
				}
			} 
				if($x_responsable_susursal_usuario_id > 0){
					 $x_destino = $x_responsable_susursal_usuario_id ;
					}


				// si pp para mañana solo genera confirmacion para pasado mañana
				$temptime2 = strtotime(ConvertDateToMysqlFormat($currdate));	
				$temptime2 = DateAdd('w',1,$temptime2);
				$fec_pp = strftime('%Y-%m-%d',$temptime2);			
				$temptime2 = strtotime($fec_pp);

				$temptime3 = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]));	
				if(($temptime3 > $temptime2) || ($temptime3 <= $temptime2)){  //if($temptime3 > $temptime2){ ahora genera el recordatorio para todas las ocasiones
				if( $GLOBALS["x_manda_carta"] == 0){
				
					
					//recordatorio antes pp
					$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]));	
					$temptime = DateAdd('w',-1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					if(strtoupper($x_dia) == "SUNDAY"){
						$temptime = strtotime($fecha_venc);
						$temptime = DateAdd('w',1,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);
					}
					$temptime = strtotime($fecha_venc);
	
	
					$x_orden_act = $x_orden_act + 1;
	
					//recordatorio pago
					$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act, 6, 1,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'RECORDATORIO','LLAMAR AL CLIENTE PARA RECORDAR PAGO',1)";
				
					phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	
				echo 	"inserta en trea".$sSql."<br>";
	
					$x_dia = strtoupper(date('l',strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]))));
	
	
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
							$x_dias_gracia = 3;
							break;
						case "SATURDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;
						case "SUNDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;		
					}
				  
				}}else{
					$x_dias_gracia = 2;
				}

				//confirmacion despues de pp			
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_pp"]));	
				$temptime = DateAdd('w',$x_dias_gracia,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				
/*				
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
*/

				//aqui tareas de confirmacion todas a coordinador si no es juridico
				if($x_orden_act < 12){				
					$x_destino_rol_id_cp = 3; 
				}else{
					$x_destino_rol_id_cp = 9; 				
				}
				
				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = $x_destino_rol_id_cp
				LIMIT 1
				";

				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_destino_cp = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);

				$x_orden_act = $x_orden_act + 1;

	

	
			}
			
			//die();
			if($GLOBALS["x_crm_tarea_tipo_id"] == 6){ //recordatorio pp		
				//bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "RECORDATORIO PP", "Se recordo promesa de pago.", $x_destino_rol_id, $x_destino, $x_observaciones );			
			
			
			}
			
						
			if($GLOBALS["x_crm_tarea_tipo_id"] == 7 && $x_entra == 1){ //confirmacion pp
				$x_complemento_bitacora = "";
				//pago completo
				if($GLOBALS["x_pago_completo"] == 1){			
					$x_complemento_bitacora = "Pago completo, el caso ha sido cerrado.";				
					//cierra caso
	
					$sSqlWrk = "
					UPDATE
						crm_caso
					SET 
						crm_caso_status_id = 2,
						fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
					WHERE 
						crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
					";
					phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


					//cierra tareas pendientes
					$sSqlWrk = "
					UPDATE
						crm_tarea
					SET 
						crm_tarea_status_id = 4,
						fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
					WHERE 
						crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
					";
					phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

					bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CONFIRMACION DE PAGO", "Se realizao confirmacion de pago. ".$x_complemento_bitacora, $x_destino_rol_id, $x_destino, $x_observaciones );


					
				}
				
				//PAGO INCOMPETO
				if($GLOBALS["x_pago_completo"] == 2){

// abierto ----------------------------------------------------
					//si hubo cambio de tarea siguiente la aplica sino sigue proceso

					$x_complemento_bitacora = "Pago incompleto.";
					
					if($x_tarea_siguiente_id > 0){
						siguiente_tarea($conn,$x_tarea_siguiente_id,$x_credito_id, $GLOBALS["x_crm_caso_id"],$x_orden_sig,$GLOBALS["x_fecha_status"],$GLOBALS["x_hora_status"],$GLOBALS["x_fectarea"],$x_destino_rol_id, $x_destino);

					}else{
					
					
						//genera solicitud de pp
		
						$x_orden_act = $x_orden_act - 2;
		
						$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','".ConvertDateToMysqlFormat($currdate)."',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'SOLICITUD PP','SOLICITAR PROMESA DE PAGO',1)";
						phpmkr_query($sSql, $conn)  or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
					}
					
					bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CONFIRMACION DE PAGO", "Se realizao confirmacion de pago. ".$x_complemento_bitacora, $x_destino_rol_id, $x_destino, $x_observaciones );

				
				}
					
				//NO PAGO
				if($GLOBALS["x_pago_completo"] == 3){		
				
				
// abierto ----------------------------------------------------
					//si hubo cambio de tarea siguiente la aplica sino sigue proceso
					
					$x_complemento_bitacora = "No se realizo el pago.";								
					
					if($x_tarea_siguiente_id > 0){
						siguiente_tarea($conn,$x_tarea_siguiente_id,$x_credito_id, $GLOBALS["x_crm_caso_id"],$x_orden_sig,$GLOBALS["x_fecha_status"],$GLOBALS["x_hora_status"],$GLOBALS["x_fectarea"],$x_destino_rol_id, $x_destino);

					}else{				
					
					
	
						//genera siguiente tarea
						//Busca siguiente tarea 
						
						
						//carta 3 solo creditos con garantia
						if($x_orden_act == 11){
							//localiza garantia
							$sSqlWrk = "
							SELECT count(*) as garantia
							FROM 
								solicitud join credito
								on credito.solicitud_id = solicitud.solicitud_id join
								garantia on garantia.solicitud_id = solicitud.solicitud_id
							WHERE 
								credito.credito_id = $x_credito_id
							LIMIT 1
							";
				
							$rswrk2 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
							$datawrk2 = phpmkr_fetch_array($rswrk2);
							$x_garantia = $datawrk2["garantia"];
							@phpmkr_free_result($rswrk2);
		
							if($x_garantia == 0){
								$x_orden_act = 15;
							}
						}
						
						$sSqlWrk = "
						SELECT 
							*
						FROM 
							crm_playlist
						WHERE 
							crm_caso_tipo_id = 3
							and orden = $x_orden_act + 1
						ORDER BY crm_playlist_id				
						";
						$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
						$datawrk = phpmkr_fetch_array($rswrk);
						$x_crm_playlist_id = $datawrk["crm_playlist_id"];
						$x_prioridad_id = $datawrk["prioridad_id"];	
						$x_asunto = $datawrk["asunto"];	
						$x_descripcion = $datawrk["descripcion"];		
						$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
						$x_destino_rol_id = $datawrk["destino_id"];							
						$x_orden_new = $datawrk["orden"];						
						$x_dias_espera = $datawrk["dias_espera"];	
						@phpmkr_free_result($rswrk);										
		
		
						if($x_destino_rol_id == 7){
							$sSqlWrk = "
							SELECT promotor.usuario_id
							FROM 
								solicitud join credito
								on credito.solicitud_id = solicitud.solicitud_id join
								promotor on promotor.promotor_id = solicitud.promotor_id
							WHERE 
								credito.credito_id = $x_credito_id
							LIMIT 1
							";
						}else{
							$sSqlWrk = "
							SELECT usuario_id
							FROM 
								usuario
							WHERE 
								usuario.usuario_rol_id = $x_destino_rol_id
							LIMIT 1
							";
						}
		
						$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
						$datawrk = phpmkr_fetch_array($rswrk);
						$x_usuario_id = $datawrk["usuario_id"];
						@phpmkr_free_result($rswrk);
							
						//Fecha Vencimiento
						$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if(strtoupper($x_dia) == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$temptime = strtotime($fecha_venc);
						
						
						
						
		
						$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
					
						phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
						
					
					}
	
	
					bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CONFIRMACION DE PAGO", "Se realizao confirmacion de pago. ".$x_complemento_bitacora, $x_destino_rol_id, $x_destino, $x_observaciones );

				}






			}
			
			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 8 && $x_entra == 1){ //carta 1
				
				//dias de gracias post fecha de entrega 
				//recordatorio antes pp
				/*<!--$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_entrega"]));	
				$temptime = DateAdd('w',2,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				//genera solicitud de pp
		
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act + 1, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'PP C1','SOLICITAR PP CARTA 1',1)";
		
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CARTA 1", "Se emitio carta 1 con fecha de entrega: ".$GLOBALS["x_fecha_entrega"], $x_destino_rol_id, $x_destino, $x_observaciones );-->*/
			
			
			}			
			
			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 9 && $x_entra == 1){ //carta 2
	
				/*//dias de gracias post fecha de entrega 
				//recordatorio antes pp
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_entrega"]));	
				$temptime = DateAdd('w',2,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				//genera solicitud de pp
		
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act + 1, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'PP C2','SOLICITAR PP CARTA 2',1)";
		
				//phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CARTA 2", "Se emitio carta 2 con fecha de entrega: ".$GLOBALS["x_fecha_entrega"], $x_destino_rol_id, $x_destino, $x_observaciones );
	*/
				
			}				
			
			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 10 && $x_entra == 1){ //carta 3
			//solo creditos con garantia
		
				//dias de gracias post fecha de entrega 
				//recordatorio antes pp
			/*	$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_entrega"]));	
				$temptime = DateAdd('w',2,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				//genera solicitud de pp
		
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act + 1, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'PP C3','SOLICITAR PP CARTA 3',1)";
		
			//	phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CARTA 3", "Se emitio carta 3 con fecha de entrega: ".$GLOBALS["x_fecha_entrega"], $x_destino_rol_id, $x_destino, $x_observaciones );*/
	
			
			}			
			
			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 11 && $x_entra == 1){ //carta 4
			
				//dias de gracias post fecha de entrega 
				//recordatorio antes pp
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_entrega"]));	
				$temptime = DateAdd('w',2,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	
				//genera solicitud de pp
		
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_act + 1, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'PP C4','SOLICITAR PP CARTA 4',1)";
		
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "CARTA 4", "Se emitio carta 4 con fecha de entrega: ".$GLOBALS["x_fecha_entrega"], $x_destino_rol_id, $x_destino, $x_observaciones );
	
				
			}


			if($GLOBALS["x_crm_tarea_tipo_id"] == 12 && $x_entra == 1){/* //demanda
				//generar resultado de demanda  3 dias despues
				$sSqlWrk = "
				SELECT 
					*
				FROM 
					crm_playlist
				WHERE 
					crm_caso_tipo_id = 3
					and orden = $x_orden_act + 1
				ORDER BY crm_playlist_id				
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
				$x_destino_rol_id = $datawrk["destino_id"];							
				$x_orden_new = $datawrk["orden"];						
				$x_dias_espera = $datawrk["dias_espera"];											
	
	
				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = $x_destino_rol_id
				LIMIT 1
				";
	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_usuario_id = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);
					
				//Fecha Vencimiento
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				//dias habiles
				if(strtoupper($x_dia) == "SATURDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',2,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				
				$temptime = strtotime($fecha_venc);
	
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."', '$x_asunto','$x_descripcion',1)";


				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "DEMANDA", "Se inicia proceso de demanda.", $x_destino_rol_id, $x_destino, $x_observaciones );

			*/}


			if($GLOBALS["x_crm_tarea_tipo_id"] == 13 && $x_entra == 1){ //resultado demanda
			
			
				if($GLOBALS["x_resultado_demanda"] == 1){
					$x_resultado = "SATISFACTRIO";

					bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "RESULATADO DE DEMANDA ", "Se especifico resultado de la demanda: ".$x_resultado, $x_destino_rol_id, $x_destino, $x_observaciones );

					$x_orden_act_resultado = $x_orden_act + 1;

				}else{
					$x_resultado = "NO SATISFACTORIO";					

					bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "RESULATADO DE DEMANDA ", "Se especifico resultado de la demanda: ".$x_resultado, $x_destino_rol_id, $x_destino, $x_observaciones );

					$x_orden_act_resultado = $x_orden_act;

				}

				//genera siguiente tarea dependiendo de resultado
				$sSqlWrk = "
				SELECT 
					*
				FROM 
					crm_playlist
				WHERE 
					crm_caso_tipo_id = 3
					and orden = $x_orden_act_resultado
				ORDER BY crm_playlist_id				
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
				$x_destino_rol_id = $datawrk["destino_id"];							
				$x_orden_new = $datawrk["orden"];						
				$x_dias_espera = $datawrk["dias_espera"];											
	
	
				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = $x_destino_rol_id
				LIMIT 1
				";
	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_usuario_id = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);
					
				//Fecha Vencimiento
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, 8,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


			}

			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 14 && $x_entra == 1 ){ //cita actuario

				//genera siguiente tarea
				$sSqlWrk = "
				SELECT 
					*
				FROM 
					crm_playlist
				WHERE 
					crm_caso_tipo_id = 3
					and orden = $x_orden_act + 1
				ORDER BY crm_playlist_id				
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
				$x_destino_rol_id = $datawrk["destino_id"];							
				$x_orden_new = $datawrk["orden"];						
				$x_dias_espera = $datawrk["dias_espera"];											


				//genera solicitud de pp

				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = $x_destino_rol_id
				LIMIT 1
				";
	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_usuario_id = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);


				//dias de gracias post fecha de entrega 
				//recordatorio antes cita
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_cita"]));	
				$temptime = DateAdd('w',-1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	


				//Recordatorio cita
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, 8,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


				$temptime = strtotime(ConvertDateToMysqlFormat($fecha_venc));	
				$temptime = DateAdd('w',7,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);	

				//Promesa de pago hasta 7 dias despues de cita
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new + 1, 5, 1,'".ConvertDateToMysqlFormat($currdate)."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id, $x_destino, NULL,NULL, 'SOLICITUD PP','SOLICITAR PROMESA DE PAGO',1)";
		
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
				
			}


			if($GLOBALS["x_crm_tarea_tipo_id"] == 15 && $x_entra == 1){ //Recordatorio cita Actuario

				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "RECORDATORIO CITA ACTURAIO", "Se recordo cita actuario.", $x_destino_rol_id, $x_destino, $x_observaciones );

			}


			if($GLOBALS["x_crm_tarea_tipo_id"] == 16 && $x_entra == 1){ //Embargo
				//genera siguiente tarea
				$sSqlWrk = "
				SELECT 
					*
				FROM 
					crm_playlist
				WHERE 
					crm_caso_tipo_id = 3
					and orden = $x_orden_act + 1
				ORDER BY crm_playlist_id				
				";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
				$x_destino_rol_id = $datawrk["destino_id"];							
				$x_orden_new = $datawrk["orden"];						
				$x_dias_espera = $datawrk["dias_espera"];											
	
	
				if($x_destino_rol_id == 7){
					$sSqlWrk = "
					SELECT promotor.usuario_id
					FROM 
						solicitud join credito
						on credito.solicitud_id = solicitud.solicitud_id join
						promotor on promotor.promotor_id = solicitud.promotor_id
					WHERE 
						credito.credito_id = $x_credito_id
					LIMIT 1
					";
				}else{
					$sSqlWrk = "
					SELECT usuario_id
					FROM 
						usuario
					WHERE 
						usuario.usuario_rol_id = $x_destino_rol_id
					LIMIT 1
					";
				}
	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_usuario_id = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);
					
				//Fecha Vencimiento
				$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"]));	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if(strtoupper($x_dia) == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
				$sSql = "INSERT INTO crm_tarea values (0,".$GLOBALS["x_crm_caso_id"].", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."','".$GLOBALS["x_hora_status"]."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "EMBARGO ", "Se llevo a cabo el proceso de embargo. ", $x_destino_rol_id, $x_destino, $x_observaciones );



			}	
			
			
			if($GLOBALS["x_crm_tarea_tipo_id"] == 17 && $x_entra == 1){ //Incobrable
					//cierra caso
	
					$sSqlWrk = "
					UPDATE
						crm_caso
					SET 
						crm_caso_status_id = 2,
						fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
					WHERE 
						crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
					";
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	

					//cierra tareas pendientes
					$sSqlWrk = "
					UPDATE
						crm_tarea
					SET 
						crm_tarea_status_id = 4,
						fecha_status = '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_status"])."'
					WHERE 
						crm_caso_id = ".$GLOBALS["x_crm_caso_id"]."
					";
					phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


				bitacora($conn, $GLOBALS["x_crm_caso_id"], $GLOBALS["x_fecha_status"], $GLOBALS["x_hora_status"], "INCOBRABLE ", "El administrador autorizo el credito como incobrable.. ", $x_destino_rol_id, $x_destino, $x_observaciones );



			}
		}

	}


}



		$EditData = true; // Update Successful
	}
	
	//die();
	return $EditData;
	
}


function bitacora($conn, $x_crm_caso_id, $x_fecha, $x_hora, $x_asunto, $x_descripcion, $x_destino_rol_id, $x_destino, $x_comentario ){


$sSqlWrk = "
SELECT 
	bitacora,
	credito_id,
	crm_caso_tipo.descripcion
FROM 
	crm_caso join crm_caso_tipo
	on crm_caso_tipo.crm_caso_tipo_id = crm_caso.crm_caso_tipo_id
WHERE 
	crm_caso_id = ".$x_crm_caso_id."
LIMIT 1
";

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_bitacora_ant = $datawrk["bitacora"];
$x_credito_id = $datawrk["credito_id"];
$x_crm_caso_tipo_desc = $datawrk["descripcion"];		
@phpmkr_free_result($rswrk);



if($x_destino_rol_id == 7){
	$sSqlWrk = "
	SELECT usuario as responsable
	FROM 
		promotor join usuario on usuario.usuario_id = promotor.usuario_id
	WHERE 
		promotor_id = $x_destino
	LIMIT 1
	";
}else{
	$sSqlWrk = "
	SELECT usuario as responsable
	FROM 
		usuario
	WHERE 
		usuario.usuario_id = $x_destino
	LIMIT 1
	";
}

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_responsable_nombre = $datawrk["responsable"];
@phpmkr_free_result($rswrk);


$x_bitacora = $x_bitacora_ant . "\n\n------------------------------\n";
//$x_bitacora .= "$x_crm_caso_tipo_desc - (".FormatDateTime($x_fecha,7)." - ".$x_hora.")";
//$x_bitacora .= "\n";
$x_bitacora .= "\n$x_comentario";	
$x_bitacora .= "\n------------------------------";

$sSqlWrk = "
UPDATE
	crm_caso
SET 
	bitacora = '$x_bitacora'
WHERE 
	crm_caso_id = ".$x_crm_caso_id."
";
phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


//Bitacora creditos
$sSqlWrk = "
SELECT 
	comentario_int
FROM 
	credito_comment
WHERE 
	credito_id = ".$x_credito_id."
LIMIT 1
";

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_comment_ant = $datawrk["comentario_int"];
@phpmkr_free_result($rswrk);


$x_bitacora = "";
$x_bitacora = $x_comment_ant . "\n\n------------------------------\n";
//$x_bitacora .= "$x_crm_caso_tipo_desc - (".FormatDateTime($x_fecha,7)." - ".$x_hora.")";
//$x_bitacora .= "\n";
$x_bitacora .= "\n$x_comentario";	
$x_bitacora .= "\n------------------------------";


$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
phpmkr_query($sSql, $conn);



}


function siguiente_tarea($conn, $x_siguinte_tarea, $x_credito_id, $x_crm_caso_id, $x_orden, $x_fecha_registro, $x_hora_registro, $x_fecha_ejecucion, $x_detino_rol_id, $x_destino){

$x_tit_bitacora = "";
$x_detalle_bitacora = "";
$x_observaciones = "";


$sSqlWrk = "
SELECT 
	*
FROM 
	crm_playlist
WHERE 
	crm_caso_tipo_id = 3
	and orden = $x_orden
ORDER BY crm_playlist_id				
";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

$datawrk = phpmkr_fetch_array($rswrk);
$x_crm_playlist_id = $datawrk["crm_playlist_id"];
$x_prioridad_id = $datawrk["prioridad_id"];	
$x_asunto = $datawrk["asunto"];	
$x_descripcion = $datawrk["descripcion"];		
$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
$x_destino_rol_id = $datawrk["destino_id"];							
$x_orden_new = $datawrk["orden"];						
$x_dias_espera = $datawrk["dias_espera"];											


if($x_destino_rol_id == 7){
	$sSqlWrk = "
	SELECT promotor.usuario_id
	FROM 
		solicitud join credito
		on credito.solicitud_id = solicitud.solicitud_id join
		promotor on promotor.promotor_id = solicitud.promotor_id
	WHERE 
		credito.credito_id = $x_credito_id
	LIMIT 1
	";
}else{
	$sSqlWrk = "
	SELECT usuario_id
	FROM 
		usuario
	WHERE 
		usuario.usuario_rol_id = $x_destino_rol_id
	LIMIT 1
	";
}

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_usuario_id = $datawrk["usuario_id"];
@phpmkr_free_result($rswrk);
	
	
//Fecha Vencimiento
$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_ejecucion));	
$temptime = DateAdd('w',$x_dias_espera,$temptime);
$fecha_venc = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if(strtoupper($x_dia) == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_venc);

$sSql = "INSERT INTO crm_tarea values (0,".$x_crm_caso_id.", $x_orden_new, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($x_fecha_registro)."','".$x_hora_registro."','$fecha_venc',NULL,NULL,NULL, 1, 1, $x_destino_rol_id,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";

phpmkr_query($sSql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);


switch ($x_siguinte_tarea)
{
	case "1": // pp 
		$x_observaciones = "El usuario ha cambiado orden a PP.";
		break;
	case "2": // carta 1
		$x_observaciones = "El usuario ha cambiado orden a carta 1.";	
		break;
	case "3": // carta 2
		$x_observaciones = "El usuario ha cambiado orden a carta 2.";	
		break;
	case "4": // carta 3
		$x_observaciones = "El usuario ha cambiado orden a carta 3.";	
		break;
	case "5": // carta 4
		$x_observaciones = "El usuario ha cambiado orden a carta 4.";	
		break;
	case "6": // demana
		$x_observaciones = "El usuario ha cambiado orden a demanda.";	
		break;
	case "7": // resultado demana
		$x_observaciones = "El usuario ha cambiado orden a resultado de demanda.";	
		break;
	case "8": // cita actuario
		$x_observaciones = "El usuario ha cambiado orden a cita actuario.";	
		break;
	case "9": // recordatorio cita actuario
		$x_observaciones = "El usuario ha cambiado orden a recordatorio cita actuario.";	
		break;
	case "10": // embargo
		$x_observaciones = "El usuario ha cambiado orden a embargo.";	
		break;
	case "11": // incobrable
		$x_observaciones = "El usuario ha cambiado orden a incobrable.";	
		break;
}


bitacora($conn, $x_crm_caso_id, $x_fecha_registro, $x_hora_registro, $x_asunto, $x_descripcion, $x_destino_rol_id, $x_destino, $x_observaciones);
	
	
}



?>


<?php 


?>

