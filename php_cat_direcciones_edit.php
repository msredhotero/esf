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
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_aval_id = Null; 
$ox_aval_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_direccion_tipo_id = Null; 
$ox_direccion_tipo_id = Null;
$x_calle = Null; 
$ox_calle = Null;
$x_colonia = Null; 
$ox_colonia = Null;
$x_delegacion_id = Null; 
$ox_delegacion_id = Null;
$x_otra_delegacion = Null; 
$ox_otra_delegacion = Null;
$x_entidad = Null; 
$ox_entidad = Null;
$x_codigo_postal = Null; 
$ox_codigo_postal = Null;
$x_ubicacion = Null; 
$ox_ubicacion = Null;
$x_antiguedad = Null; 
$ox_antiguedad = Null;
$x_vivienda_tipo_id = Null; 
$ox_vivienda_tipo_id = Null;
$x_otro_tipo_vivienda = Null; 
$ox_otro_tipo_vivienda = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_telefono_secundario = Null; 
$ox_telefono_secundario = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_direccion_id = @$_GET["direccion_id"];
$x_cliente_id = @$_GET["cliente_id"];

//if (!empty($x_direccion_id )) $x_direccion_id  = (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id ) : $x_direccion_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_direccion_id = @$_POST["x_direccion_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_aval_id = @$_POST["x_aval_id"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_direccion_tipo_id = @$_POST["x_direccion_tipo_id"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_propietario = @$_POST["x_propietario"];
	$x_entidad_id = @$_POST["x_entidad_id"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_ubicacion = @$_POST["x_ubicacion"];
	$x_antiguedad = @$_POST["x_antiguedad"];
	$x_vivienda_tipo_id = @$_POST["x_vivienda_tipo_id"];
	$x_otro_tipo_vivienda = @$_POST["x_otro_tipo_vivienda"];
	$x_telefono = @$_POST["x_telefono"];
	$x_telefono_secundario = @$_POST["x_telefono_secundario"];
}

// Check if valid key
if (($x_direccion_id == "") || (is_null($x_direccion_id))) {
	echo "AQUI";
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_direccionlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cat_direcciones.php?cliente_id=$x_cliente_id");
			exit();
		}
		break;
	case "D": // DIRECCIONES
		if($_POST["x_delegacion_id_temp"] != ""){
			$x_delegacion_id2 = $_POST["x_delegacion_id_temp"];
		}
		break;				
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direcciones</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color:transparent; border:0 ">

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function buscadelegacion(){
	EW_this = document.direccionedit;
	EW_this.a_edit.value = "D";	
	EW_this.submit();	
}


function EW_checkMyForm(EW_this) {
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "SELECT", "El cliente es requerido."))
		return false;
}
if (EW_this.x_aval_id && !EW_hasValue(EW_this.x_aval_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_aval_id, "SELECT", "El aval es requerido."))
		return false;
}
if (EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "El promotor es requerido."))
		return false;
}
if (EW_this.x_direccion_tipo_id && !EW_hasValue(EW_this.x_direccion_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_direccion_tipo_id, "SELECT", "El tipo de dirección es requerido."))
		return false;
}
if (EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "La calle es requerida."))
		return false;
}
if (EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "La colonia es requerida."))
		return false;
}
if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "La delegación es requerida."))
		return false;
}
if (EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "La entidad es requerida."))
		return false;
}
if (EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "El Código Postal es requerido."))
		return false;
}
if (EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "La Ubicación es requerida."))
		return false;
}
if (EW_this.x_antiguedad && !EW_hasValue(EW_this.x_antiguedad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad es requerida."))
		return false;
}
if (EW_this.x_antiguedad && !EW_checkinteger(EW_this.x_antiguedad.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad es requerida."))
		return false; 
}
if (EW_this.x_vivienda_tipo_id && !EW_hasValue(EW_this.x_vivienda_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_id, "SELECT", "El tipo de vivienda es requerido."))
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
<p><span class="phpmaker"><a href="php_cat_direcciones.php?cliente_id=<?php echo $x_cliente_id; ?>">Cancelar</a></span></p>
<form name="direccionedit" id="direccionedit" action="php_cat_direcciones_edit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_cliente_id" value="<?php echo $x_cliente_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>"  />
<input type="hidden" name="x_direccion_tipo_id" value="<?php echo $x_direccion_tipo_id; ?>"  />

<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td class="ewTableHeaderThin"><div align="center">
	<?php echo $x_direccion_tipo_desc; ?>
	</div></td>
  </tr>
  <tr>
    <td>
	
	

<?php if($x_direccion_tipo_id == 1){ ?>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
    <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlspecialchars(@$x_calle) ?>" size="80" maxlength="150" /></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Colonia: </span></td>
    <td colspan="3"><input name="x_colonia" type="text" class="texto_normal" id="x_colonia" value="<?php echo htmlspecialchars(@$x_colonia) ?>" size="80" maxlength="150" /></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Entidad:</span></td>
    <td width="172"><span class="texto_normal">
      <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id\" class=\"texto_normal\" onchange=\"buscadelegacion()\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
    </span></td>
    <td width="309"><div align="left"><span class="texto_normal">Del/Mun: </span><span class="texto_normal">
      <?php
		if($x_entidad_id > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
    </span></div></td>
    <td width="54"><div align="left"></div></td>
  </tr>
  <!---
	  <tr>
	    <td class="texto_normal">Otra delegaci&oacute;n: </td>
	    <td colspan="4">
		<input name="x_otra_delegacion" type="text" class="texto_normal" id="x_otra_delegacion" value="<?php //echo htmlspecialchars(@$x_otra_delegacion) ?>" size="80" maxlength="250" />		</td>
	    </tr>
	  <tr>
	  -->
  <tr>
    <td><span class="texto_normal">C.P.
      : </span></td>
    <td colspan="4"><span class="texto_normal">
      <input name="x_codigo_postal" type="text" class="texto_normal" id="x_codigo_postal" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="5" maxlength="10" />
    </span></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
    <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
  </tr>
  <tr>
    <td class="texto_normal">Antiguedad en Domicilio: </td>
    <td colspan="4"><span class="texto_normal">
      <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
      (a&ntilde;os)</span></td>
  </tr>
  <tr>
    <td class="texto_normal">Tipo de Vivienda: </td>
    <td colspan="4"><span class="texto_normal">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_id\" class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_id) {
					$x_vivienda_tipo_idList .= "' selected";
				}
				$x_vivienda_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_vivienda_tipo_idList .= "</select>";
		echo $x_vivienda_tipo_idList;
		?>
    </span></td>
  </tr>
  <tr>
    <td ></td>
    <td colspan="4" class="texto_normal"><div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"> Propietario de la Vivienda:&nbsp;
            <input class="texto_normal" type="text" name="x_propietario" value="<?php echo $x_propietario; ?>" size="50" maxlength="150" />
    </div></td>
  </tr>
  <tr>
    <td class="texto_normal">Tel. Particular: </td>
    <td colspan="4"><input name="x_telefono" type="text" class="texto_normal" id="x_telefono" value="<?php echo htmlspecialchars(@$x_telefono) ?>" size="20" maxlength="20" />
        <span class="texto_normal">&nbsp;Tel. Celular:
          <input name="x_telefono_secundario" type="text" class="texto_normal" id="x_telefono_secundario" value="<?php echo htmlspecialchars(@$x_telefono_secundario) ?>" size="20" maxlength="20" />
      </span></td>
  </tr>
</table>
<?php } ?>	  
	  
	  
	  
<?php if($x_direccion_tipo_id == 2){ ?>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4"><div align="left"></div></td>
  </tr>
  <tr>
    <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
    <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlspecialchars(@$x_calle) ?>" size="80" maxlength="150" /></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Colonia: </span></td>
    <td colspan="3"><input name="x_colonia" type="text" class="texto_normal" id="x_colonia" value="<?php echo htmlspecialchars(@$x_colonia) ?>" size="80" maxlength="150" /></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Entidad:</span></td>
    <td width="172"><span class="texto_normal">
      <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id\" class=\"texto_normal\" onchange=\"buscadelegacion()\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
    </span></td>
    <td width="309"><div align="left"><span class="texto_normal">
      <input type="hidden" name="x_delegacion_id_temp" value="" />
      Del/Mun: </span><span class="texto_normal">
        <?php
		if($x_entidad_id > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
      </span></div></td>
    <td width="54"><div align="left"></div></td>
  </tr>
  <!---
	  <tr>
	    <td class="texto_normal">Otra delegaci&oacute;n: </td>
	    <td colspan="4">
		<input name="x_otra_delegacion2" type="text" class="texto_normal" id="x_otra_delegacion2" value="<?php echo htmlspecialchars(@$x_otra_delegacion2) ?>" size="80" maxlength="250" />		</td>
	    </tr>
	  <tr>
	  --->
  <tr>
    <td><span class="texto_normal">C.P.
      :</span></td>
    <td colspan="4"><span class="texto_normal">
      <input name="x_codigo_postal" type="text" class="texto_normal" id="x_codigo_postal" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="5" maxlength="10"/>
    </span></td>
  </tr>
  <tr>
    <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
    <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
  </tr>
  <tr>
    <td class="texto_normal">Antiguedad en Domicilio: </td>
    <td colspan="4"><span class="texto_normal">
      <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
      (a&ntilde;os)</span></td>
  </tr>
  <tr>
    <td class="texto_normal">Tel.: </td>
    <td colspan="4"><input name="x_telefono" type="text" class="texto_normal" id="x_telefono" value="<?php echo htmlspecialchars(@$x_telefono) ?>" size="20" maxlength="20" />
        <span class="texto_normal">&nbsp; </span></td>
  </tr>
</table>
<?php } ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <input type="submit" name="Action" value="MODIFICAR" />
    </div></td>
  </tr>
</table>
<p>
<p>

</form>
</body>
</html>
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
	global $x_direccion_id;
	$sSql = "SELECT * FROM direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id ";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id) : $x_direccion_id;
	$sWhere .= "(`direccion_id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_direccion_id"] = $row["direccion_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_aval_id"] = $row["aval_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_direccion_tipo_id"] = $row["direccion_tipo_id"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_propietario"] = $row["propietario"];
		$GLOBALS["x_entidad_id"] = $row["entidad_id"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row["telefono_secundario"];


		$sSql = "SELECT descripcion FROM direccion_tipo where direccion_tipo_id = ".$GLOBALS["x_direccion_tipo_id"];
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_direccion_tipo_desc"] = $row2["descripcion"];		
		
	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);	
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
{
	global $x_direccion_id;
	$sSql = "SELECT * FROM `direccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id) : $x_direccion_id;	
	$sWhere .= "(`direccion_id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`cliente_id`"] = $theValue;
		$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "0";
		$fieldList["`aval_id`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "0";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
		$fieldList["`direccion_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
		$fieldList["`delegacion_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
/*		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
*/		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_tipo_vivienda`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		// update
		$sSql = "UPDATE `direccion` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
