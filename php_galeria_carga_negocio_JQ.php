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
	
	foreach($_POST as $nombre => $valor){
		$$nombre = $valor;		
		}
	
	
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
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
<script language="javascript">
        $(document).ready(function() {
		
            $('#btnAdd').click(function() {
                var num     = $('.clonedInput').length;
                var newNum  = new Number(num + 1);
 				$('#contador').attr('value',num);
                var newElem = $('#file' + num).clone().attr('id', 'file' + newNum);
     			newElem.find('td:eq(0) ').html('Foto ' + newNum);
				elementos = newElem.find('td:eq(1) input').length;
				if(elementos == 4){
				newElem.find('td:eq(1) input:eq(0)').attr({'id':'a_x_negocio_'+newNum,'name':'a_x_negocio_'+newNum,'value': 1});	
				newElem.find('td:eq(1) input:eq(1)').attr({'id':'a_x_negocio_'+newNum,'name':'a_x_negocio_'+newNum,'value': 2});	
				newElem.find('td:eq(1) input:eq(2)').attr({'id':'a_x_negocio_'+newNum,'name':'a_x_negocio_'+newNum,'value': 3});	
				newElem.find('td:eq(1) input:eq(3)').attr({"name": 'x_foto_negocio_' + newNum,"id": 'x_foto_negocio_' + newNum,"value": ''});
				//newElem.find('td:eq(1) input:eq(2)').attr({'id':'a_x_negocio_'+newNum ,'name':'a_x_negocio_'+newNum,'value': 3,'type':'hidden'});	
				
				//newElem.find('td:eq(1) label:eq(1)').remove();
				//newElem.find('td:eq(1) label:eq(2)').remove();
				//$('<input>').attr({type: 'hidden', id: 'a_x_negocio_'+newNum, name: 'a_x_negocio_'+newNum, value:3}).appendTo(newElem);		
				//$('<input>').attr({type: 'file', id: 'x_foto_negocio_'+newNum, name: 'x_foto_negocio_'+newNum, value:''}).appendTo(newElem);			

					}else{
				newElem.find('td:eq(1) input:eq(0)').attr('id','a_x_negocio_'+newNum).attr('name','a_x_negocio_'+newNum).attr('value', 3);
                newElem.find('td:eq(1) input:eq(1)').attr('id', 'x_foto_negocio_' + newNum).attr('name', 'x_foto_negocio_' + newNum).attr('value','');
				 
					}
				elementos = newElem.find('td:eq(1) input').length;
				newElem.find('td:eq(0) ').html('Foto ' + newNum);
				
                $('#file' + num).after(newElem);
				newElem.find('td:eq(1) label').remove();
				newElem.find('td:eq(1) input').remove();
				$('<input>').attr({type: 'hidden', id: 'a_x_negocio_'+newNum, name: 'a_x_negocio_'+newNum, value:3}).appendTo(newElem.find('td:eq(1)'));		
				$('<input>').attr({type: 'file', id: 'x_foto_negocio_'+newNum, name: 'x_foto_negocio_'+newNum, value:''}).appendTo(newElem.find('td:eq(1)'));
				
				//newElem.find('#a_x_negocio_'+newNum+ ' radio').remove();
                $('#btnDel').attr('disabled','');
 
                if (newNum == 10)
                    $('#btnAdd').attr('disabled','disabled');
					
				$('#contador').attr('value',newNum);	
            });
 
            $('#btnDel').click(function() {
                var num = $('.clonedInput').length;
 
                $('#file' + num).remove();
                $('#btnAdd').attr('disabled','');
 
                if (num-1 == 1)
                    $('#btnDel').attr('disabled','disabled');
            });
 
            $('#btnDel').attr('disabled','disabled');
			
        });

    


</script>



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
<?php echo $x_mensaje; ?>
<?php if(empty($x_mensaje)){?>
<input type="hidden" name="x_galeria_fotografica_id" value="<?php echo $x_galeria_fotografica_id;?>"  />
<input type="hidden" name="x_nombre_galeria" value="<?php echo $x_nombre_galeria;?>"  />
<input type="hidden" name="contador" id="contador" value="<?php echo $x_galerias_extra;?>" />
<table width="700" border="0" align="center">
  <tr>
    <td height="48" colspan="3" background="images/headTupload.jpg" style="color:#FFFFFF"><strong><pre>	NEGOCIO</pre></strong></td>
  </tr>
  <tr background="images/headTUpload1.jpg" id="file1"  class="clonedInput">
    <td width="56" height="33">Foto 1</td>
    <td colspan="2">
    <?php if ((!is_null($x_foto_negocio_1)) && ($x_foto_negocio_1 <> "")) {  ?>
       <?php if($x_edita_fotos_existententes == 0){?>
       <input type="radio" name="a_x_negocio_1" value="1" checked><label>Mantener&nbsp;</label>
       <?php }else{ ?>
        <input type="radio" name="a_x_negocio_1" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_1" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_1" value="3"><label>Cambiar</label><br />
        <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_1" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_1"  id="x_foto_negocio_1" onchange="if (this.form.a_x_negocio_1[2]) this.form.a_x_negocio_1[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg" id="file2"  class="clonedInput" >
    <td height="33">Foto 2</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_2)) && ($x_foto_negocio_2 <> "")) {  ?>
     	<?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_2" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>
         <input type="radio" name="a_x_negocio_2" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_2" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_2" value="3">Cambiar<br />
        
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_2" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_2" id="x_foto_negocio_2" onchange="if (this.form.a_x_negocio_2[2]) this.form.a_x_negocio_2[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload1.jpg" id="file3"  class="clonedInput">
    <td height="33">Foto 3</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_3)) && ($x_foto_negocio_3 <> "") ) {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_3" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>
        <input type="radio" name="a_x_negocio_3" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_3" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_3" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_3" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_3"  id="x_foto_negocio_3" onchange="if (this.form.a_x_negocio_3[2]) this.form.a_x_negocio_3[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg" id="file4"  class="clonedInput">
    <td height="33">Foto 4</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_4)) && ($x_foto_negocio_4 <> "") ) {  ?>
     	 <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_4" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>        
        <input type="radio" name="a_x_negocio_4" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_4" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_4" value="3">Cambiar<br />
        
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_4" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_4" id="x_foto_negocio_4"  onchange="if (this.form.a_x_negocio_4[2]) this.form.a_x_negocio_4[2].checked=true;checkFile(this.value,this.name,'neg');"/></td>
  </tr>
  <tr background="images/headTUpload1.jpg" id="file5"  class="clonedInput">
    <td height="33">Foto 5</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_5)) && ($x_foto_negocio_5 <> "")) {  ?>
      <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_5" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?> 
         <input type="radio" name="a_x_negocio_5" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_5" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_5" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_5" value="3">
        <?php } ?><input type="file" name="x_foto_negocio_5"  id="x_foto_negocio_5" onchange="if (this.form.a_x_negocio_5[2]) this.form.a_x_negocio_5[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  
   <tr background="images/headTUpload1.jpg" id="file6"  class="clonedInput">
    <td width="56" height="33">Foto 6</td>
    <td colspan="2">
    <?php if ((!is_null($x_foto_negocio_6)) && ($x_foto_negocio_6 <> "")) {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_6" value="1" checked><label>Mantener&nbsp;</label>
     <?php }else{ ?> 
             <input type="radio" name="a_x_negocio_6" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_6" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_6" value="3">Cambiar<br />
        <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_6" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_6"  id="x_foto_negocio_6" onchange="if (this.form.a_x_negocio_6[2]) this.form.a_x_negocio_6[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg" id="file7"  class="clonedInput">
    <td height="33">Foto 7</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_7)) && ($x_foto_negocio_7 <> "") ) {  ?>
     
      <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_7" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>         
        <input type="radio" name="a_x_negocio_7" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_7" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_7" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_7" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_7" id="x_foto_negocio_7" onchange="if (this.form.a_x_negocio_7[2]) this.form.a_x_negocio_7[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload1.jpg" id="file8"  class="clonedInput">
    <td height="33">Foto 8</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_8)) && ($x_foto_negocio_8 <> "")) {  ?>
      <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_8" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>
        <input type="radio" name="a_x_negocio_8" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_8" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_8" value="3">Cambiar<br />
          <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_8" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_8"  id="x_foto_negocio_8" onchange="if (this.form.a_x_negocio_8[2]) this.form.a_x_negocio_8[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  <tr background="images/headTUpload2.jpg" id="file9"  class="clonedInput">
    <td height="33">Foto 9</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_9)) && ($x_foto_negocio_9 <> "") ) {  ?>
       <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_9" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>
         <input type="radio" name="a_x_negocio_9" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_9" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_9" value="3">Cambiar<br />
         <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_9" value="3">
        <?php } ?>
    <input type="file" name="x_foto_negocio_9" id="x_foto_negocio_9"  onchange="if (this.form.a_x_negocio_9[2]) this.form.a_x_negocio_9[2].checked=true;checkFile(this.value,this.name,'neg');"/></td>
  </tr>
  <tr background="images/headTUpload1.jpg" id="file10"  class="clonedInput">
    <td height="33">Foto 10</td>
    <td colspan="2">
     <?php if ((!is_null($x_foto_negocio_10)) && ($x_foto_negocio_10 <> "")) {  ?>
     <?php if($x_edita_fotos_existententes == 0){?>
        <input type="radio" name="a_x_negocio_10" value="1" checked><label>Mantener&nbsp;</label>
        <?php }else{ ?>
        <input type="radio" name="a_x_negocio_10" value="1" checked><label>Mantener&nbsp;</label>
        <input type="radio" name="a_x_negocio_10" value="2" disabled><label>Eliminar&nbsp;</label>
        <input type="radio" name="a_x_negocio_10" value="3"><label>Cambiar</label><br />
            <?php }?>
        <?php } else {?>
        <input type="hidden" name="a_x_negocio_10" id="" value="3">
        <?php } ?><input type="file" name="x_foto_negocio_10"  id="x_foto_negocio_10" onchange="if (this.form.a_x_negocio_10[2]) this.form.a_x_negocio_10[2].checked=true;checkFile(this.value,this.name,'neg');" /></td>
  </tr>
  
  
  <?php 
  $x_gale = 11;
  
  while($x_gale <= $x_galerias_extra ){
	  // pintamos el tr
	  $galeria_extra_id = "x_galeria_fotografica_extra_id".$x_gale;
	  $x_tr = '<input type="hidden" name="x_galeria_extra_id_'.$x_gale.'" id="x_galeria_extra_id_'.$x_gale.'" value="'.$$galeria_extra_id.'">';
	  $x_tr .= ' <tr background="images/headTUpload1.jpg" id="file'.$x_gale.'"  class="clonedInput">';
	  $x_tr .= '<td height="33">Foto '.$x_gale.'</td>' ;
	  $x_tr .= '<td colspan="2"> ';
	  $x_tr .= ' ';
	  if($x_edita_fotos_existententes == 0){
	  $x_tr .= '<input type="radio" name="a_x_negocio_'.$x_gale.'" value="1" checked><label>Mantener&nbsp;</label>';
	  }else{
	 $x_tr .= '<input type="radio" name="a_x_negocio_'.$x_gale.'" value="1" checked><label>Mantener&nbsp;</label> ';	  
	 $x_tr .= '<input type="radio" name="a_x_negocio_'.$x_gale.'" value="2" disabled><label>Eliminar&nbsp;</label>';	
	 $x_tr .= '<input type="radio" name="a_x_negocio_'.$x_gale.'" value="3"><label>Cambiar</label><br />'; 
		  }
	 $x_tr .= '<input type="file" name="x_foto_negocio_'.$x_gale.'"  id="x_foto_negocio_'.$x_gale.'" onchange="if (this.form.a_x_negocio_'.$x_gale.'[2]) this.form.a_x_negocio_'.$x_gale.'[2].checked=true;checkFile(this.value,this.name,\'neg\');" />';
	 echo $x_tr;
	 $x_gale ++;
	 
	  }
	  
	  
  
  ?>
  
  <tr background="images/headTUpload2.jpg">
    <td colspan="2"><label>Agregar mas fotos?</label>
      <input type="button" id="btnAdd" value="+" /></td>
    <td width="320"><input type="button" name="neg" id="neg"  onclick="checkMyForm();" value="Guardar" /><div></div></td>
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
	#echo $rows["nombre_galeria"]."----<br>";
	$x_tipo_galeria_id = $rows["tipo_galeria_id"];	
	
	if($x_tipo_galeria_id == 2){
		// la galeria es de un aval
		$GLOBALS["x_nombre_galeria"] = "AVAL_".$rows["nombre_galeria"];
		}
	
	$x_solicitud_id = $rows["solicitud_id"];
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
	}else{
		
		 $GLOBALS["x_edita_fotos_existententes"] = 1;
		}
	
	
	$sqlS = "SELECT * FROM galeria_fotografica_extra WHERE galeria_fotografica_id = $id";

	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$x_cont = 11;
	while($rows= phpmkr_fetch_array($rsS)){
		$GLOBALS["x_galeria_fotografica_extra_id".$x_cont] = $rows["galeria_fotografica_extra_id"];
		$GLOBALS["x_documento".$x_cont] = $rows["documento"];
		$x_cont ++;
		
		}
	
	$GLOBALS["x_galerias_extra"] = $x_cont- 1;
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
	 
	 
	 foreach($_POST  as $nombre => $valor){
		 $$nombre = $valor;
		//echo $nombre."--->".$valor."<br>";
		 }
	
#	print_r($_FILES);
		 
		 
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
		
		
		
		for($i=1; $i<=10; $i++){
			$a_x_negocio = "a_x_negocio_".$i;
			
			if($$a_x_negocio == 3){
				echo "entra<br><br>";
				
				
				}
				
				
			if ($$a_x_negocio == "2") { // Remove
					$fieldList["`foto_negocio_1`"] = "NULL";
				} else if ($$a_x_negocio == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_".$i]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_".$i]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_foto_negocio_".$i]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "FOTO_NEGOCIO_".$i."_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;					 				 
					
					
							if (!move_uploaded_file($_FILES["x_foto_negocio_".$i]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes( $x_nuevo_nombre) :  $x_nuevo_nombre;
			$fieldList["foto_negocio_".$i] = " '" . $theName . "'"; 					
					$x_imagen1 = $theName;	
					@unlink($_FILES["x_foto_negocio_".$i]["tmp_name"]);
				}
				}	
				
				
			
			
			}
			
			
			$sSql = "UPDATE `galeria_fotografica` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE  galeria_fotografica_id = $x_galeria_fotografica_id";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			
			
			if($contador > 10){
				for($e=11;$e<=$contador;$e++){
				$fieldList = NULL;
				$a_x_negocio = "a_x_negocio_".$e;	
				$x_imagen_n = "x_imagen".$e;	
					if ($$a_x_negocio == "2") { // Remove
					$fieldList["`foto_negocio_1`"] = "NULL";
				} else if ($$a_x_negocio == "3") { // Update
				if (is_uploaded_file($_FILES["x_foto_negocio_".$e]["tmp_name"])) {
					$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto_negocio_".$e]["name"]);
					
					//aqui es donde debo genera el nombre del archivo
					 #copiamos la extension del archivo					 
					 $ext = explode('.',$_FILES["x_foto_negocio_".$e]["name"]);
					 $extension = $ext[1];					 
					 $x_nuevo_nombre = "FOTO_NEGOCIO_".$e."_".strtoupper($x_nombre_galeria).".".$extension;	
					 $nuevo_destfile = ewUploadPath(1).$x_nuevo_nombre;					 				 
					
					
							if (!move_uploaded_file($_FILES["x_foto_negocio_".$e]["tmp_name"], $nuevo_destfile)) // move file to destination path
							die("You didn't upload a file or the file couldn't be moved to" . $nuevo_destfile);
				// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes( $x_nuevo_nombre) :  $x_nuevo_nombre;
			$fieldList["documento"] = " '" . $theName . "'"; 	
			$fieldList["galeria_fotografica_id"] =  $x_galeria_fotografica_id; 					
			$fieldList["tipo_documento"] = 4; 
					$$x_imagen_n = $theName;	
					@unlink($_FILES["x_foto_negocio_".$e]["tmp_name"]);
				}
				
				#insertamos en la galeria extra
				
				
			//verificacmos si ya existia la galeria o no; si ya existia solo se actualiza; de lo contrario se inserta.
			$x_gale_extra_id = "x_galeria_extra_id_".$e;
			if($$x_gale_extra_id > 1){
			//se actualiza
			$strsql = "UPDATE `galeria_fotografica_extra` SET ";	
			$strsql .= "documento =  \" $theName\" ";
			$strsql .= "WHERE galeria_fotografica_extra_id =".$$x_gale_extra_id." ";		
				
				}	else{
					//se inserta
			$strsql = "INSERT INTO `galeria_fotografica_extra` (";
			$strsql .= implode(",", array_keys($fieldList));
			$strsql .= ") VALUES (";
			$strsql .= implode(",", array_values($fieldList));
			$strsql .= ")";
				}
		
			phpmkr_query($strsql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);	
				//echo "sql gal extra ".$strsql;
				
				if(!empty($$x_imagen_n)){// se llama la funcion 		
				echo "imagen n".$$x_imagen_n."<br>";	
					cambiaImagen("upload/".$$x_imagen_n);# or die("Error al reducir la imagemn");			
					}	
				}
					
					
				
					
				}
				
				}	
		
		// ya que se inserto en la base de datos cambiamos el tamaño de las imagenes usamos la fucion de cambia imagen
	
		if(!empty($x_imagen1)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen1);# or die("Error al reducir la imagemn");			
			}		
		if(!empty($x_imagen2)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen2);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen3)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen3);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen4)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen4);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen5)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen5);# or die("Error al reducir la imagemn");			
			}	
			
		if(!empty($x_imagen6)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen6);# or die("Error al reducir la imagemn");			
			}		
		if(!empty($x_imagen7)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen7);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen8)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen8);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen9)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen9);# or die("Error al reducir la imagemn");			
			}
		if(!empty($x_imagen10)){// se llama la funcion 			
			cambiaImagen("upload/".$x_imagen10);# or die("Error al reducir la imagemn");			
			}			 
	 $x_2 =  true;
	 return  $x_2;
	 }
?>