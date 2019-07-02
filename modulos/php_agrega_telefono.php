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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agrega Telefono</title>

<link href="../crm.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../ew.js"></script>
<script language="javascript" src="agrega_telefono.js"></script>
<script src="paisedohint.js"></script> 
<script language="javascript">
function EW_checkMyForm(EW_this) {
EW_this = document.agrega_telefono;	
validada = true;
if (EW_this.x_numero_telefonico && !EW_hasValue(EW_this.x_numero_telefonico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_telefonico, "TEXT", "EL numero telefonico es requerido."))
		validada = false;
}

if (EW_this.x_numero_telefonico.value.length < 10)  {
	if (!EW_onError(EW_this, EW_this.x_numero_telefonico, "TEXT", "El numero debe ser a 10 digitos."))
		validada = false;
}

if (EW_this.x_numero_telefonico.value.length > 10)  {
	if (!EW_onError(EW_this, EW_this.x_numero_telefonico, "TEXT", "El numero debe ser a 10 digitos."))
		validada  = false;
}
return validada;
}


</script>
<script language="javascript">
function cargarValores(){
	EW_this = document.agrega_telefono;
validada = true;
	
	if (validada == true && EW_this.x_pertence && !EW_hasValue(EW_this.x_pertence, "SELECT" )) {
	//if (!EW_onError(EW_this, EW_this.x_pertence, "SELECT", "Indique si el numero es del TITULAR o del AVAL."))
		//validada = false;
}
	
	//pertece = document.getElementById("x_pertence").value;
	pertece= 0;
	cliente_id  = document.getElementById("x_cliente_id").value;
	tipo_telefono = document.getElementById("x_tipo_telefono").value;
if(validada){
	cargarCapastelfonos(cliente_id,tipo_telefono,pertece);	
	
	}
	
	
	}

</script>

</head>

<body>

<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php 

$conn = phpmkr_db_connect(HOST,USER,PASS,DB,PORT);
if( $_POST["inserta"] == 1 ){	
	$x_cliente_id = $_POST["x_cliente_id"];
	$x_tipo_telefono = $_POST["x_tipo_telefono"];
	$x_numero = $_POST["x_numero_telefonico"];
	$x_comentario = $_POST["x_comentario"];
	$x_compania = $_POST["x_compania_celular_1"];
	$x_aval = $_POST["aval_id"];
	$x_credito_id = $_POST["x_credito_id"];
	
	//echo "com".$x_compania;
	if(empty($x_compania)){
		$x_compania= 0;
		}
	// se insetan los datos en la tabla
	if($_POST["x_tipo_telefono"] == 1){
		$sqlBusc = "SELECT COUNT(*) AS existe FROM telefono WHERE cliente_id = $x_cliente_id and numero = $x_numero and telefono_tipo_id = 1 ";		
		}else if($_POST["x_tipo_telefono"] == 2){
			$sqlBusc = "SELECT COUNT(*) AS existe FROM telefono WHERE cliente_id = $x_cliente_id and numero = $x_numero  and telefono_tipo_id = 2 ";
			}
			
	$rsBusca = phpmkr_query($sqlBusc,$conn)or die("erro".phpmkr_error().$sqlBusc);	
	$rowBusca = phpmkr_fetch_array($rsBusca);
	$x_existente = $rowBusca["existe"]+0;	
	if($x_existente > 0){
		//echo "El numero ya existe ya no se puede rgistrar";
		$_SESSION["ewmsg"] = "El numero que intenta registrar ya existe, no puede registarlo nuevamente.";
		$_SESSION["ewmsg2"] = "El teléfono ya estaba registrado, el estatus de contacto no surtió efecto";
		}else{
			//echo "se inserta el numero";
			$sqlInsert = "INSERT INTO telefono (telefono_id, cliente_id, telefono_tipo_id, numero, comentario, compania_id, aval_id) VALUES (NULL, $x_cliente_id, $x_tipo_telefono, $x_numero, \"$x_comentario\", $x_compania, 0)";
		//	echo " INSERT ".$sqlInsert."<br>";
			$rsInset = phpmkr_query($sqlInsert,$conn)or die("error al inserta telefono".phpmkr_error().$sqlInsert);
			$_SESSION["ewmsg"] = "El numero se ha registrado correctamente. ";
			
			$sqlStatus = "SELECT * FROM `telefono_contacto_status`  WHERE `credito_id` =  ".$x_credito_id."";
			$rsStatus = phpmkr_query($sqlStatus,$conn) or die("Error al seleccionar el status del contacto".phpmkr_error()."sql:".$sqlStatus);
			$rowStatus = phpmkr_fetch_array($rsStatus);
			$x_status_contacto = $rowStatus["telefono_status_id"];
			
			if($x_status_contacto == 3 ){
				// esta en no contactable cambia a singestion nuevo contacto
				$_SESSION["ewmsg2"] = " EL tel&eacute;fono NO estaba registrado, el estatus de contacto ha cambiado de NO CONTACTABLE a SIN GESTI&Oacute;N NUEVO CONTACTO.";
				$x_fecha = date("Y-m-d");
				$x_hora = date("H:i:s");
				$x_usuario = $_SESSION["php_project_esf_status_UserID"]; 
				$sqlUpdate = "UPDATE telefono_contacto_status SET telefono_status_id = 4, fecha = '$x_fecha', hora = '$x_hora', usuario_id = $x_usuario WHERE credito_id = $x_credito_id ";
				phpmkr_query($sqlUpdate,$conn) or die ("Error al actualiza el estaus".phpmkr_error());
				
				}else if($x_status_contacto == 2){
					// esta en contactable y se queda asi
					$_SESSION["ewmsg2"] ="EL Tel&eacute;fono NO estaba registrado, sin embargo el estatus de contacto se mantiene como CONTACTABLE.";
					
					}else{
						$_SESSION["ewmsg2"] ="EL Tel&eacute;fono NO estaba registrado, sin embargo el estatus de contacto se mantiene.";
						}
			
			
			}
	
	}
	
if($x_sol_id <1){
	$x_sol_id = $_GET["key2"]; // la solcitud_id	
	$x_credito_id = $_GET["credito_id"];
	}

if(!empty($x_sol_id)){
#	Buscamos el cliente id
$sqlCliente = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_id = $x_sol_id ";
$rsCliente = phpmkr_query($sqlCliente,$conn) or die (phpmkr_error()."sql :".$sqlCliente);
$rowcliente = phpmkr_fetch_array($rsCliente);
$x_cliente_id = $rowcliente["cliente_id"];
#echo "cliente_id ".$x_cliente_id."<br>";
	}

?>
<form name="agrega_telefono" action=""  method="post" onSubmit="return EW_checkMyForm(this);">
<input type="hidden" name="inserta" id="inserta" value="1" />
<input type="hidden"  name="x_cliente_id"  id="x_cliente_id"  value="<?php echo $x_cliente_id?>"/>
<input type="hidden" name="x_credito_id" id="x_credito_id" value="<?php echo $x_credito_id;?>" /> 
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr bgcolor="#FFE6E6">
    <td height="40" colspan="4"><center>Registrar nuevo telefono</center></td>
    </tr>
  <tr>
  
 
   <td height="40" colspan="4"> <?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<p><span class="phpmaker" style="color: Blue;"><?php echo $_SESSION["ewmsg2"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>  </td>
  </tr>
  <tr>
    <td>Tipo telefono</td>
    <td><select name="x_tipo_telefono" id="x_tipo_telefono" onchange="cargarValores(<?php echo $x_cliente_id?>, this.value);">
      <option value="0"> Seleccione </option>
      <option value="1"> Fijo </option>
      <option value="2"> Celular </option>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan="4"><div id="muestra_telefonos">  </div></td>
    </tr>
    <tr>
      <td colspan="4"><div id="ingresa_datos"> </div></td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>



</form>
</body>
</html>

