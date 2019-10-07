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
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_aval_id = @$_GET["aval_id"];
if (empty($_POST["x_nombre_galeria"])) {
	$bCopy = false;
}

$x_liga_ife = 0;
// Get action

if(empty($sAction)){
$sAction = @$_POST["a_add"];	
	}

if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{


$x_nombre_galeria = $_POST["x_nombre_galeria"];
$x_tipo_galeria = $_POST["x_tipo_galeria"];
$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
//echo "id galeria".$x_galeria_fotografica_id."<br>";


}
$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
$id = $x_galeria_fotografica_id;
//echo "id".$id."<br>";

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$sqlS = "SELECT identificacion_oficial_1, identificacion_oficial_2, identificacion_oficial_3, identificacion_oficial_4, identificacion_oficial_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id and (identificacion_oficial_1  IS NOT NULL || identificacion_oficial_2  IS NOT NULL || identificacion_oficial_3  IS NOT NULL || identificacion_oficial_4  IS NOT NULL || identificacion_oficial_5  IS NOT NULL) ";
		
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$x_liga_ife = 1;
	
		}
	mysql_free_result($rsS);
	
	
	
		$sqlS = "SELECT foto_persona_1, foto_persona_2, foto_persona_3, foto_persona_4, foto_persona_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id AND (foto_persona_1  IS NOT NULL ||  foto_persona_2  IS NOT NULL || foto_persona_3  IS NOT NULL || foto_persona_4  IS NOT NULL || foto_persona_5 IS NOT NULL )";
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero2 = mysql_num_rows($rsS);
	if($numero2 >0){
		$x_liga_foto = 1;
		}
	
	mysql_free_result($rsS);
	
		$sqlS = "SELECT foto_domicilio_1, foto_domicilio_2, foto_domicilio_3, foto_domicilio_4, foto_domicilio_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id AND ( foto_domicilio_1 IS NOT NULL || foto_domicilio_2 IS NOT NULL || foto_domicilio_3 IS NOT NULL || foto_domicilio_4 IS NOT NULL || foto_domicilio_5 IS NOT NULL )";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero3 = mysql_num_rows($rsS);
	if($numero3 >0){
		$x_liga_domicilio = 1;
		}
		
	mysql_free_result($rsS);
	
		$sqlS = "SELECT comprobante_domicilio_1, comprobante_domicilio_2, comprobante_domicilio_3, comprobante_domicilio_4, comprobante_domicilio_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id AND (comprobante_domicilio_1 IS NOT NULL || comprobante_domicilio_2 IS NOT NULL || comprobante_domicilio_3 IS NOT NULL || comprobante_domicilio_4 IS NOT NULL || comprobante_domicilio_5 IS NOT NULL  )";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero4 = mysql_num_rows($rsS);
	if($numero4 >0){
		$x_liga_comp_dom= 1;
		}
		
	mysql_free_result($rsS);
	
		$sqlS = "SELECT comprobante_propiedad_1, comprobante_propiedad_2, comprobante_propiedad_3, comprobante_propiedad_4, comprobante_propiedad_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id AND ( comprobante_propiedad_1 IS NOT NULL || comprobante_propiedad_2 IS NOT NULL || comprobante_propiedad_3 IS NOT NULL || comprobante_propiedad_4 IS NOT NULL || comprobante_propiedad_5 IS NOT NULL )";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero5 = mysql_num_rows($rsS);
	if($numero5 >0){
		$x_liga_propiedad = 1;
		}
		
	mysql_free_result($rsS);
	
		$sqlS = "SELECT foto_negocio_1, foto_negocio_2, foto_negocio_3, foto_negocio_4, foto_negocio_5, foto_negocio_6, foto_negocio_7, foto_negocio_8, foto_negocio_9, foto_negocio_10 FROM galeria_fotografica WHERE galeria_fotografica_id = $id AND (  foto_negocio_1 IS NOT NULL || foto_negocio_2 IS NOT NULL || foto_negocio_3 IS NOT NULL || foto_negocio_4 IS NOT NULL || foto_negocio_5 IS NOT NULL  || foto_negocio_6 IS NOT NULL || foto_negocio_7 IS NOT NULL || foto_negocio_8 IS NOT NULL || foto_negocio_9 IS NOT NULL || foto_negocio_10 IS NOT NULL)";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$x_liga_negocio = 1;
		}
		
	mysql_free_result($rsS);



$sAction = "C";
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn,$x_galeria_fotografica_id)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			//phpmkr_db_close($conn);
			//ob_end_clean();
			//header("Location: php_avallist.php");
			//exit();
		}
		break;
	
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">

function checkMyformParte1(){
	
	EW_this = document.Parte1;
	validada = true;
	
	
	if (EW_this.x_nombre_galeria && !EW_hasValue(EW_this.x_nombre_galeria, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_galeria, "TEXT", "EL nombre de la galeria es requerido."))
		validada = false;
	}
	
	if (EW_this.x_tipo_galeria && !EW_hasValue(EW_this.x_tipo_galeria, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_galeria, "SELECT", "El tipo de galeria es requerido."))
		validada = false;
	}
	
	//termina validaciones del formato adquision de maquinaria
	if(validada == true){
	//EW_this.a_edit.value = "U";
	
	EW_this.submit();
;
	}
	}
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "SELECT", "El cliente es requerido."))
		return false;
}
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "EL nombre es requerido."))
		return false;
}
if (EW_this.x_parentesco_tipo_id && !EW_hasValue(EW_this.x_parentesco_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id, "SELECT", "El parentesco es requerido."))
		return false;
}
if (EW_this.x_telefono && !EW_hasValue(EW_this.x_telefono, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono, "TEXT", "El teléfono es requerido."))
		return false;
}
if (EW_this.x_ingresos_mensuales && !EW_hasValue(EW_this.x_ingresos_mensuales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Los ingresos mensuales son requeridos."))
		return false;
}
if (EW_this.x_ingresos_mensuales && !EW_checknumber(EW_this.x_ingresos_mensuales.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Los ingresos mensuales son requeridos."))
		return false; 
}
if (EW_this.x_ocupacion && !EW_hasValue(EW_this.x_ocupacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ocupacion, "TEXT", "La oupación es requerida."))
		return false;
}
return true;
}


function irAListado(){
	window.locationf="php_galeria_fotograficalist.php";
	}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
  
   
   
   <p><span class="phpmaker">Agregar nueva galeria de fotos<br><br>
     <a href="php_galeria_fotograficalist.php">Regresar a la lista</a>&nbsp;<a href="php_galeriaviewJQ.php?x_galeria_fotografica_id=<?php echo $id?>">Ver la galeria completa </a>&nbsp;<a href="php_galeria_ajustable.php?x_galeria_fotografica_id=<?php echo $id?>">Ver galeria ajustable </a></span></p>
   
       <?php if(!empty($x_galeria_fotografica_id)){?>
<form name="avaladd" id="avaladd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<input type="hidden" name="x_galeria_fotografica_id" value="<?php echo $x_galeria_fotorafica_id;?>" />

<table width="697" border="0">
  <tr>
    <td height="20" class="ewTableHeaderThin">Galeria de <?php echo $x_nombre_galeria; ?></td>
   </tr>
    <?php

	  if($x_liga_ife == 1){?>
  <tr background="images/headTUpload1.jpg">
    <td height="27"><a href="php_galeriaview_ife.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Identificaci&oacute;n Oficial</a></td>
    </tr><?php }?>
   
     <?php 
	 
	   if($x_liga_foto == 1){?>
  <tr background="images/headTUpload2.jpg">
    <td height="27"><a href="php_galeriaview_foto.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Solicitud de Credito </a></td>
    </tr><?php }?>
    
     <?php
	   if($x_liga_domicilio == 1){?>
     <tr background="images/headTUpload1.jpg">
    <td height="27"><a href="php_galeriaview_domicilio.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Estado de cuenta bancario</a></td>
    </tr><?php }?>
    
   <?php if($x_liga_comp_dom == 1){?>
  <tr background="images/headTUpload2.jpg">
    <td height="27"><a href="php_galeriaview_comp_dom.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Comprobante Domicilio</a></td>
    </tr><?php }?>
    
     <?php  if($x_liga_propiedad== 1){?>
     <tr background="images/headTUpload1.jpg">
    <td height="27"><a href="php_galeriaview_comp_prop.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Recibos de nomina</a></td>
    </tr><?php }?>
    
     <?php  if($x_liga_negocio == 1){?>
  <tr background="images/headTUpload2.jpg">
    <td height="27"><a href="php_galeriaview_negocio.php?x_galeria_fotografica_id=<?php echo $x_galeria_fotografica_id;?>" target="_blank">Cartas liquidaci&oacute;n</a></td>
    </tr><?php }?>
    <tr><td align="right"><input type="submit" name="Action" value="Salir" onClick="irAListado();"></td></tr>
</table>
</form>
<?php }?>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn, $x_id)
{
	

$id = $x_id;	
	$sqlS = "SELECT identificacion_oficial_1, identificacion_oficial_2, identificacion_oficial_3, identificacion_oficial_4, identificacion_oficial_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";
		
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_ife"] = 1;
	
		}
	mysql_free_result($rsS);
	
	
	
		$sqlS = "SELECT foto_persona_1, foto_persona_2, foto_persona_3, foto_persona_4, foto_persona_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_foto"] = 1;
		}
	mysql_free_result($rsS);
	
		$sqlS = "SELECT foto_domicilio_1, foto_domicilio_2, foto_domicilio_3, foto_domicilio_4, foto_domicilio_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_domicilio"] = 1;
		}
	mysql_free_result($rsS);
	
		$sqlS = "SELECT comprobante_domicilio_1, comprobante_domicilio_2, comprobante_domicilio_3, comprobante_domicilio_4, comprobante_domicilio_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_comp_dom"] = 1;
		}
	mysql_free_result($rsS);
	
		$sqlS = "SELECT comprobante_propiedad_1, comprobante_propiedad_2, comprobante_propiedad_3, comprobante_propiedad_4, comprobante_propiedad_5 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_propiedad"] = 1;
		}
	mysql_free_result($rsS);
	
		$sqlS = "SELECT foto_negocio_1, foto_negocio_2, foto_negocio_3, foto_negocio_4, foto_negocio_5, foto_negocio_6, foto_negocio_7, foto_negocio_8, foto_negocio_9, foto_negocio_10 FROM galeria_fotografica WHERE galeria_fotografica_id = $id";	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
	$numero = mysql_num_rows($rsS);
	if($numero >0){
		$GLOBLALS["x_liga_negocio"] = 1;
		}
	mysql_free_result($rsS);

return true;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	#agregamos la galeria
	
	
	
	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_galeria"]) : $GLOBALS["x_nombre_galeria"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_galeria`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($GLOBALS["x_tipo_galeria"] != "") ? intval($GLOBALS["x_tipo_galeria"]) : "NULL";
	$fieldList["`tipo_galeria_id`"] = $theValue;

	

	// insert into database
	$sSql = "INSERT INTO `galeria_fotografica` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$GLOBALS["x_galeria_fotografica_id"] = mysql_insert_id();
	
	if($x_result){
	return true;
	}else{
		//die("error". phphmkr_error()."sql".$sSql);
				
		return false;
		}
}
?>
