<?php session_start(); ?>
<?php ob_start(); ?>

<?php include("admin/db.php"); ?>
<?php include("admin/phpmkrfn.php"); ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];
$x_preventa = true;

$x_msg = "";





if($_POST["envio"] == "datosEnviados"){
	
	// Get fields from form
	$x_creditoSolidario_id = @$_POST["x_creditoSolidario_id"];
	$x_nombre_grupo = @$_POST["x_nombre_grupo"];
	$x_promotor = @$_POST["x_promotor"];
	$x_representante_sugerido = @$_POST["x_representante_sugerido"];
	$x_tesorero = @$_POST["x_tesorero"];
	$x_numero_integrantes = @$_POST["x_numero_integrantes"];
	$x_integrante_1 = @$_POST["x_integrante_1"];
	$x_monto_1 = @$_POST["x_monto_1"];
	$x_integrante_2 = @$_POST["x_integrante_2"];
	$x_monto_2 = @$_POST["x_monto_2"];
	$x_integrante_3 = @$_POST["x_integrante_3"];
	$x_monto_3 = @$_POST["x_monto_3"];
	$x_integrante_4 = @$_POST["x_integrante_4"];
	$x_monto_4 = @$_POST["x_monto_4"];
	$x_integrante_5 = @$_POST["x_integrante_5"];
	$x_monto_5 = @$_POST["x_monto_5"];
	$x_integrante_6 = @$_POST["x_integrante_6"];
	$x_monto_6 = @$_POST["x_monto_6"];
	$x_integrante_7 = @$_POST["x_integrante_7"];
	$x_monto_7 = @$_POST["x_monto_7"];
	$x_integrante_8 = @$_POST["x_integrante_8"];
	$x_monto_8 = @$_POST["x_monto_8"];
	$x_integrante_9 = @$_POST["x_integrante_9"];
	$x_monto_9 = @$_POST["x_monto_9"];
	$x_integrante_10 = @$_POST["x_integrante_10"];
	$x_monto_10 = @$_POST["x_monto_10"];
	$x_monto_total = @$_POST["x_monto_total"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	
	//FORMAR ARRAY CON VALORES DE LOS CAMPOS PARA INSERTAR EN LA BASE DE DATOS
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	
	// Field nombre_grupo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_grupo"]) : $GLOBALS["x_nombre_grupo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_grupo`"] = $theValue;

	// Field promotor
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_promotor"]) : $GLOBALS["x_promotor"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`promotor`"] = $theValue;

	// Field representante_sugerido
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_representante_sugerido"]) : $GLOBALS["x_representante_sugerido"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`representante_sugerido`"] = $theValue;

	// Field tesorero
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tesorero"]) : $GLOBALS["x_tesorero"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tesorero`"] = $theValue;

	// Field numero_integrantes
	$theValue = ($GLOBALS["x_numero_integrantes"] != "") ? intval($GLOBALS["x_numero_integrantes"]) : "NULL";
	$fieldList["`numero_integrantes`"] = $theValue;

	// Field integrante_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_1"]) : $GLOBALS["x_integrante_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_1`"] = $theValue;

	// Field monto_1
	$theValue = ($GLOBALS["x_monto_1"] != "") ? " '" . $GLOBALS["x_monto_1"] . "'" : "NULL";
	$fieldList["`monto_1`"] = $theValue;

	// Field integrante_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_2"]) : $GLOBALS["x_integrante_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_2`"] = $theValue;

	// Field monto_2
	$theValue = ($GLOBALS["x_monto_2"] != "") ? " '" . $GLOBALS["x_monto_2"] . "'" : "NULL";
	$fieldList["`monto_2`"] = $theValue;

	// Field integrante_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_3"]) : $GLOBALS["x_integrante_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_3`"] = $theValue;

	// Field monto_3
	$theValue = ($GLOBALS["x_monto_3"] != "") ? " '" . $GLOBALS["x_monto_3"] . "'" : "NULL";
	$fieldList["`monto_3`"] = $theValue;

	// Field integrante_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_4"]) : $GLOBALS["x_integrante_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_4`"] = $theValue;

	// Field monto_4
	$theValue = ($GLOBALS["x_monto_4"] != "") ? " '" . $GLOBALS["x_monto_4"] . "'" : "NULL";
	$fieldList["`monto_4`"] = $theValue;

	// Field integrante_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_5"]) : $GLOBALS["x_integrante_5"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_5`"] = $theValue;

	// Field monto_5
	$theValue = ($GLOBALS["x_monto_5"] != "") ? " '" . $GLOBALS["x_monto_5"] . "'" : "NULL";
	$fieldList["`monto_5`"] = $theValue;

	// Field integrante_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_6"]) : $GLOBALS["x_integrante_6"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_6`"] = $theValue;

	// Field monto_6
	$theValue = ($GLOBALS["x_monto_6"] != "") ? " '" . $GLOBALS["x_monto_6"] . "'" : "NULL";
	$fieldList["`monto_6`"] = $theValue;

	// Field integrante_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_7"]) : $GLOBALS["x_integrante_7"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_7`"] = $theValue;

	// Field monto_7
	$theValue = ($GLOBALS["x_monto_7"] != "") ? " '" . $GLOBALS["x_monto_7"] . "'" : "NULL";
	$fieldList["`monto_7`"] = $theValue;

	// Field integrante_8
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_8"]) : $GLOBALS["x_integrante_8"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_8`"] = $theValue;

	// Field monto_8
	$theValue = ($GLOBALS["x_monto_8"] != "") ? " '" . $GLOBALS["x_monto_8"] . "'" : "NULL";
	$fieldList["`monto_8`"] = $theValue;

	// Field integrante_9
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_9"]) : $GLOBALS["x_integrante_9"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_9`"] = $theValue;

	// Field monto_9
	$theValue = ($GLOBALS["x_monto_9"] != "") ? " '" . $GLOBALS["x_monto_9"] . "'" : "NULL";
	$fieldList["`monto_9`"] = $theValue;

	// Field integrante_10
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_10"]) : $GLOBALS["x_integrante_10"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_10`"] = $theValue;

	// Field monto_10
	$theValue = ($GLOBALS["x_monto_10"] != "") ? " '" . $GLOBALS["x_monto_10"] . "'" : "NULL";
	$fieldList["`monto_10`"] = $theValue;

	// Field monto_total
	$theValue = ($GLOBALS["x_monto_total"] != "") ? " '" . $GLOBALS["x_monto_total"] . "'" : "NULL";
	$fieldList["`monto_total`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
	$fieldList["`fecha_registro`"] = $theValue;

	// insert into database
	$strsql = "INSERT INTO `creditosolidario` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn);
	
	$_SESSION["mensajeAlta"] = "REGISTRO ALTA EXITOSA" ;	
	
	
	}// fin datos enviados
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cr&eacute;dito Solidadrio</title>
<link rel="stylesheet" type="text/css" href="../cssFormatos/cssFormatos.css" />
<script type="text/javascript" src="admin/ew.js"></script>
<script language="javascript">
window.onload = function (){
	
	document.getElementById("enviarFormulario").onclick = EW_checkMyForm;
	document.getElementById("x_nombre_grupo").onchange = aMayus;
	document.getElementById("x_promotor").onchange = aMayus;
	document.getElementById("x_representante_sugerido").onchange = aMayus;
	document.getElementById("x_tesorero").onchange = aMayus;
	document.getElementById("x_integrante_1").onchange = aMayus;
	document.getElementById("x_integrante_2").onchange = aMayus;
	document.getElementById("x_integrante_3").onchange = aMayus;
	document.getElementById("x_integrante_4").onchange = aMayus;
	document.getElementById("x_integrante_5").onchange = aMayus;
	document.getElementById("x_integrante_6").onchange = aMayus;
	document.getElementById("x_integrante_7").onchange = aMayus;
	document.getElementById("x_integrante_8").onchange = aMayus;
	document.getElementById("x_integrante_9").onchange = aMayus;
	document.getElementById("x_integrante_10").onchange = aMayus;
	document.getElementById("x_monto_1").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_2").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_3").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_4").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_5").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_6").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_7").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_8").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_9").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_10").onchange = actualizaImporteTotal;
	
	
	
	
	function actualizaImporteTotal(){
		 impoT = parseFloat(document.getElementById("x_monto_total").value);
		 suma = 0;
		 t1 = document.getElementById("x_monto_1").value;
		 t2 = document.getElementById("x_monto_2").value;
		 t3 = document.getElementById("x_monto_3").value;
		 t4 = document.getElementById("x_monto_4").value;
		 t5 = document.getElementById("x_monto_5").value;
		 t6 = document.getElementById("x_monto_6").value;
		 t7 = document.getElementById("x_monto_7").value;
		 t8 = document.getElementById("x_monto_8").value;
		 t9 = document.getElementById("x_monto_9").value;
		 t10 = document.getElementById("x_monto_10").value;
		 if(t1 == ''){			
			m1 = 0; }else{
			m1 = parseFloat(document.getElementById("x_monto_1").value);
			}
		if(t2 == ''){			
			m2 = 0; }else{
			m2 = parseFloat(document.getElementById("x_monto_2").value);
			}
		if(t3 == ''){			
			m3 = 0; }else{
			m3 = parseFloat(document.getElementById("x_monto_3").value);
			}
		if(t4 == ''){			
			m4 = 0; }else{
			m4 = parseFloat(document.getElementById("x_monto_4").value);
			}
		if(t5 == ''){			
			m5 = 0; }else{
			m5 = parseFloat(document.getElementById("x_monto_5").value);
			}
		if(t6 == ''){			
			m6 = 0; }else{
			m6 = parseFloat(document.getElementById("x_monto_6").value);
			}
		if(t7 == ''){			
			m7 = 0; }else{
			m7 = parseFloat(document.getElementById("x_monto_7").value);
			}
		if(t8 == ''){			
			m8 = 0; }else{
			m8 = parseFloat(document.getElementById("x_monto_8").value);
			}
		 
		
		if(t9 == ''){			
			m9 = 0; }else{
			m9 = parseFloat(document.getElementById("x_monto_9").value);
			}
		if(t10 == ''){			
			m10 = 0; }else{
			m10 = parseFloat(document.getElementById("x_monto_10").value);
			}
		
		suma = m1 + m2 + m3 +m4 +m5 +m6 +m7 +m8 +m9 +m10;	
		document.getElementById("x_monto_total").value = suma;
		}
	

function EW_checkMyForm() {
	
	EW_this = document.frmSolidario;
	validada = true;
	
if (EW_this.x_nombre_grupo && !EW_hasValue(EW_this.x_nombre_grupo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_grupo, "TEXT", "El sigueinete campo es requerido - nombre grupo"))
		validada = false;
}
if (EW_this.x_promotor && !EW_hasValue(EW_this.x_promotor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor, "TEXT", "El siguiente campo es requerido - promotor"))
		validada = false;
}
if (EW_this.x_representante_sugerido && !EW_hasValue(EW_this.x_representante_sugerido, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_representante_sugerido, "TEXT", "El siguiente campo es requerido - representante sugerido"))
		validada = false;
}
if (EW_this.x_tesorero && !EW_hasValue(EW_this.x_tesorero, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tesorero, "TEXT", "El siguiente campo es requerido - tesorero"))
		validada = false;
}
if (EW_this.x_numero_integrantes && !EW_hasValue(EW_this.x_numero_integrantes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "El siguiente campo es requerido - numero integrantes"))
		validada = false;
}
if (EW_this.x_numero_integrantes && !EW_checkinteger(EW_this.x_numero_integrantes.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "Este campo acepta valor numerico - numero integrantes"))
		validada = false; 
}
if (EW_this.x_monto_1 && !EW_checknumber(EW_this.x_monto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_1, "TEXT", "Incorrect floating point number - monto 1"))
		validada = false; 
}
if (EW_this.x_monto_2 && !EW_checknumber(EW_this.x_monto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_2, "TEXT", "Incorrect floating point number - monto 2"))
		validada = false; 
}
if (EW_this.x_monto_3 && !EW_checknumber(EW_this.x_monto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_3, "TEXT", "Incorrect floating point number - monto 3"))
		validada = false; 
}
if (EW_this.x_monto_4 && !EW_checknumber(EW_this.x_monto_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_4, "TEXT", "Incorrect floating point number - monto 4"))
		validada = false; 
}
if (EW_this.x_monto_5 && !EW_checknumber(EW_this.x_monto_5.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_5, "TEXT", "Incorrect floating point number - monto 5"))
		validada = false; 
}
if (EW_this.x_monto_6 && !EW_checknumber(EW_this.x_monto_6.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_6, "TEXT", "Incorrect floating point number - monto 6"))
		validada = false; 
}
if (EW_this.x_monto_7 && !EW_checknumber(EW_this.x_monto_7.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_7, "TEXT", "Incorrect floating point number - monto 7"))
		validada = false; 
}
if (EW_this.x_monto_8 && !EW_checknumber(EW_this.x_monto_8.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_8, "TEXT", "Incorrect floating point number - monto 8"))
		validada = false; 
}
if (EW_this.x_monto_9 && !EW_checknumber(EW_this.x_monto_9.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_9, "TEXT", "Incorrect floating point number - monto 9"))
		validada = false; 
}
if (EW_this.x_monto_10 && !EW_checknumber(EW_this.x_monto_10.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_10, "TEXT", "Incorrect floating point number - monto 10"))
		validada = false; 
}
if (EW_this.x_monto_total && !EW_checknumber(EW_this.x_monto_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_total, "TEXT", "Incorrect floating point number - monto total"))
		validada = false; 
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "El siguiente campo es requerido - fecha registro"))
		validada = false;
}




if(validada == true){
	EW_this.submit();	
	 alert("documento enviado");
	}

}// ew_form

	}//window.onload




</script>

</head>

<body>
<div id="contenedor" >
<div id="paginaUno">
<!--
<form action="old_creditoSolidario.php" method="post" name="frmSolidario" id="frmSolidario"> -->
<input type="hidden" id="envio" name="envio" value="datosEnviados" />
<table width="70%" id="pagina1">
  <tr>
    <td  id="tableHead" colspan="7"><img src="images/grupo.png" /><p></td>
  </tr>
  <tr> <td colspan="2">Nombre Grupo</td>
    <td colspan="5"><label>
      <input type="text" name="x_nombre_grupo" id="x_nombre_grupo" maxlength="250" size="80" />
    </label></td>
  </tr>
  <tr>
    <td colspan="2">Promotor</td>
    <td colspan="5"><label>
      <input type="text" name="x_promotor" id="x_promotor" maxlength="250" size="80"/>
    </label></td>
  </tr>
  <tr>
    <td colspan="2">Representante Sugerido</td>
    <td colspan="5">
      <input type="text" name="x_representante_sugerido" id="x_representante_sugerido" maxlength="250" size="80"/>
    </td>
  </tr>
  <tr>
    <td colspan="2">Tesorero</td>
    <td colspan="5">
      <input type="text" name="x_tesorero" id="x_tesorero" maxlength="250" size="80" /> 
    </td>
  </tr>
  <tr>
    <td colspan="7" id="tableHead"><p><img src="images/integrantesGrupo.png" /><p></td>
  </tr>
  <tr>
    <td colspan="2">Numero de Integrantes</td>
    <td colspan="5">
      <input type="text" name="x_numero_integrantes" id="x_numero_integrantes" size="15" />
    </td>
  </tr>
  <tr>
    <td height="24" colspan="3">Nombre</td>
    <td width="13%">Monto</td>
    <td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="18%">&nbsp;</td>
  </tr>
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="10%">Integrante 1</td>
    <td width="52%"><input type="text" id="x_integrante_1" name="x_integrante_1" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_1" id="x_monto_1" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 2</td>
    <td><input type="text" id="x_integrante_2" name="x_integrante_2" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_2" id="x_monto_2" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 3</td>
    <td><input type="text" id="x_integrante_3" name="x_integrante_3" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_3" id="x_monto_3" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 4</td>
    <td><input type="text" id="x_integrante_4" name="x_integrante_4" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_4" id="x_monto_4" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 5</td>
    <td><input type="text" id="x_integrante_5" name="x_integrante_5" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_5" id="x_monto_5" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 6</td>
    <td><input type="text" id="x_integrante_6" name="x_integrante_6" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_6" id="x_monto_6" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 7</td>
    <td><input type="text" id="x_integrante_7" name="x_integrante_7" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_7" id="x_monto_7" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 8</td>
    <td><input type="text" id="x_integrante_8" name="x_integrante_8" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_8" id="x_monto_8" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>Integrante 9</td>
    <td><input type="text" id="x_integrante_9" name="x_integrante_9" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_9" id="x_monto_9" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>Integrante 10</td>
    <td><input type="text" id="x_integrante_10" name="x_integrante_10" maxlength="250" size="80" /></td>
    <td><input type="text" name="x_monto_10" id="x_monto_10" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Importe Total :</td>
    <td><input type="text" name="x_monto_total" id="x_monto_total" size="18" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
     <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" id="enviarFormulario" name="enviarFormulario" value="Enviar" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

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

<!--</form>-->
</div><!--paginaUno-->
</div><!-- contenedor-->
</body>
</html>