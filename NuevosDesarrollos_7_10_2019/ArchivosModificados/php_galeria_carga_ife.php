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
	$x_identificacion_oficial_1 = $_POST["x_identificacion_oficial_1"];
	$x_identificacion_oficial_2 = $_POST["x_identificacion_oficial_2"];
	$x_identificacion_oficial_3 = $_POST["x_identificacion_oficial_3"];
	$x_identificacion_oficial_4 = $_POST["x_identificacion_oficial_4"];
	$x_identificacion_oficial_5 = $_POST["x_identificacion_oficial_5"];
	
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
	
	if(EW_this.x_identificacion_oficial_1.value.length == 0 && EW_this.x_identificacion_oficial_2.value.length == 0 && EW_this.x_identificacion_oficial_3.value.length == 0 &&EW_this.x_identificacion_oficial_4.value.length == 0  && EW_this.x_identificacion_oficial_5.value.length == 0   ){
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

<?php echo $x_mensaje; ?>
<?php if(empty($x_mensaje)){?>
<input type="hidden" name="x_galeria_fotografica_id" value="<?php echo $x_galeria_fotografica_id;?>"  />
<input type="hidden" name="x_nombre_galeria" value="<?php echo $x_nombre_galeria;?>"  />
<table width="700" border="0" align="center">
  <tr>
    <td height="48" colspan="3" background="images/headTupload.jpg" style="color:#FFFFFF"><strong><pre>	IDENTIFICAIC&Oacute;N OFICIAL</pre></strong></td>
  </tr>
  <tr background="images/headTUpload1.jpg">
    <td width="56" height="33">Foto 1</td>
    <td colspan="2">
    <?php if ((!is_null($x_identificacion_oficial_1)) && $x_identificacion_oficial_1 <> "") {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>    
        <input type="radio" name="a_x_ife_1" value="1" checked><label>Mantener&nbsp;</label>
       <?php }else{ ?>       
        <input type="radio" name="a_x_ife_1" value="1" checked><label>Mantener&nbsp;</label> 
        <input type="radio" name="a_x_ife_1" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_ife_1" value="3">Cambiar<br />
        <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_ife_1" value="3">
        <?php } ?>
    <input type="file" name="x_identificacion_oficial_1"  id="x_identificacion_oficial_1" onchange="if (this.form.a_x_ife_1[2]) this.form.a_x_ife_1[2].checked=true;checkFile(this.value,this.name,'ife');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg">
    <td height="33">Foto 2</td>
    <td colspan="2">
     <?php if ((!is_null($x_identificacion_oficial_2)) && $x_identificacion_oficial_2 <> "") {  ?>
      <?php if($x_edita_fotos_existententes == 0){?> 
        <input type="radio" name="a_x_ife_2" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>  
        <input type="radio" name="a_x_ife_2" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_ife_2" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_ife_2" value="3">Cambiar<br />
           <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_ife_2" value="3">
        <?php } ?>
    <input type="file" name="x_identificacion_oficial_2" id="x_identificacion_oficial_2" onchange="if (this.form.a_x_ife_2[2]) this.form.a_x_ife_2[2].checked=true;checkFile(this.value,this.name,'ife');" /></td>
  </tr>
  <tr background="images/headTUpload1.jpg">
    <td height="33">Foto 3</td>
    <td colspan="2">
     <?php if ((!is_null($x_identificacion_oficial_3)) && $x_identificacion_oficial_3 <> "") {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>      
        <input type="radio" name="a_x_ife_3" value="1" checked><label>Mantener&nbsp;</label>
         <?php }else{ ?>  
         <input type="radio" name="a_x_ife_3" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_ife_3" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_ife_3" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_ife_3" value="3">
        <?php } ?>
    <input type="file" name="x_identificacion_oficial_3"  id="x_identificacion_oficial_3" onchange="if (this.form.a_x_ife_3[2]) this.form.a_x_ife_3[2].checked=true;checkFile(this.value,this.name,'ife');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg">
    <td height="33">Foto 4</td>
    <td colspan="2">
     <?php if ((!is_null($x_identificacion_oficial_4)) && $x_identificacion_oficial_4 <> "") {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>       
        <input type="radio" name="a_x_ife_4" value="1" checked><label>Mantener&nbsp;</label>
           <?php }else{ ?>          
          <input type="radio" name="a_x_ife_4" value="1" checked><label>Mantener&nbsp;</label> 
        <input type="radio" name="a_x_ife_4" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_ife_4" value="3">Cambiar<br />
        <?php }?>
        <?php } else {?>
        
        <input type="hidden" name="a_x_ife_4" value="3">
        <?php } ?>
    <input type="file" name="x_identificacion_oficial_4" id="x_identificacion_oficial_4"  onchange="if (this.form.a_x_ife_4[2]) this.form.a_x_ife_4[2].checked=true;checkFile(this.value,this.name,'ife');"/></td>
  </tr>
  <tr background="images/headTUpload1.jpg">
    <td height="33">Foto 5</td>
    <td colspan="2">
     <?php if ((!is_null($x_identificacion_oficial_5)) && $x_identificacion_oficial_5 <> "") {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>  
        <input type="radio" name="a_x_ife_5" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>    
        <input type="radio" name="a_x_ife_5" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_ife_5" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_ife_5" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_ife_5" value="3">
        <?php } ?><input type="file" name="x_identificacion_oficial_5"  id="x_identificacion_oficial_5" onchange="if (this.form.a_x_ife_5[2]) this.form.a_x_ife_5[2].checked=true;checkFile(this.value,this.name,'ife');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg">
    <td colspan="2">&nbsp;</td>
    <td width="320"><input type="button" name="ife" id="ife"  onclick="checkMyForm();" value="Guardar" /></td>
    </tr>
</table>
<?php }?>
</form>
</body>
</html>
<?php


function loadData($conn, $id){
	$sqlS = "SELECT solicitud_id, tipo_galeria_id, nombre_galeria, identificacion_oficial_1, identificacion_oficial_2, identificacion_oficial_3, identificacion_oficial_4, identificacion_oficial_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";

	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$rows= phpmkr_fetch_array($rsS);
	$GLOBALS["x_identificacion_oficial_1"] = $rows["identificacion_oficial_1"];
	$GLOBALS["x_identificacion_oficial_2"] = $rows["identificacion_oficial_2"];
	$GLOBALS["x_identificacion_oficial_3"] = $rows["identificacion_oficial_3"];
	$GLOBALS["x_identificacion_oficial_4"] = $rows["identificacion_oficial_4"];
	$GLOBALS["x_identificacion_oficial_5"] = $rows["identificacion_oficial_5"];
	mysql_free_result($rsS);
	
	$GLOBALS["x_nombre_galeria"] = $rows["nombre_galeria"];
	//echo $rows["nombre_galeria"]."----<br>";
	$x_tipo_galeria_id = $rows["tipo_galeria_id"];	
	
	if($x_tipo_galeria_id == 2){
		// la galeria es de un aval
		$GLOBALS["x_nombre_galeria"] = "AVAL_".$rows["nombre_galeria"];
		}
	
	$x_solicitud_id = $rows["solicitud_id"];
	#echo "<br>solo".$x_solicitud_id."<br>";
	
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
	}else{
		
		 $GLOBALS["x_edita_fotos_existententes"] = 1;
		}
	
	
	
	}
	
	
 function addData($conn){
	  $x_galeria_fotografica_id = $_POST["x_galeria_fotografica_id"];
	 $a_x_ife_1 = @$_POST["a_x_ife_1"];
	 $a_x_ife_2 = @$_POST["a_x_ife_2"];
	 $a_x_ife_3 = @$_POST["a_x_ife_3"];
	 $a_x_ife_4 = @$_POST["a_x_ife_4"];
	 $a_x_ife_5 = @$_POST["a_x_ife_5"];
	 
	 $x_nombre_galeria = $_POST["x_nombre_galeria"];	 
	 $x_hoy = date("Y-m-d");	 
	 $x_nombre_galeria = sanear_string($x_nombre_galeria);
	 $x_hoy = sanear_string($x_hoy);
	 $x_nombre_galeria =  $x_nombre_galeria."_". $x_hoy."_".$x_galeria_fotografica_id;
	 
	 
	 // documentos	
	$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_identificacion_oficial_1"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_identificacion_oficial_1"]["size"] > $EW_MaxFileSize) {
			die("El tamoño de la imagen es mayor al tamaño permitido, cambien la imagen por una en tamaño mediano o grande");
		}
	}
	
 
	$fieldList = NULL;
		$x_imagen1 = "";
		$x_imagen2 = "";
		$x_imagen3 = "";
		$x_imagen4 = "";
		$x_imagen5 = "";
		
		// Field IFE 1
		
		if ($a_x_ife_1 == "2") { // Remove
					$fieldList["`identificacion_oficial_1`"] = "NULL";
				} else if ($a_x_ife_1 == "3") { // Update
				if (is_uploaded_file($_FILES["x_identificacion_oficial_1"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_identificacion_oficial_1"]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_identificacion_oficial_1"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "IFE_1_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;					 				 
					 
					
							if (!move_uploaded_file($_FILES["x_identificacion_oficial_1"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($x_nuevo_nombre)) : $x_nuevo_nombre ;
			$fieldList["`identificacion_oficial_1`"] = " '" . $theName . "'"; 					
					$x_imagen1 = $theName;	
					@unlink($_FILES["x_identificacion_oficial_1"]["tmp_name"]);
				}
				}
			// Field IFE 2
				if ($a_x_ife_2 == "2") { // Remove
					$fieldList["`identificacion_oficial_2`"] = "NULL";
				} else if ($a_x_ife_2 == "3") { // Update
				if (is_uploaded_file($_FILES["x_identificacion_oficial_2"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_identificacion_oficial_2"]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_identificacion_oficial_2"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "IFE_2_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;	
					
							if (!move_uploaded_file($_FILES["x_identificacion_oficial_2"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($x_nuevo_nombre)) : $x_nuevo_nombre ;
			$fieldList["`identificacion_oficial_2`"] = " '" . $theName . "'"; 					
					$x_imagen2 = $theName;
							
					@unlink($_FILES["x_identificacion_oficial_2"]["tmp_name"]);
				}
				}
					// Field IFE 3
					
				if ($a_x_ife_3 == "2") { // Remove
					$fieldList["`identificacion_oficial_3`"] = "NULL";
				} else if ($a_x_ife_3 == "3") { // Update		
				if (is_uploaded_file($_FILES["x_identificacion_oficial_3"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_identificacion_oficial_3"]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_identificacion_oficial_3"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "IFE_3_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;	
					 
					 
							if (!move_uploaded_file($_FILES["x_identificacion_oficial_3"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($x_nuevo_nombre)) : $x_nuevo_nombre ;
			$fieldList["`identificacion_oficial_3`"] = " '" . $theName . "'"; 					
					$x_imagen3 = $theName;		
					@unlink($_FILES["x_identificacion_oficial_3"]["tmp_name"]);
				}
				}
					// Field IFE 4
					if ($a_x_ife_4 == "2") { // Remove
					$fieldList["`identificacion_oficial_4`"] = "NULL";
				} else if ($a_x_ife_4 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_identificacion_oficial_4"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_identificacion_oficial_4"]["name"]);
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_identificacion_oficial_4"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "IFE_4_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;	
					 
							if (!move_uploaded_file($_FILES["x_identificacion_oficial_4"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($x_nuevo_nombre)) : $x_nuevo_nombre ;
			$fieldList["`identificacion_oficial_4`"] = " '" . $theName . "'"; 					
						$x_imagen4 = $theName;	
					@unlink($_FILES["x_identificacion_oficial_4"]["tmp_name"]);
				}
				}
				
					// Field IFE 1
					
					if ($a_x_ife_5 == "2") { // Remove
					$fieldList["`identificacion_oficial_5`"] = "NULL";
				} else if ($a_x_ife_5 == "3") { // Update	
				if (is_uploaded_file($_FILES["x_identificacion_oficial_5"]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_identificacion_oficial_5"]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_identificacion_oficial_5"]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "IFE_5_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;	
					 
					
							if (!move_uploaded_file($_FILES["x_identificacion_oficial_5"]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($x_nuevo_nombre)) : $x_nuevo_nombre ;
			$fieldList["`identificacion_oficial_5`"] = " '" . $theName . "'"; 					
						$x_imagen5 = $theName;	
					@unlink($_FILES["x_identificacion_oficial_5"]["tmp_name"]);
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
	
		/*if(!empty($x_imagen1)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen1);			
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
	 */
	 return true;
	 }
?>