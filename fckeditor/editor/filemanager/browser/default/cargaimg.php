<?php include ("phpmkrfn.php") ?>
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


// Initialize common variables
$x_logotipo = Null; 
$ox_logotipo = Null;
$fs_x_logotipo = 0;
$fn_x_logotipo = "";
$ct_x_logotipo = "";
$w_x_logotipo = 0;
$h_x_logotipo = 0;
$a_x_logotipo = "";
?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

	$bCopy = false;

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
	$x_logotipo = @$_POST["x_logotipo"];
}
switch ($sAction)
{
	case "A": // Add
		if (AddData($conn)) { // Add New Record
//			echo "La imagen ha sido cargada al servidor.<br><br><a href='javascript: window.close();'>Cerrar esta ventana</a>";

			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.location = 'frmupload.html';		
			window.close(); 	
			//-->
			</script>";

			
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carga de imagenes</title>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
}
.Estilo2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
-->
</style>
</head>

<body>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_logotipo && !EW_hasValue(EW_this.x_logotipo, "FILE" )) {
	if (!EW_onError(EW_this, EW_this.x_logotipo, "FILE", "Seleccione la imagen a ser cargada."))
		return false;
}
alert("Espera a que la imagen sea cargada, presione l boton aceptar para continuar.");
return true;
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker"><span class="Estilo1">Carga de imagenes</span><br>
  <br>
    <a href="javascript: window.close();" class="Estilo2">Cerrar esta ventana</a></span></p>
<form name="aerolineaadd" id="aerolineaadd" action="cargaimg.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="EW_Max_File_Size" value="10000000">
<table class="ewTable">
	
	<tr>
		<td class="Estilo2">Archivo de imagen:</td>
	  <td class="ewTableAltRow"><span>
<?php $x_logotipo = ""; // Clear BLOB related fields ?>
<input name="x_logotipo" type="file" class="Estilo2" id="x_logotipo" size="30">
</span></td>
	</tr>
</table>
<p>
<input name="Action" type="submit" class="Estilo1" value="Cargar al servidor">
</form>
</body>
</html>

<?php
//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_logotipo"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_logotipo"]["size"] > $EW_MaxFileSize) {
			die("El tamaño maximo ha sido excedido.");
		}
	}


	// Field logotipo
		if (is_uploaded_file($_FILES["x_logotipo"]["tmp_name"])) {
//			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_logotipo"]["name"]);
			$destfile = "\\\\172.21.6.23\\s010\\exposystems.com.mx\\portal\\userfiles\\image\\" . ewUploadFileName($_FILES["x_logotipo"]["name"]);			
					if (!move_uploaded_file($_FILES["x_logotipo"]["tmp_name"], $destfile)) // move file to destination path
					die("No fue posible cargar el archivo.");

			// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_logotipo"]["name"])) : ewUploadFileName($_FILES["x_logotipo"]["name"]);
			$fieldList["`logotipo`"] = " '" . $theName . "'";
			@unlink($_FILES["x_logotipo"]["tmp_name"]);
		}

	return true;
}
?>
