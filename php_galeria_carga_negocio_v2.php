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
	header("Location:  login.php");
	exit();
}
?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include("upload/php_galeria_cambia_tamano_imagen.php")?>
<?php include("limpiar_cadena_caracteres_raros.php");?>

<?php
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);	
if(isset($_GET["x_galeria_fotografica_id"])){
	$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
	loadData($conn,$x_galeria_fotografica_id);
	}
if(isset($_POST["x_envio"])){

	$x_galeria_fotografica_id = $_POST["x_galeria_fotografica_id"];
	$x_foto_negocio_1 = $_POST["x_foto_negocio_1"];
	$x_foto_negocio_2 = $_POST["x_foto_negocio_2"];
	$x_foto_negocio_3 = $_POST["x_foto_negocio_3"];
	$x_foto_negocio_4 = $_POST["x_foto_negocio_4"];
	$x_foto_negocio_5 = $_POST["x_foto_negocio_5"];
	$x_foto_negocio_6 = $_POST["x_foto_negocio_6"];
	$x_foto_negocio_7 = $_POST["x_foto_negocio_7"];
	$x_foto_negocio_8 = $_POST["x_foto_negocio_8"];
	$x_foto_negocio_9 = $_POST["x_foto_negocio_9"];
	$x_foto_negocio_10 = $_POST["x_foto_negocio_10"];	
	$x_nombre_galeria = $_POST["x_nombre_galeria"];
	
	
	if(!addData($conn)){
		echo "Erroe al intentar cargar las imagenes";
		die();
		}
		
		
			$x_mensaje = "<br><br><br><p align='center'><a href='javascript: window.close();' >Las imagenes se guardaron correctamente, continue con los demas documentos.<p><p align='center'> Click aqui para cerrar esta ventana.</a></p>";
	ob_end_clean();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<script type="text/javascript" src="ew.js"></script>
<script  type="text/javascript" src="checkFileExist.js"></script>
<script language="javascript">

function checkMyForm(){
EW_this = document.ife;
	validada = true;
	
	if(EW_this.x_foto_negocio_1.value.length == 0 && EW_this.x_foto_negocio_2.value.length == 0 && EW_this.x_foto_negocio_3.value.length == 0 &&EW_this.x_foto_negocio_4.value.length == 0  && EW_this.x_foto_negocio_5.value.length == 0 && EW_this.x_foto_negocio_6.value.length == 0 && EW_this.x_foto_negocio_7.value.length == 0 && EW_this.x_foto_negocio_8.value.length == 0 && EW_this.x_foto_negocio_9.value.length == 0  && EW_this.x_foto_negocio_10.value.length == 0  ){
		alert("Es necesario seleccionar por lo menos 1 archivo");
		validada = false;
		}
	
if(validada == true){
	
	EW_this.submit();
	}
}
</script>


<body>
<form name="ife" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="x_envio" value="1" />
<input type="hidden" name="x_nombre_galeria" value="<?php echo $x_nombre_galeria;?>"  />
<?php echo $x_mensaje; ?>
<?php if(empty($x_mensaje)){?>
<input type="hidden" name="x_galeria_fotografica_id" value="<?php echo $x_galeria_fotografica_id;?>"  />
<input type="hidden" name="x_nombre_galeria" value="<?php echo $x_nombre_galeria;?>"  />
<table width="700" border="0" align="center">
  <tr>
    <td height="48" colspan="3" background="images/headTupload.jpg" style="color:#FFFFFF"><strong><pre>	NEGOCIO</pre></strong></td>
  </tr>
  <tr background="images/headTUpload1.jpg">
    <td width="56" height="33">Foto 1</td>
    <td colspan="2">
    <?php if ((!is_null($x_foto_negocio_1)) && ($x_foto_negocio_1 <> "")) {  ?>
       <?php if($x_edita_fotos_existententes == 0){?>
       <input type="radio" name="a_x_negocio_1" value="1" checked><label>Mantener&nbsp;</label>
       <?php }else{ ?>
        <input type="radio" name="a_x_negocio_1" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_1" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_1" value="3">Cambiar<br />
        <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_1" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_1"  id="x_foto_negocio_1" onchange="if (this.form.a_x_negocio_1[2]) this.form.a_x_negocio_1[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg">
    <td colspan="2">&nbsp;</td>
    <td width="320"><input type="button" name="neg" id="neg"  onclick="checkMyForm();" value="Guardar" /></td>
  </tr>
</table>
<?php }?>
</form>
</body>
</html>
<?php


function loadData($conn, $id){
	$sqlS = "SELECT solicitud_id, tipo_galeria_id, nombre_galeria, foto_negocio_1, foto_negocio_2, foto_negocio_3, foto_negocio_4, foto_negocio_5, foto_negocio_6, foto_negocio_7, foto_negocio_8, foto_negocio_9, foto_negocio_10 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";

	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$rows= phpmkr_fetch_array($rsS);
	$GLOBALS["x_foto_negocio_1"] = $rows["foto_negocio_1"];
	$GLOBALS["x_foto_negocio_2"] = $rows["foto_negocio_2"];
	$GLOBALS["x_foto_negocio_3"] = $rows["foto_negocio_3"];
	$GLOBALS["x_foto_negocio_4"] = $rows["foto_negocio_4"];
	$GLOBALS["x_foto_negocio_5"] = $rows["foto_negocio_5"];
	$GLOBALS["x_foto_negocio_6"] = $rows["foto_negocio_6"];
	$GLOBALS["x_foto_negocio_7"] = $rows["foto_negocio_7"];
	$GLOBALS["x_foto_negocio_8"] = $rows["foto_negocio_8"];
	$GLOBALS["x_foto_negocio_9"] = $rows["foto_negocio_9"];
	$GLOBALS["x_foto_negocio_10"] = $rows["foto_negocio_10"];
	$GLOBALS["x_nombre_galeria"] = $rows["nombre_galeria"];
	echo $rows["nombre_galeria"]."----<br>";
	$x_tipo_galeria_id = $rows["tipo_galeria_id"];	
	
	if($x_tipo_galeria_id == 2){
		// la galeria es de un aval
		$GLOBALS["x_nombre_galeria"] = "AVAL_".$rows["nombre_galeria"];
		}
	
	$x_solicitud_id = $rows["solicitud_id"];
	#echo "<br>solo".$x_solicitud_id."<br>";
	mysql_free_result($rsS);
	//seleccionamos el cliente id
	if(!empty($x_solicitud_id)){
	$sqlClienteID = " SELECT * FROM solicitud_cliente WHERE solicitud_id = $x_solicitud_id";
	$rsClienteID = phpmkr_query($sqlClienteID,$conn)or die ("Error al seleccionar el cliente id".phpmkr_error()."sql;".$sqlClienteID);
	$rowCliente = phpmkr_fetch_array($rsClienteID);
	$x_cliente_id = $rowCliente["cliente_id"];	
	// seleccionamos todas la solicitudes de ese cliente
	$sqlSolictudes = " select * from solicitud_cliente where cliente_id = $x_cliente_id ";
	$rsSolcitudes = phpmkr_query($sqlSolictudes,$conn) or die ("Erro al seleccionar ".phpmkr_error()."sql: ".$sqlSolictudes);
	$x_listdo_sol = "";
	while($rowSolictude = phpmkr_fetch_array($rsSolcitudes)){
		$x_listdo_sol = $x_listdo_sol.$rowSolictude["solicitud_id"].", ";		
		}
	}
	$x_listdo_sol = trim($x_listdo_sol,", ");
	//echo "<br>listado de solictudes ".$x_listdo_sol."<br>";
	#seleccinamos todas la galerias que correspondan al cliente 
	 $GLOBALS["x_edita_fotos_existententes"] = 0;
	if( strlen($x_listdo_sol) > 1){
	 $sqlGaleria = "SELECT * FROM galeria_fotografica WHERE  solicitud_id in ($x_listdo_sol) and tipo_galeria_id =  $x_tipo_galeria_id ";
	 $rsGaleria = phpmkr_query($sqlGaleria,$conn)or die ("Error al seleccionar las galerias".phpmkr_error()."sql:". $sqlGaleria);
	 $x_numero_galerias = mysql_num_rows($rsGaleria);
	 
	 if($x_numero_galerias < 2){
		 // las fotos existentes no se pueden editar
		 $GLOBALS["x_edita_fotos_existententes"] = 1;
		 }
	}
	
	
	
	
	
	}
	
	
 function addData($conn){
	 
	 
	 
	 $x_galeria_fotografica_id = $_POST["x_galeria_fotografica_id"];
	 $a_x_negocio_1 = @$_POST["a_x_negocio_1"];
	 $a_x_negocio_2 = @$_POST["a_x_negocio_2"];
	 $a_x_negocio_3 = @$_POST["a_x_negocio_3"];
	 $a_x_negocio_4 = @$_POST["a_x_negocio_4"];
	 $a_x_negocio_5 = @$_POST["a_x_negocio_5"];
	 $a_x_negocio_6 = @$_POST["a_x_negocio_6"];
	 $a_x_negocio_7 = @$_POST["a_x_negocio_7"];
	 $a_x_negocio_8 = @$_POST["a_x_negocio_8"];
	 $a_x_negocio_9 = @$_POST["a_x_negocio_9"];
	 $a_x_negocio_10 = @$_POST["a_x_negocio_10"];
	 
	 
	 $x_nombre_galeria = $_POST["x_nombre_galeria"];	 
	 $x_hoy = date("Y-m-d");	 
	 $x_nombre_galeria = sanear_string($x_nombre_galeria);
	 $x_hoy = sanear_string($x_hoy);
	 $x_nombre_galeria =  $x_nombre_galeria."_". $x_hoy."_".$x_galeria_fotografica_id;
	
	
	
	 
	 
	 // documentos	
	$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_foto_negocio_1"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_foto_negocio_1"]["size"] > $EW_MaxFileSize) {
			die("El tamoño de la imagen es mayor al tamaño permitido, cambien la imagen por una en tamaño mediano o grande");
		}
	}
	
 
	$fieldList = NULL;
		$x_imagen1 = "";
		$x_imagen2 = "";
		$x_imagen3 = "";
		$x_imagen4 = "";
		$x_imagen5 = "";
		$x_imagen6 = "";
		$x_imagen7 = "";
		$x_imagen8 = "";
		$x_imagen9 = "";
		$x_imagen10 = "";
		
		// Field IFE 1
		
		if ($a_x_negocio_1 == "2") { // Remove
					$fieldList["`foto_negocio_1`"] = "NULL";
				} else if ($a_x_negocio_1 == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_1"]["tmp_name"])) {
					
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_foto_negocio_1"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "FOTO_NEGOCIO_1_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;					 				 
					 $destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_1"]["name"]);
					
					
					
							if (!move_uploaded_file($_FILES["x_foto_negocio_1"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
				
			$theName = (!get_magic_quotes_gpc()) ? addslashes($x_nuevo_nombre) : ewUploadFileName($x_nuevo_nombre);
			$fieldList["`foto_negocio_1`"] = " '" . $theName . "'"; 					
					$x_imagen1 = $theName;	
					@unlink($_FILES["x_foto_negocio_1"]["tmp_name"]);
				}
				}
			// Field IFE 2
				if ($a_x_negocio_2 == "2") { // Remove
					$fieldList["`foto_negocio_2`"] = "NULL";
				} else if ($a_x_negocio_2 == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_2"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_2"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_2"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_2"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_2"]["name"]);
			$fieldList["`foto_negocio_2`"] = " '" . $theName . "'"; 					
					$x_imagen2 = $theName;
							
					@unlink($_FILES["x_foto_negocio_2"]["tmp_name"]);
				}
				}
					// Field IFE 3
					
				if ($a_x_negocio_3 == "2") { // Remove
					$fieldList["`foto_negocio_3`"] = "NULL";
				} else if ($a_x_negocio_3 == "3") { // Update		
				if (is_uploaded_file($_FILES["x_foto_negocio_3"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_3"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_3"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_3"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_3"]["name"]);
			$fieldList["`foto_negocio_3`"] = " '" . $theName . "'"; 					
					$x_imagen3 = $theName;		
					@unlink($_FILES["x_foto_negocio_3"]["tmp_name"]);
				}
				}
					// Field IFE 4
					if ($a_x_negocio_4 == "2") { // Remove
					$fieldList["`foto_negocio_4`"] = "NULL";
				} else if ($a_x_negocio_4 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_foto_negocio_4"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_4"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_4"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_4"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_4"]["name"]);
			$fieldList["`foto_negocio_4`"] = " '" . $theName . "'"; 					
						$x_imagen4 = $theName;	
					@unlink($_FILES["x_foto_negocio_4"]["tmp_name"]);
				}
				}
				
					// Field IFE 1
					
					if ($a_x_negocio_5 == "2") { // Remove
					$fieldList["`foto_negocio_5`"] = "NULL";
				} else if ($a_x_negocio_5 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_foto_negocio_5"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_5"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_5"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_5"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_5"]["name"]);
			$fieldList["`foto_negocio_5`"] = " '" . $theName . "'"; 					
						$x_imagen5 = $theName;	
					@unlink($_FILES["x_foto_negocio_5"]["tmp_name"]);
				}		
				}
	 
	 
	 if ($a_x_negocio_6 == "2") { // Remove
					$fieldList["`foto_negocio_6`"] = "NULL";
				} else if ($a_x_negocio_6 == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_6"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_6"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_6"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_6"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_6"]["name"]);
			$fieldList["`foto_negocio_6`"] = " '" . $theName . "'"; 					
					$x_imagen6 = $theName;	
					@unlink($_FILES["x_foto_negocio_1"]["tmp_name"]);
				}
				}
			// Field IFE 2
				if ($a_x_negocio_7 == "2") { // Remove
					$fieldList["`foto_negocio_7`"] = "NULL";
				} else if ($a_x_negocio_7 == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_7"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_7"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_7"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_7"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_7"]["name"]);
			$fieldList["`foto_negocio_7`"] = " '" . $theName . "'"; 					
					$x_imagen7 = $theName;
							
					@unlink($_FILES["x_foto_negocio_7"]["tmp_name"]);
				}
				}
					// Field IFE 3
					
				if ($a_x_negocio_8 == "2") { // Remove
					$fieldList["`foto_negocio_8`"] = "NULL";
				} else if ($a_x_negocio_8 == "3") { // Update		
				if (is_uploaded_file($_FILES["x_foto_negocio_8"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_8"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_8"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_8"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_8"]["name"]);
			$fieldList["`foto_negocio_8`"] = " '" . $theName . "'"; 					
					$x_imagen8 = $theName;		
					@unlink($_FILES["x_foto_negocio_8"]["tmp_name"]);
				}
				}
					// Field IFE 4
					if ($a_x_negocio_9 == "2") { // Remove
					$fieldList["`foto_negocio_9`"] = "NULL";
				} else if ($a_x_negocio_9 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_foto_negocio_9"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_9"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_9"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_9"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_9"]["name"]);
			$fieldList["`foto_negocio_9`"] = " '" . $theName . "'"; 					
						$x_imagen9 = $theName;	
					@unlink($_FILES["x_foto_negocio_9"]["tmp_name"]);
				}
				}
				
			// Field IFE 6
					
					if ($a_x_negocio_10 == "2") { // Remove
					$fieldList["`foto_negocio_10`"] = "NULL";
				} else if ($a_x_negocio_10 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_foto_negocio_10"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_10"]["name"]);
							if (!move_uploaded_file($_FILES["x_foto_negocio_10"]["tmp_name"], $destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto_negocio_10"]["name"])) : ewUploadFileName($_FILES["x_foto_negocio_10"]["name"]);
			$fieldList["`foto_negocio_10`"] = " '" . $theName . "'"; 					
						$x_imagen10 = $theName;	
					@unlink($_FILES["x_foto_negocio_10"]["tmp_name"]);
				}		
				}
	 
	 // MODIFICAMOS EL REGISTRO EN LA TABLA PARA GUARDAR LAS FOTOS
	 // update
		$sSql = "UPDATE `galeria_fotografica` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE  galeria_fotografica_id = $x_galeria_fotografica_id";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		// ya que se inserto en la base de datos cambiamos el tamaño de las imagenes usamos la fucion de cambia imagen
	
		if(!empty($x_imagen1)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen1) or die();			
			}		
		if(!empty($x_imagen2)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen2);			
			}
		if(!empty($x_imagen3)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen3);			
			}
		if(!empty($x_imagen4)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen4);			
			}
		if(!empty($x_imagen5)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen5);			
			}	
			
		if(!empty($x_imagen6)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen6);			
			}		
		if(!empty($x_imagen7)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen7);			
			}
		if(!empty($x_imagen8)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen8);			
			}
		if(!empty($x_imagen9)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen9);			
			}
		if(!empty($x_imagen10)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen10);			
			}			 
	 
	 
	 die();
	 return true;
	 }
?>