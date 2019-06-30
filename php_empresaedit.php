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
// Load key from QueryString
$x_empresa_id = @$_GET["empresa_id"];


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
$x_empresa_id = @$_GET["empresa_id"];
if (empty($x_empresa_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_empresa_id = @$_POST["x_empresa_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_calle = @$_POST["x_calle"];
	$x_numero_exterior = @$_POST["x_numero_exterior"];
	$x_numero_interior = @$_POST["x_numero_interior"];
	$x_colonia = @$_POST["x_colonia"];
	$x_estado = @$_POST["x_estado"];
	$x_delegacion = @$_POST["x_delegacion"];
	$x_telefono = @$_POST["x_telefono"];
	$x_fax = @$_POST["x_fax"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	 //representate legal	
	$x_representante_legal_id = @$_POST["x_representante_legal_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];
	$x_curp = @$_POST["x_curp"];
	$x_telefono_rep = @$_POST["x_telefono_rep"];
	$x_extension = @$_POST["x_extension"];
	$x_new_field0 = @$_POST["x_new_field0"];
	//datos de contitucion
	$x_datos_constitucion_empresa_id = @$_POST["x_datos_constitucion_empresa_id"];
	$x_acta_numero = @$_POST["x_acta_numero"];
	$x_fecha_acta = @$_POST["x_fecha_acta"];
	$x_nombre_licenciado = @$_POST["x_nombre_licenciado"];
	$x_numero_notaria = @$_POST["x_numero_notaria"];
	$x_estado = @$_POST["x_estado"];
	$x_numero_folio_mercantil = @$_POST["x_numero_folio_mercantil"];
	$x_fecha_folio_mercantil = @$_POST["x_fecha_folio_mercantil"];
	//datos escritura
	$x_datos_escritura_empresa_id = @$_POST["x_datos_escritura_empresa_id"];
	$x_escritura_numero = @$_POST["x_escritura_numero"];
	$x_fecha_escritura = @$_POST["x_fecha_escritura"];
	$x_nombre_licenciado_esc = @$_POST["x_nombre_licenciado_esc"];
	$x_numero_notaria_esc = @$_POST["x_numero_notaria_esc"];
	$x_estado_esc = @$_POST["x_estado_esc"];
	//datos de pago
	$x_datos_pago_empresa_id = @$HTTP_POST_VARS["x_datos_pago_empresa_id"];	
	$x_dia_corte_nomina = @$_POST["x_dia_corte_nomina"];
	$x_dia_pago_nomina = @$_POST["x_dia_pago_nomina"];
	$x_dia_pago_sip = @$_POST["x_dia_pago_sip"];
	$x_forma_pago = @$_POST["x_forma_pago"];
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_empresalist.php");
			exit();
		}
		break;
	case "U": // Add
		if (EditData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos de la empresa se han actualizado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_empresalist.php");
			exit();
		}
		break;
	}
?>
<?php 
include ("header.php");
 ?>
<script type="text/javascript" src="ew.js"></script>
<script src="paisedohint.js"></script> 
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script type="text/javascript">
<!--



function EW_checkMyForm() {
EW_this = document.empresaEdit;
validada = true;

if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre de la mepresa es requerido"))
		validada = false;
}
if (EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Por favor indique la calle de la empresa"))
		validada = false;
}




//representante

if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "El nombre del represntante es requerido"))
		validada = false;
}
if (EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "El apellido paterno del represntante es requerido"))
		validada = false;
}

/*if (EW_this.x_apellido_materno && !EW_hasValue(EW_this.x_apellido_materno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_materno, "TEXT", "El apellido materno del represntante es requerido"))
		validada = false;
}*/
/*if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "El siguinete campo es requrido - curp"))
		validada = false;
}
if (EW_this.x_telefono && !EW_hasValue(EW_this.x_telefono, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono, "TEXT", "El siguinete campo es requrido - telefono del representante legal"))
		validada = false;
}
if (EW_this.x_extension && !EW_hasValue(EW_this.x_extension, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_extension, "TEXT", "El siguinete campo es requrido - extension"))
		validada = false;
}*/
/*
if (EW_this.x_new_field0 && !EW_checkinteger(EW_this.x_new_field0.value)) {
	if (!EW_onError(EW_this, EW_this.x_new_field0, "TEXT", "Incorrect integer - new field 0"))
		validada = false; 
}*/

// datos constitucion

if (EW_this.x_acta_numero && !EW_hasValue(EW_this.x_acta_numero, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_acta_numero, "TEXT", "El siguiente campo es requerido - numero de acta"))
		validada = false;
}
if (EW_this.x_fecha_acta && !EW_hasValue(EW_this.x_fecha_acta, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_acta, "TEXT", "El siguiente campo es requerido ; - fecha acta"))
		validada = false;
}
/*if (EW_this.x_fecha_acta && !EW_checkdate(EW_this.x_fecha_acta.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_acta, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha acta"))
		validada = false; 
}*/
if (EW_this.x_nombre_licenciado && !EW_hasValue(EW_this.x_nombre_licenciado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_licenciado, "TEXT", "El siguiente campo es requerido  - nombre licenciado del acta"))
		validada = false;
}

if (EW_this.x_numero_notaria && !EW_hasValue(EW_this.x_numero_notaria, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_notaria, "TEXT", "El siguiente campo es requerido  - numero notaria del acta"))
		validada = false;
}

/*
if (EW_this.x_estado && !EW_hasValue(EW_this.x_estado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado, "TEXT", "El siguiente campo es requerido  - estado el acta"))
		validada = false;
}*/

if (EW_this.x_numero_folio_mercantil && !EW_hasValue(EW_this.x_numero_folio_mercantil, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_folio_mercantil, "TEXT", "El siguiente campo es requerido  - numero folio mercantil"))
		validada = false;
}
if (EW_this.x_fecha_folio_mercantil && !EW_hasValue(EW_this.x_fecha_folio_mercantil, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_folio_mercantil, "TEXT", "El siguiente campo es requerido  - fecha folio mercantil"))
		validada = false;
}




//datos escrituras


if (EW_this.x_escritura_numero && !EW_hasValue(EW_this.x_escritura_numero, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_escritura_numero, "TEXT", "El siguiente campo es requerido - escritura numero"))
		validada = false;
}
if (EW_this.x_fecha_escritura && !EW_hasValue(EW_this.x_fecha_escritura, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_escritura, "TEXT", "El siguiente campo es requerido - fecha escritura"))
		validada = false;
}
/*if (EW_this.x_fecha_escritura && !EW_checkdate(EW_this.x_fecha_escritura.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_escritura, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha escritura"))
		validada = false;
}*/
if (EW_this.x_nombre_licenciado_esc && !EW_hasValue(EW_this.x_nombre_licenciado_esc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_licenciado_esc, "TEXT", "El siguiente campo es requerido- nombre licenciado escritura"))
		validada = false;
}
if (EW_this.x_numero_notaria_esc && !EW_hasValue(EW_this.x_numero_notaria_esc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_notaria_esc, "TEXT", "El siguiente campo es requerido - numero notaria escritura"))
		validada = false;
}
if (EW_this.x_estado_esc && !EW_hasValue(EW_this.x_estado_esc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_esc, "TEXT", "El siguiente campo es requerido - estado"))
		validada = false;
}



//datos pago

if (EW_this.x_dia_corte_nomina && !EW_hasValue(EW_this.x_dia_corte_nomina, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dia_corte_nomina, "TEXT", "El siguiente campo es requerido - dia corte nomina"))
		validada = false;
}

if (EW_this.x_dia_pago_nomina && !EW_hasValue(EW_this.x_dia_pago_nomina, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dia_pago_nomina, "TEXT", "El siguiente campo es requerido- dia pago nomina"))
		validada = false;
}

if (EW_this.x_dia_pago_sip && !EW_hasValue(EW_this.x_dia_pago_sip, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dia_pago_sip, "TEXT", "El siguiente campo es requerido - dia pago sip"))
		validada = false;
}

if (EW_this.x_forma_pago && !EW_hasValue(EW_this.x_forma_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago, "TEXT", "El siguiente campo es requerido - forma pago"))
		validada = false;
}



if(validada == true){
	EW_this.a_edit.value = "U";
	EW_this.submit();
}

}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="php_project_esf.css" />
<p><span class="phpmaker"><a href="php_empresalist.php">Regresar a la Lista</a></span></p>
<form name="empresaEdit" id="empresaEdit" action="" method="post" >
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_empresa_id" value="<?php echo $x_empresa_id; ?>" /> 
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table width="700" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr>
    <td height="22" colspan="5"   align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold" >Empresa</td>
  </tr>
   <tr>
    <td width="114" class="texto_normal">&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td width="114" class="texto_normal">Nombre:</td>
    <td colspan="4"><input name="x_nombre" type="text" id="x_nombre" value="<?php echo htmlspecialchars(@$x_nombre) ?>" size="120" maxlength="500" /></td>
  </tr>
  <tr>
    <td class="texto_normal">Calle:</td>
    <td width="320"><input name="x_calle" type="text" id="x_calle" value="<?php echo htmlspecialchars(@$x_calle) ?>" size="80" maxlength="500" /></td>
    <td colspan="3"><table width="232" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="53" class="texto_normal">No.Ext:</td>
        <td width="67"><input name="x_numero_exterior" type="text" id="x_numero_exterior" value="<?php echo htmlspecialchars(@$x_numero_exterior) ?>" size="15" maxlength="25" /></td>
        <td width="45" class="texto_normal">No.Int:</td>
        <td width="67"><input type="text" name="x_numero_interior" id="x_numero_interior" size="15" maxlength="20" value="<?php echo htmlspecialchars(@$x_numero_interior) ?>" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td  class="texto_normal">Colonia:</td>
    <td><input name="x_colonia" type="text" id="x_colonia" value="<?php echo htmlspecialchars(@$x_colonia) ?>" size="80" maxlength="500"></td>
    <td width="81" class="texto_normal">CP:</td>
    <td colspan="2"><input name="x_codigo_postal" type="text" id="x_codigo_postal" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="15" maxlength="500"></td>
  </tr>
  <tr>
    <td class="texto_normal">Estado:</td>
    <td><?php
		$x_delegacion_idList = "<select name=\"x_estado\"  onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_estado) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?></td>
    <td colspan="3" class="texto_normal"><div id="txtHint1" class="texto_normal">
        Del/Mun:
        <?php
		if($x_estado > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion\" >";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_estado";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion) {
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
        </div> </td>
    </tr>
  <tr>
    <td class="texto_normal">Telefono:</td>
    <td><input type="text" name="x_telefono" id="x_telefono" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_telefono) ?>"></td>
    <td class="texto_normal">Fax:</td>
    <td width="132"><input type="text" name="x_fax" id="x_fax" size="30" maxlength="45" value="<?php echo htmlspecialchars(@$x_fax) ?>"></td>
    <td width="57">&nbsp;</td>
  </tr>
 <tr>
    <td>status</td>
    <td>
    <select name="x_status" id="x_status">
    <option value="1" <?php  if($x_status == 1){?> selected="selected" <?php }?>>Activo</option>
    <option value="2" <?php  if($x_status == 2){?> selected="selected" <?php }?> >Inactivo</option>    
    </select>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  height="22" colspan="5"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Representante Legal</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="16" rowspan="2" class="texto_normal">Titular:  </td>
        <td width="276"><input name="x_nombre_completo" type="text" id="x_nombre_completo" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>" size="50" maxlength="250"></td>
        <td width="12">&nbsp;</td>
        <td width="275"><input name="x_apellido_paterno" type="text" id="x_apellido_paterno" value="<?php echo htmlspecialchars(@$x_apellido_paterno) ?>" size="50" maxlength="250"></td>
        <td width="24">&nbsp;</td>
        <td width="264"><input name="x_apellido_materno" type="text" id="x_apellido_materno" value="<?php echo htmlspecialchars(@$x_apellido_materno) ?>" size="50" maxlength="250"></td>
      </tr>
      <tr>
        <td class="texto_normal">Nombre</td>
        <td>&nbsp;</td>
        <td class="texto_normal" >Paterno</td>
        <td>&nbsp;</td>
        <td class="texto_normal">Materno</td>
      </tr>
    </table></td>
  </tr>

 <tr>
    <td class="texto_normal" >Curp</td>
    <td><input type="text" name="x_curp" id="x_curp" size="50" maxlength="50" value="<?php echo htmlspecialchars(@$x_curp) ?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td class="texto_normal" >Telefono</td>
    <td><input type="text" name="x_telefono_rep" id="x_telefono_rep" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_rep) ?>"></td>
    <td class="texto_normal" >Extension</td>
    <td><input type="text" name="x_extension" id="x_extension" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_extension) ?>"></td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
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
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="Action" type="button"  value="Editar" onClick="EW_checkMyForm();" /></td>
    <td>&nbsp;</td>
  </tr>
  </table>



</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php
//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables
function LoadData($conn){
	
	global $x_empresa_id;
	
	
	$sSql = "SELECT * FROM `empresa`";
	$sSql .= " WHERE `empresa_id` = " . $x_empresa_id;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);
		
		// Get the field contents
		$GLOBALS["x_empresa_id"] = $row["empresa_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_numero_exterior"] = $row["numero_exterior"];
		$GLOBALS["x_numero_interior"] = $row["numero_interior"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_estado"] = $row["estado"];
		$GLOBALS["x_delegacion"] = $row["delegacion"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_fax"] = $row["fax"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_status"] = $row["status"];
		
		
		phpmkr_free_result($rs);
		$sql = "SELECT * FROM 	representante_legal WHERE empresa_id = $x_empresa_id ";
		$rs = phpmkr_query($sql,$conn);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_representante_legal_id"] = $row["representante_legal_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row["apellido_materno"];
		$GLOBALS["x_curp"] = $row["curp"];
		$GLOBALS["x_telefono_rep"] = $row["telefono"];
		$GLOBALS["x_extension"] = $row["extension"];
		$GLOBALS["x_new_field0"] = $row["new_field0"];
		phpmkr_free_result($rs);
	
		
		
	
	
		return  $LoadData;
	
	
	
	}
	
}
function EditData($conn){
	 $x_empresa_id = $GLOBALS["x_empresa_id"];


	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

//EMPRESA

// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field numero_exterior
	$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "NULL";
	$fieldList["`numero_exterior`"] = $theValue;

	// Field numero_interior
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_numero_interior"]) : $GLOBALS["x_numero_interior"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`numero_interior`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field estado
	$theValue = ($GLOBALS["x_estado"] != "") ? intval($GLOBALS["x_estado"]) : "NULL";
	$fieldList["`estado`"] = $theValue;

	// Field delegacion
	$theValue = ($GLOBALS["x_delegacion"] != "") ? intval($GLOBALS["x_delegacion"]) : "NULL";
	$fieldList["`delegacion`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field fax
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_fax"]) : $GLOBALS["x_fax"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`fax`"] = $theValue;

	// Field otro
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;
	
	$theValue = ($GLOBALS["x_status"] != "") ? intval($GLOBALS["x_status"]) : "NULL";
	$fieldList["`status`"] = $theValue;

	// update
		$sSql = "UPDATE `empresa` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `empresa_id` =". $GLOBALS["x_empresa_id"]."";
		$x_result = phpmkr_query($sSql, $conn);	
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				$resp = FALSE;
				exit();
			}else{
				$resp = true;
				}
	$x_empresa_id = mysql_insert_id();			
				
				
				
				

	//REPRESENTANTE LEGAL
	$fieldList = NULL;
	// Field empresa_id

	

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	// Field apellido_paterno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	// Field apellido_materno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	// Field curp
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_rep"]) : $GLOBALS["x_telefono_rep"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field extension
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_extension"]) : $GLOBALS["x_extension"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`extension`"] = $theValue;

	// Field new_field0
	$theValue = ($GLOBALS["x_new_field0"] != "") ? intval($GLOBALS["x_new_field0"]) : "NULL";
	$fieldList["`new_field0`"] = $theValue;

	// insert into database
	// update
		$sSql = "UPDATE `representante_legal` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `empresa_id` =". $GLOBALS["x_empresa_id"];
		$x_result =  phpmkr_query($sSql, $conn);
	
		phpmkr_query($sSql, $conn);
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);
				$resp = FALSE;
				exit();
			}else{
				$resp = true;
				}
				




	
	phpmkr_query('commit;', $conn);	 
	
	return $resp;
}

?>
