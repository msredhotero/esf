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
<?php

// Initialize common variables
$x_entidad_id = Null; 
$ox_entidad_id = Null;
$x_nombre = Null; 
$ox_nombre = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_cliente_id = @$_GET["x_cliente"];
if (empty($x_cliente_id)) {
	$bCopy = false;
}

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
if(!empty($x_cliente)){
	$sqlDC = "SELECT nombre_completo, apellido_paterno, apellido_materno FROM cliente WHERE cliente_id = $x_cliente_id ";
	$rsDC = phpmkr_query($sqlDC,$conn) or die("Error".phpmkr_error()."sql".$sqlDC);
	$rowDC = phpmkr_fetch_array($rsDC);
	$x_nombre_cliente = $rowDC["nombre_completo"]." ".$rowDC["apellido_paterno"]." ".$rowDC["apellido_materno"];
	
	
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
	$x_entidad_id = @$_POST["x_entidad_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_cliente_id = $_POST["x_cliente_id"];
	
	$x_direccion_id = @$_POST["x_direccion_id"]; //checar debe estar como un hidden es  necesario
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_entidad_id = @$_POST["x_entidad_id"];	
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_localidad_id = $_POST["x_localidad_id"];
	$x_localidad_id2 = $_POST["x_localidad_id2"];
	$x_numero_exterior = @$_POST["x_numero_exterior"];
	$x_compania_celular_id = @$_POST["x_compania_celular_id"];
	$x_telefono_movil_2 = @$_POST["x_otro_telefono_domicilio_2"];
	$x_compania_celular_id_2 = $_POST["x_compania_celular_id_2"];
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "b": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_entidadlist.php");
		
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_direcciones_archivo_muerto.php");
			
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>

<script src="paisedohint.js"></script> 
<script language="javascript" src="lochint.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre es requerido."))
		return false;
}
return true;
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">Agregar otro domicilio antiguo<br>
    <br>
    <a href="php_direcciones_archivo_muerto.php">Regresar a la lista</a></span></p>
<form name="entidadadd" id="entidadadd" action="php_guarda_direcciones_nuevas.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<p>
  <?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
</p>
<p>&nbsp; </p>
<table width="921">
<tr>
      <td colspan="11" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Domicilio Antiguo</td>
      <input type="hidden" name="x_cliente_id"  value="<?php echo $x_cliente_id?>"/>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead">Cliente: <?php echo $x_nombre_cliente;?></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead">&nbsp;</td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlentities($x_calle_domicilio);?>"  maxlength="100" size="60"/></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>" maxlength="100" size="50"/></td>
      <td>C&oacute;digo Postal </td>
      <td colspan="5"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad_domicilio\" id=\"x_entidad_domicilio\"  onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_domicilio) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>
      </td>
      <td colspan="6"><div align="left"><span class="texto_normal">
        
        </span><span class="texto_normal">
          <div id="txtHint1" class="texto_normal">
            Del/Mun:
            <?php
		if($x_entidad_domicilio > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" >";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_domicilio";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
          </div>
        </span></div></td>
    </tr>
    <tr>
      <td>Localidad</td>
      <td colspan="4"><div id="txtHint3" class="texto_normal">
        <?php  
$x_delegacion_idList = "<select name=\"x_localidad_id\"  >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["localidad_id"] == @$x_localidad_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);

$x_delegacion_idList .= "</select>";

echo $x_delegacion_idList;
      ?>
      </div></td>
      <td>Ubicacion</td>
      <td colspan="5">
      <strong>
      <input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>"  maxlength="250" size="35"/>
      </strong></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_vivienda\" id=\"x_tipo_vivienda\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_vivienda) {
					$x_vivienda_tipo_idList .= "' selected";
				}
				$x_vivienda_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_vivienda_tipo_idList .= "</select>";
		echo $x_vivienda_tipo_idList;
		?>
      
      
      
      
      </td>
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad" id="x_antiguedad"  value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="24">&nbsp;</td>
      <td width="105">&nbsp;</td>
      <td width="63">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>

</table>
<p>
  <input type="submit" name="Action" value="Agregar">
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_entidad_id;
	
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_cliente;

	
	echo $x_cliente;

		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;
		
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;	
		
		
		// Field vivienda_tipo_id
		$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
		$fieldList["`compania_celular_id`"] = $theValue;
	
		$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
		$fieldList["`numero_exterior`"] = $theValue;
		
			 // Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil_2"]) :$GLOBALS["x_telefono_movil_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil_2`"] = $theValue;
			 
		$theValue = ($GLOBALS["x_compania_celular_id_2"] != "") ? intval($GLOBALS["x_compania_celular_id_2"]) : "0";
		$fieldList["`compania_celular_id_2`"] = $theValue;
			// Field delegacion_id
		$theValue = ($GLOBALS["x_localidad_id"] != "") ? intval($GLOBALS["x_localidad_id"]) : "0";
		$fieldList["`localidad_id`"] = $theValue;
		
		

	// insert into database
	$sSql = "INSERT INTO `direccion_antigua` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>