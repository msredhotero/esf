
<?php session_start(); ?>
<?php ob_start(); ?>
<?php

?>

<?php
if (@$_SESSION["php_project_esf_status"]  <> "login") {
	header("Location:  login.php");
}
?>
<?php
$ewCurSec = 0; // Initialise

// User levels

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
$sqlCompleto = '';
$sSqlWrk = '';

$inipath = php_ini_loaded_file();

//die(var_dump($_FILES['archivo2']['name']));
$ar = array();

$has_title_row = true;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(is_uploaded_file($_FILES['csvfile']['tmp_name'])){

		$sql = 'delete from csv_sdn';
		$rswrk = phpmkr_query($sql,$conn) or die("<p>Error al borrar</p> " . phpmkr_error() . ' SQL:' . $sql);

		$filename = basename($_FILES['csvfile']['name']);

		if(substr($filename, -3) == 'csv'){

			$tmpfile = $_FILES['csvfile']['tmp_name'];

			if (($fh = fopen($tmpfile, "r")) !== FALSE) {
				$i = 0;
				while (($items = fgetcsv($fh, 10000, ",")) !== FALSE) {
					if($has_title_row === true && $i == 0){ // skip the first row if there is a tile row in CSV file
						$i++;
						continue;
					}
					$sSqlWrk = '';


					$campo0 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[0]))));
					$campo1 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[1]))));
					$campo2 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[2]))));
					$campo3 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[3]))));
					$campo4 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[4]))));
					$campo5 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[5]))));
					$campo6 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[6]))));
					$campo7 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[7]))));
					$campo8 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[8]))));
					$campo9 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[9]))));
					$campo10 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[10]))));
					$campo11 = strtoupper(utf8_decode(str_replace("'","",str_replace(";",",",$items[11]))));

					//guardamos en base de datos la l�nea leida

					$sSqlWrk = "INSERT INTO csv_sdn(ent_num,sdn_name,sdn_type,program,title,call_sign,vess_type,tonnage,grt,vess_flag,vess_owner,remarks)";
					$sSqlWrk .=" VALUES('$campo0','$campo1','$campo2','$campo3','$campo4','$campo5','$campo6','$campo7','$campo8','$campo9','$campo10','$campo11')";
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("<b><center><span class=\"texto_titulo\">Error al importar los registros, por favor verifique que su archivo tiene el formato correcto.</b><p><br> Contacte al administrador y envie el siguiente error junto con el archivo que intento cargar<br><p> " . phpmkr_error() . ' SQL:' . $sSqlWrk);

					$sqlCompleto .= $sSqlWrk.'<br>';
					//print_r($items);
					$i++;

					$leyenda = '';
				}
			}
			}
			else{
				$leyenda = 'Formato invalido';
				//die('Invalid file format uploaded. Please upload CSV.');
			}
    }
    else{
		 $leyenda = 'Por favor seleccione un archivo';
        //die('Please upload a CSV file.');
    }
}

$exito = 0;




//echo $sqlCompleto;

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
    <td ><a href="php_ofaclist.php?cmd=resetall">Lista OFAC</a></td>




    </tr>
</table>
  <p></p>
  <p></p>
  <p></p>
  <br />
  <br />

<p>
<div class="content-box-gray">
	<?php if ($leyenda == '') { ?>
<br /><b>Importar registros a la lista negra de la CNBV:</b> Se importaron con existo <b><?php echo ($i -1);?></b> registros en la lista negra de la CNBV<p>
<?php } else {
echo $leyenda;
 } ?>
  </div>
  <p>&nbsp;</p>
</form>
<?php include ("footer.php") ?>

<?php
//phpmkr_db_close($conn);
?>
<?php





?>
