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
	header("Location: login.php");
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
$currdate = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];
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

$bCopy = true;
$x_reporte_cnv_archivos_id = @$_GET["id"];
//echo "solicitud_id = ".$x_solicitud_id." ";
if (($x_solicitud_id == "") || (is_null($x_solicitud_id))) { 
$x_solicitud_id= @$_POST["x_solicitud_id"]; }



if (!empty($x_solicitud_id)) {
	$bCopy = false;
}

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
// Get fields from form
//hiddens

foreach($_POST as $campo => $valor){
	$$campo = $valor;
	}

}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			echo "resultado:".$_SESSION["ewmsg"]."";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_reporte_cnbv_archivolist.php");
			exit();
		}
		break;
	case "U": // Add
		if (EditData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Actualizado";
			phpmkr_db_close($conn);
			ob_end_clean();		 
			header("Location: php_reporte_cnbv_archivolist.php");
			exit();
		}
		
}
?>




<?php include("header.php"); ?>
<?php include("utilerias/fecha_letras.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<script type="text/javascript" src="ew.js"></script>
<script src="paisedohint.js"></script> 
<script language="javascript" src="checkFileExist.js"></script>
<script src="muestra_dir_empresa.js"></script>
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

<script type="text/javascript" src="../scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.themeswitcher.js"></script>

<script>
$(document).ready(function() {
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
	
	});
</script>

<script type="text/javascript">
<!-- funciones java script -->


function show_address(empresa_id){
	x_empresa_id = empresa_id.value;
	process(x_empresa_id);	
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
EW_this = document.solicitudadd;
validada = true;


if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique Promotor."))
		validada = false;
}

if (validada == true && EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Indique su Nombre."))
		validada = false;
}

if (validada == true && EW_this.x_paterno && !EW_hasValue(EW_this.x_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_paterno, "TEXT", "Indique su Apellido Paterno."))
		validada = false;
}






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
<form name="solicitudadd" id="solicitudadd" action=""    enctype="multipart/form-data" method="post" >
  <input type="hidden" name="a_add" value="U" />
  <input type="hidden" name="x_reporte_cnv_archivos_id" value="<?php echo $x_reporte_cnv_archivos_id ?>" />
  
  
  <?php
if (@$_SESSION["ewmsg"] <> "") {
		
?>
  <p><span class="ewmsg">
    <?php // echo $_SESSION["ewmsg"] ?>
  </span></p>
  <?php
	//$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<a href="php_reporte_cnbv_archivolist.php">Regresar al listado</a>
  <p></p>
  <p></p>
  <p></p>
  <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="texto_normal_SIP">
   <tr>
      <td colspan="7" valign="top" class="encabezado_crea">ARCHIVO EMITIDO POR LA CNBV</td>
    </tr>
    
	
      <tr>
        <td colspan="7" class="encabezado_sip">&nbsp;</td>
      </tr>
     <tr>
      <td width="123">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="117">&nbsp;</td>
      <td width="285" colspan="2" align="right">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      </tr>
    
      
       <tr>
         <td colspan="3" align="right" class="linea_sip">CNBV</td>
         <td colspan="4" align="left" class="linea_sip">
           <?php if ((!is_null($x_archivo_cnbv)) && $x_archivo_cnbv <> "") {  ?>
           <input type="radio" name="a_x_archivo_cnbv" value="1" checked><label>Mantener&nbsp;</label>
           <input type="radio" name="a_x_archivo_cnbv" value="2" disabled><label>Eliminar&nbsp;</label>
           <input type="radio" name="a_x_archivo_cnbv" value="3">
           Cambiar
           <br />
           <?php } else {?>
           <input type="hidden" name="a_x_archivo_cnbv" value="3">
           <?php } ?><input type="file" name="x_archivo_cnbv" id="x_archivo_cnbv" onChange="if (this.form.a_x_archivo_cnbv[2]) this.form.a_x_archivo_cnbv[2].checked=true;checkFile(this.value,this.name,'sub');"/>
           <?php echo "<br>". $x_archivo_cnbv;?></td>
       </tr>

    
    
    
    
     <tr>
       <td width="123">&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td width="285" colspan="2">&nbsp;</td>
       <td width="114">&nbsp;</td>
       <td width="120"><input type="button" name="sub" id="sub"  value="GUARDAR" onClick="EW_checkMyForm();" /></td>
     </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<?php include ("footer.php") ?>

<?php
//phpmkr_db_close($conn);
?>
<?php 

function LoadData($conn){
	
	$x_load_data = true;
	 //echo "entro a load data...sol id=".$GLOBALS["x_solicitud_id"]."-";

		
	
		
		//documentos
		$sqlD  = "SELECT * FROM reporte_cnv_archivos WHERE reporte_cnv_archivos_id = ".$GLOBALS["x_reporte_cnv_archivos_id"]."";
		$rs = phpmkr_query($sqlD,$conn) or die("Error al seleccionar los documentos".phpmkr_error()."sql:".$sqlD);
		if(!$rs ){
			$x_load_data  = false;
			}
		$row = phpmkr_fetch_array($rs);
	
		$GLOBALS["x_archivo_cnbv"] = $row["archivo_cnbv"];
		echo $row["archivo_cnbv"];
		
		phpmkr_free_result($rs);
		
		
	
		
		
		
		
		
		
		
	return  $x_load_data;
	
	}// load data


function EditData($conn){	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');	
	$x_add_data = true;	
	
		if (!empty($_FILES["x_archivo_cnbv"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_archivo_cnbv"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}
	
	$fieldList = NULL;	
	$a_x_archivo_cnbv = @$_POST["a_x_archivo_cnbv"];
	
			// Field x_nomina_1
				if (is_uploaded_file($_FILES["x_archivo_cnbv"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_archivo_cnbv"]["name"]);
					$destfile = "reportes_cnbv/cnbv/". ewUploadFileName($_FILES["x_archivo_cnbv"]["name"]);
							if (!move_uploaded_file($_FILES["x_archivo_cnbv"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_archivo_cnbv"]["name"])) : ewUploadFileName($_FILES["x_archivo_cnbv"]["name"]);
			$fieldList["`archivo_cnbv`"] = " '" . $theName . "'"; 					
							
					@unlink($_FILES["x_archivo_cnbv"]["tmp_name"]);
								}
				
				
	// update
									$sSql = "UPDATE `reporte_cnv_archivos` SET ";
									foreach ($fieldList as $key=>$temp) {
										$sSql .= "$key = $temp, ";
									}
									if (substr($sSql, -2) == ", ") {
										$sSql = substr($sSql, 0, strlen($sSql)-2);
									}
									$sSql .= " WHERE 	reporte_cnbv_id = ".$GLOBALS["x_reporte_cnv_archivos_id"];
									echo $sSql;
									$x_result = phpmkr_query($sSql,$conn);
									if(!$x_result){
										echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
										phpmkr_query('rollback;', $conn);	 
										//exit();
									}
									
									
		
	phpmkr_query('commit;', $conn);	 
	
	return $x_add_data;	
	}
?>