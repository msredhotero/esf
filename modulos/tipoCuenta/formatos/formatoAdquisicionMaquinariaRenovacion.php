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
/*
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
*/
?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link href="../crm.css" rel="stylesheet" type="text/css" />
</head>
<body>




<input type="hidden" name="x_win" value="<?php echo $x_win ;?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />

<input type="hidden" name="x_direccion_id2" value="<?php echo $x_direccion_id2; ?>" />
<input type="hidden" name="x_direccion_id3" value="<?php echo $x_direccion_id3; ?>" />
<input type="hidden" name="x_direccion_id4" value="<?php echo $x_direccion_id4; ?>" />

<input type="hidden" name="x_aval_id" value="<?php echo $x_aval_id; ?>" />
<input type="hidden" name="x_garantia_id" value="<?php echo $x_garantia_id; ?>" />
<input type="hidden" name="x_ingreso_id" value="<?php echo $x_ingreso_id; ?>" />
<input type="hidden" name="x_gasto_id" value="<?php echo $x_gasto_id; ?>" />
<input type="hidden" name="x_ingreso_aval_id" value="<?php echo $x_ingreso_aval_id; ?>" />
<input type="hidden" name="x_gasto_aval_id" value="<?php echo $x_gasto_aval_id; ?>" />
<!--aqui inserto el formulario----.................................................................................................-->
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales </td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td class="texto_normal" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td>
          <input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlentities($x_nombre_completo)?>" maxlength="250" size="35"  />
          </td>
          <td><input type="text" name="x_apellido_paterno" id="x_apellido_parterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" maxlength="250" size="35" /></td>
          <td><input type="text" name="x_apellido_materno" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" maxlength="250" size="35" /></td>
          </tr>
        <tr>
          <td class="texto_normal"> Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td>RFC</td>
      <td colspan="4"><input type="text" name="x_rfc" id="x_rfc" size="30" maxlength="150" value="<?php echo htmlentities(@$x_rfc) ?>" /></td>
      <td width="172">CURP</td>
      <td colspan="5"><input type="text" name="x_curp" id="x_curp" size="30" maxlength="150" value="<?php echo htmlentities(@$x_curp) ?>" /></td>
    </tr>
    <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="25"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onMouseOver="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%y/%m/%d', 
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span></td>
      <td width="105">Sexo</td>
      <td width="103"><label>
        <select name="x_sexo" id="x_sexo">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
      <td>Integrantes Familia</td>
      <td colspan="5"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>" maxlength="30" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Dependientes</td>
      <td colspan="2"><input type="text" name="x_dependientes" id="x_dependientes" value="<?php echo htmlspecialchars(@$x_dependientes) ?>"  maxlength="30" size="30"  onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Correo Electronico</td>
      <td colspan="6"><input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>"  maxlength="100" size="46"/></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_esposa" id="x_esposa"  value="<?php echo htmlspecialchars(@$x_esposa) ?>" maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Domicilio </td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlspecialchars(@$x_calle_domicilio) ?>" maxlength="100" size="100"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>" maxlength="100" size="50"/></td>
      <td rowspan="2">C&oacute;digo Postal </td>
      <td colspan="5" rowspan="2"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad_domicilio\" id=\"x_entidad_domicilio\" class=\"texto_normal\" >";
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
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>"  maxlength="250" size="50"/></td>
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
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?>" maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td height="24">Tel Domicilio</td>
      <td width="133"><input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio"  value="<?php echo htmlspecialchars(@$x_telefono_domicilio) ?>" maxlength="50" size="25"/></td>
      <td width="76">Otro Tel</td>
      <td colspan="2"><input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1"  value="<?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?>" maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensula_domicilio" id="x_renta_mensula_domicilio" value="<?php echo number_format(@$x_renta_mensula_domicilio, 2, '.', '') ?>" maxlength="25" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Celular</td>
      <td><input type="text" name="x_celular" id="x_celular" value="<?php echo htmlspecialchars(@$x_celular) ?>" maxlength="50" size="25"/></td>
      <td>Otro Tel</td>
      <td colspan="2"><input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2" value="<?php echo htmlspecialchars(@$x_otro_telefono_domicilio_2) ?>"  maxlength="50" size="25"/></td>
      <td colspan="6">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td>Giro Negocio</td>
      <td colspan="10"><input type="text" name="x_giro_negocio" id="x_giro_negocio" value="<?php echo htmlspecialchars(@$x_giro_negocio) ?>"></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle_negocio" id="x_calle_negocio" value="<?php echo htmlspecialchars(@$x_calle_negocio) ?>"  maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_negocio" id="x_colonia_negocio"  value="<?php echo htmlspecialchars(@$x_colonia_negocio) ?>" maxlength="250" size="70"/></td>
      <td rowspan="2"><p>C&oacute;digo Postal</p></td>
      <td colspan="5" rowspan="2"><input type="text" name="x_codigo_postal_negocio" id="x_codigo_postal_negocio" value="<?php echo htmlspecialchars(@$x_codigo_postal_negocio)?>"  maxlength="25" size="30"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList2 = "<select name=\"x_entidad_negocio\" id=\"x_entidad_negocio\" class=\"texto_normal\" >";
		$x_entidad_idList2 .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList2 .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_negocio) {
					$x_entidad_idList2 .= "' selected";
				}
				$x_entidad_idList2 .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList2 .= "</select>";
		echo $x_entidad_idList2;
		?>
    </td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_negocio" id="x_ubicacion_negocio" value="<?php echo htmlspecialchars(@$x_ubicacion_negocio) ?>" maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Tipo Local</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_local_negocio\" id=\"x_tipo_local_negocio\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_local_negocio) {
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
      <td colspan="5"><input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio"  value="<?php echo htmlspecialchars(@$x_antiguedad_negocio) ?>" maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_negocio) ?>" maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><input type="text" name="x_tel_negocio" id="x_tel_negocio"  value="<?php echo htmlspecialchars(@$x_tel_negocio) ?>" maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual" id="x_renta_mensual"  value="<?php echo number_format(@$x_renta_mensual, 2, '.', '') ?>" maxlength="50" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Solicitud de Compra</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"><center><textarea cols="80" rows="4" id="x_solicitud_compra" name="x_solicitud_compra"><?php echo @$x_solicitud_compra; ?></textarea></center></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr></table>
   
    <table width="90%">
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr></table>
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="300"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_com_1" id="x_referencia_com_1" value="<?php echo htmlspecialchars(@$x_referencia_com_1) ?>"  maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1"  value="<?php echo htmlspecialchars(@$x_tel_referencia_1) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td width="107">Parentesco</td>
      <td colspan="6">
         <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_1\" id=\"x_parentesco_ref_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_com_2" id="x_referencia_com_2"  value="<?php echo htmlspecialchars(@$x_referencia_com_2) ?>" maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2"  value="<?php echo htmlspecialchars(@$x_tel_referencia_2) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_2\" id=\"x_parentesco_ref_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_2) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">3.-</td>
        <td width="276"><input type="text" name="x_referencia_com_3" id="x_referencia_com_3"  value="<?php echo htmlspecialchars(@$x_referencia_com_3) ?>" maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="34">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3"  value="<?php echo htmlspecialchars(@$x_tel_referencia_3) ?>"  maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_3\" id=\"x_parentesco_ref_3\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_3) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">4.-</td>
        <td width="276"><input type="text" name="x_referencia_com_4" id="x_referencia_com_4"  value="<?php echo htmlspecialchars(@$x_referencia_com_4) ?>" maxlength="250" size="40"/></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="35">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_4" id="x_tel_referencia_4"  value="<?php echo htmlspecialchars(@$x_tel_referencia_4) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_4\" id=\"x_parentesco_ref_4\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_4) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}

		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      </td>
      </tr>
    <tr>
      <td width="201">&nbsp;</td>
      <td width="108">&nbsp;</td>
      <td width="159">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="51">&nbsp;</td>
      <td width="140">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="48">&nbsp;</td>
    </tr>
    
</table>
   
    

  




<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Familiares</td>
      </tr>
      <tr>
        <td colspan="11" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="147">Ingreso del Negocio</td>
        <td colspan="4"><input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio"  value="<?php echo number_format(@$x_ing_fam_negocio, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td width="23" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Otros Ingresos TH</td>
        <td colspan="4"><input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th" value="<?php echo number_format(@$x_ing_fam_otro_th, 2, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1"   value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?>" maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_1" id="x_ing_fam_1"   value="<?php echo number_format(@$x_ing_fam_1, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?>" maxlength="250" size="35" /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar  2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_2" id="x_ing_fam_2"  value="<?php echo number_format(@$x_ing_fam_2, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_3" id="x_ing_fam_cuales_3"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_3) ?>" maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1"  value="<?php echo number_format(@$x_ing_fam_deuda_1, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?>" maxlength="250" size="35" /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2"  value="<?php echo number_format(@$x_ing_fam_deuda_2, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?>"  maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Total</td>
        <td colspan="4"><input type="text" name="x_ing_fam_total" id="x_ing_fam_total"  value="<?php echo number_format(@$x_ing_fam_total, 2, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">&nbsp;</td>
        <td width="71">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="72">&nbsp;</td>
        <td width="65">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Flujos del Negocio</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="147">Ventas</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas" value="<?php echo number_format(@$x_flujos_neg_ventas, 2, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td width="23" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1" value="<?php echo number_format(@$x_flujos_neg_proveedor_1, 2, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  value="<?php echo number_format(@$x_flujos_neg_proveedor_2, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?>"  maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  value="<?php echo number_format(@$x_flujos_neg_proveedor_3, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 4</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  value="<?php echo number_format(@$x_flujos_neg_proveedor_4, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1" value="<?php echo number_format(@$x_flujos_neg_gasto_1, 2, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  value="<?php echo number_format(@$x_flujos_neg_gasto_2, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  value="<?php echo number_format(@$x_flujos_neg_gasto_3, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Negocio</td>
        <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  value="<?php echo number_format(@$x_ingreso_negocio, 2, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
 
        <tr></tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <td colspan="19"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Inversion del Negocio</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <tr>
          <td height="30" colspan="5"><center>
            FIJA
          </center></td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2"><center>
            VARIABLE
          </center></td>
          <td width="21" colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">Concepto</td>
          <td colspan="4">Valor</td>
          <td colspan="2">&nbsp;</td>
          <td width="267">Concepto</td>
          <td width="171" colspan="-2">Valor</td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?>"  maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  value="<?php echo number_format(@$x_inv_neg_fija_valor_1, 2, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_1" value="<?php echo number_format(@$x_inv_neg_var_valor_1, 2, '.', '') ?>" id="x_inv_neg_var_valor_1"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2" value="<?php echo number_format(@$x_inv_neg_fija_valor_2, 2, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_2"  value="<?php echo number_format(@$x_inv_neg_var_valor_2, 2, '.', '') ?>" id="x_inv_neg_var_valor_2"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  value="<?php echo number_format(@$x_inv_neg_fija_valor_3, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  value="<?php echo number_format(@$x_inv_neg_var_valor_3, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4" value="<?php echo number_format(@$x_inv_neg_fija_valor_4, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?>"  maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  value="<?php echo number_format(@$x_inv_neg_var_valor_4, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226" align="right">Total</td>
          <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  value="<?php echo number_format(@$x_inv_neg_total_fija, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td align="right">TotalL</td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"   value="<?php echo number_format(@$x_inv_neg_total_var, 2, '.', '') ?>"maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right"></td>
          <td width="171" colspan="-2"></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right">ACTIVOS TOTALES:</td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales" value="<?php echo number_format(@$x_inv_neg_activos_totales, 2, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        
     
       
      </table>
  
  
  <!-- aqui termina.....................................................................................................................-->
  	
	
         
          
             
            
        
            
             
             
            
     <table><tr><td>  
     <?php if($_SESSION["crm_UserRolID"] < 4){ 
/*
capacidad de pago mensual TITULAR (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))
 
capacidad de pago semanal (cps) = (cpm / 4) * .40
  
Esto solo lo ve el coordinador de credito.
*/
  			$x_ventas_menusuales = ($x_ingresos_negocio != "") ? $x_ingresos_negocio : 0;
  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;			
  			$x_otros_ing = ($x_otros_ingresos != "") ? $x_otros_ingresos : 0;			
  			$x_ing_fam1 = ($x_ingresos_familiar_1 != "") ? $x_ingresos_familiar_1 : 0;						
  			$x_ing_fam2 = ($x_ingresos_familiar_2 != "") ? $x_ingresos_familiar_2 : 0;									

  			$x_prov1 = ($x_gastos_prov1 != "") ? $x_gastos_prov1 : 0;          
  			$x_prov2 = ($x_gastos_prov2 != "") ? $x_gastos_prov2 : 0;          
  			$x_prov3 = ($x_gastos_prov3 != "") ? $x_gastos_prov3 : 0;          			
  			$x_otrop = ($x_otro_prov != "") ? $x_otro_prov : 0;          						
  			$x_empleados = ($x_gastos_empleados != "") ? $x_gastos_empleados : 0;          						
  			$x_ren_neg = ($x_gastos_renta_negocio != "") ? $x_gastos_renta_negocio : 0;          			
  			$x_ren_cas = ($x_gastos_renta_casa != "") ? $x_gastos_renta_casa : 0;          						
  			$x_hipo = ($x_gastos_credito_hipotecario != "") ? $x_gastos_credito_hipotecario : 0;
  			$x_gas_otros = ($x_gastos_otros != "") ? $x_gastos_otros : 0;          									
	
			$x_dep = ($x_numero_hijos_dep != "") ? $x_numero_hijos_dep : 0;
			$x_ren = $x_ren_cas + $x_hipo;
			

			$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));
			
			$x_ingresos2 = $x_salario;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));
			
			$x_cps = ($x_cpm / 4) * .40;

			echo "<b>
			Capacidad de Pago Mensual: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal: ".FormatNumber(@$x_cps,0,2,0,1)."		
			</b>
			";



/*
capacidad de pago mensual AVAL (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))
 
capacidad de pago semanal (cps) = (cpm / 4) * .40
  
Esto solo lo ve el coordinador de credito.
*/
  			$x_ventas_menusuales = ($x_ingresos_mensuales != "") ? $x_ingresos_mensuales : 0;
//  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;			
  			$x_otros_ing = ($x_otros_ingresos_aval != "") ? $x_otros_ingresos_aval : 0;			
  			$x_ing_fam1 = ($x_ingresos_familiar_1_aval != "") ? $x_ingresos_familiar_1_aval : 0;						
  			$x_ing_fam2 = ($x_ingresos_familiar_2_aval != "") ? $x_ingresos_familiar_2_aval : 0;									

  			$x_prov1 = ($x_gastos_prov1_aval != "") ? $x_gastos_prov1_aval : 0;          
  			$x_prov2 = ($x_gastos_prov2_aval != "") ? $x_gastos_prov2_aval : 0;          
  			$x_prov3 = ($x_gastos_prov3_aval != "") ? $x_gastos_prov3_aval : 0;          			
  			$x_otrop = ($x_otro_prov_aval != "") ? $x_otro_prov_aval : 0;          						
  			$x_empleados = ($x_gastos_empleados_aval != "") ? $x_gastos_empleados_aval : 0;          						
  			$x_ren_neg = ($x_gastos_renta_negocio_aval != "") ? $x_gastos_renta_negocio_aval : 0;          			
  			$x_ren_cas = ($x_gastos_renta_casa2 != "") ? $x_gastos_renta_casa2 : 0;          						
  			$x_hipo = ($x_gastos_credito_hipotecario_aval != "") ? $x_gastos_credito_hipotecario_aval : 0;
  			$x_gas_otros = ($x_gastos_otros_aval != "") ? $x_gastos_otros_aval : 0;          									
	
			$x_dep = ($x_numero_hijos_dep_aval != "") ? $x_numero_hijos_dep_aval : 0;
			$x_ren = $x_ren_cas + $x_hipo;
			

			$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));
			
//			$x_ingresos2 = $x_salario;
			$x_ingresos2 = 0;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));
			
			$x_cps = ($x_cpm / 4) * .40;

			echo "<b>
			Capacidad de Pago Mensual AVAL: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal AVAL: ".FormatNumber(@$x_cps,0,2,0,1)."		
			</b>
			";
			} ?></td>

    <td><div align="center">
      
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>

