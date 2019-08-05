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

// Initialize common variables

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link href="../crm.css" rel="stylesheet" type="text/css" />
</head>

<body>



<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />


<input type="hidden" name="x_win" value="<?php echo $x_win; ?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<span class="texto_normal">



</span>
<input type="hidden" name="x_direccion_id2" value="<?php echo $x_direccion_id2; ?>" />
<input type="hidden" name="x_direccion_id3" value="<?php echo $x_direccion_id3; ?>" />
<input type="hidden" name="x_direccion_id4" value="<?php echo $x_direccion_id4; ?>" />

<input type="hidden" name="x_aval_id" value="<?php echo $x_aval_id; ?>" />
<input type="hidden" name="x_garantia_id" value="<?php echo $x_garantia_id; ?>" />
<input type="hidden" name="x_ingreso_id" value="<?php echo $x_ingreso_id; ?>" />
<input type="hidden" name="x_gasto_id" value="<?php echo $x_gasto_id; ?>" />
<input type="hidden" name="x_ingreso_aval_id" value="<?php echo $x_ingreso_aval_id; ?>" />
<input type="hidden" name="x_gasto_aval_id" value="<?php echo $x_gasto_aval_id; ?>" />
 
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  
  
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">

<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td width="165"><span class="texto_normal">Titular:</span></td>
	  <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="155"><div align="center">
	        <input name="x_nombre_completo" type="text" class="texto_normal" id="x_nombre_completo" value="<?php echo htmlentities(@$x_nombre_completo) ?>" size="25" maxlength="250" />
	        </div></td>
	      <td width="178"><div align="center">
	        <input name="x_apellido_paterno" type="text" class="texto_normal" id="x_apellido_paterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" size="25" maxlength="250" />
	        </div></td>
	      <td width="201"><div align="center">
	        <input name="x_apellido_materno" type="text" class="texto_normal" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" size="25" maxlength="250" />
	        </div></td>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="155"><div align="center"><span class="texto_normal">Nombre</span></div></td>
	      <td width="178"><div align="center"><span class="texto_normal">Apellido Paterno</span></div></td>
	      <td width="201"><div align="center"><span class="texto_normal">Apellido Materno</span></div></td>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td class="texto_normal">RFC:</td>
	  <td colspan="4">
	    <input name="x_tit_rfc" type="text" class="texto_normal" id="x_tit_rfc" value="<?php echo htmlentities(@$x_tit_rfc) ?>" size="20" maxlength="20" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">CURP:</td>
	  <td colspan="4">
      <input name="x_tit_curp" type="text" class="texto_normal" id="x_tit_curp" value="<?php echo htmlentities(@$x_tit_curp) ?>" size="20" maxlength="20" /></td>
	  </tr>
      
	<tr>
	  <td><span class="texto_normal">Tipo de Negocio: </span></td>
	  <td colspan="4"><input name="x_tipo_negocio" type="text" class="texto_normal" id="x_tipo_negocio" value="<?php echo htmlentities(@$x_tipo_negocio) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
	  <td colspan="4"><table width="533" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="121" align="left">
            <span class="texto_normal">
            <input name="x_tit_fecha_nac" type="text" id="x_tit_fecha_nac" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="12" maxlength="12">
            &nbsp;<img src="images/ew_calendar.gif" id="cx_tit_fecha_nac" alt="Calendario" style="cursor:pointer;cursor:hand;">
            <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_tit_fecha_nac", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_tit_fecha_nac" // ID of the button
            }
            );
            </script>
            </span>            </td>
          <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
            <input name="x_sexo" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo == "1") { echo "checked"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo"<?php if (@$x_sexo == "2") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?> </span></div></td>
          <td width="243"><div align="left"><span class="texto_normal">Edo. Civil:
            <?php
		$x_estado_civil_idList = "<select name=\"x_estado_civil_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estado_civil_id"] == @$x_estado_civil_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
            </span></div></td>
        </tr>
      </table></td>
	  </tr>
	
      <tr>
        <td><span class="texto_normal">&nbsp;No. de hijos
              : </span></td>
        <td colspan="3"><span class="texto_normal">
          <input name="x_numero_hijos" type="text" class="texto_normal" id="x_numero_hijos"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos) ?>" size="2" maxlength="1"/>
        Hijos dependientes:
        <input name="x_numero_hijos_dep" type="text" class="texto_normal" id="x_numero_hijos_dep"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_dep) ?>" size="2" maxlength="1"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nombre del Conyuge:</span></td>
        <td width="535" colspan="3">
		<input name="x_nombre_conyuge" type="text" class="texto_normal" id="x_nombre_conyuge" value="<?php echo htmlentities(@$x_nombre_conyuge) ?>" size="80" maxlength="250">		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3"><input name="x_email" type="text" class="texto_normal" id="x_email" value="<?php echo htmlspecialchars(@$x_email) ?>" size="50" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nacionalidad:</span></td>
        <td colspan="3"><span class="texto_normal">
          <?php
		$x_nac_idList = "<select name=\"x_nacionalidad_id\" class=\"texto_normal\">";
		$x_nac_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_nac_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["nacionalidad_id"] == @$x_nacionalidad_id) {
					$x_nac_idList .= "' selected";
				}
				$x_nac_idList .= ">" . htmlentities($datawrk["pais_nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_nac_idList .= "</select>";
		echo $x_nac_idList;

		?>
        </span></td>
      </tr>
	</table>	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio Particular </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlentities(@$x_calle) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia" type="text" class="texto_normal" id="x_colonia" value="<?php echo htmlentities(@$x_colonia) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
		<?php
		$x_delegacion_idList = "<select name=\"x_entidad_id\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\">";
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
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">
              
        </span><span class="texto_normal">
        <div id="txtHint1" class="texto_normal">
        Del/Mun:
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
        <td width="54"><div align="left"></div></td>
      </tr>
 
	    <tr>
	      <td><span class="texto_normal">C.P.
          : </span></td>
	      <td colspan="4"><span class="texto_normal">
	        <input name="x_codigo_postal" type="text" class="texto_normal" id="x_codigo_postal" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="5" maxlength="10" />
	      </span></td>
	      </tr>
	    <td><span class="texto_normal">Referencia de Ubicación:</span></td>
	  <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlentities(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Antiguedad en Domicilio: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
(años)</span></td>
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
				$x_vivienda_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
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
        <td > </td>
        <td colspan="4" class="texto_normal">
        
		<div id="prop1rentada" class="<?php if($x_vivienda_tipo_id == 2){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Arrendatario (Nombre y Tel):&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_renta" id="x_propietario_renta" value="<?php echo $x_propietario_renta; ?>" size="25" maxlength="150" />&nbsp;
		Renta:
		<input class="importe" name="x_gastos_renta_casa" type="text" id="x_gastos_renta_casa" value="<?php echo htmlspecialchars(@$x_gastos_renta_casa) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
		<div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_familiar" id="x_propietario_familiar" value="<?php echo $x_propietario; ?>" size="50" maxlength="150" />
		</div>		

		<div id="prop1credito" class="<?php if($x_vivienda_tipo_id == 4){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Empresa:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_ch" id="x_propietario_ch" value="<?php echo $x_propietario_ch; ?>" size="25" maxlength="150" />&nbsp;
        Pago Mensual:
		<input class="importe" name="x_gastos_credito_hipotecario" type="text" id="x_gastos_credito_hipotecario" value="<?php echo htmlspecialchars(@$x_gastos_credito_hipotecario) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		

		</td>
      </tr>
	<tr>
	  <td class="texto_normal">Tels. Particular: </td>
	  <td colspan="4"><input name="x_telefono" type="text" class="texto_normal" id="x_telefono" value="<?php echo htmlspecialchars(@$x_telefono) ?>" size="20" maxlength="20" />
	    <span class="texto_normal">&nbsp;- 
	    <input name="x_telefono_sec" type="text" class="texto_normal" id="x_telefono_sec" value="<?php echo htmlspecialchars(@$x_telefono_sec) ?>" size="20" maxlength="20" />
	    </span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tel. Celular: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_telefono_secundario" type="text" class="texto_normal" id="x_telefono_secundario" value="<?php echo htmlspecialchars(@$x_telefono_secundario) ?>" size="20" maxlength="20" />
	  </span></td>
	  </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio del negocio </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td colspan="4" style=" border: solid 1px #666">
		<div align="left">
          <input type="checkbox" name="x_mismos" value="0" onclick="mismosdom()" />
         <span class="texto_normal">Mismos que el Dom. Part.</span>		</div>		</td>
        </tr>	
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><input name="x_empresa" type="text" class="texto_normal" id="x_empresa" value="<?php echo htmlentities(@$x_empresa) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3"><input name="x_puesto" type="text" class="texto_normal" id="x_puesto" value="<?php echo htmlentities(@$x_puesto) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3">
		<span class="texto_normal">
        <input name="x_fecha_contratacion" type="text" id="x_fecha_contratacion" value="<?php echo FormatDateTime(@$x_fecha_contratacion,7); ?>" size="12" maxlength="12">
            &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_contratacion" alt="Calendario" style="cursor:pointer;cursor:hand;">
            <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_fecha_contratacion", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_fecha_contratacion" // ID of the button
            }
            );
            </script>
            </span>         
        </td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3"><input class="importe" name="x_salario_mensual" type="text" id="x_salario_mensual" value="<?php echo htmlspecialchars(@$x_salario_mensual) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"  /></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle2" type="text" class="texto_normal" id="x_calle2" value="<?php echo htmlentities(@$x_calle2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia2" type="text" class="texto_normal" id="x_colonia2" value="<?php echo htmlentities(@$x_colonia2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
        <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id2\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint2', 'x_delegacion_id2')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id2) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
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

        </span><span class="texto_normal">
        <div id="txtHint2" class="texto_normal">        
		Del/Mun:        
        <?php
		if($x_entidad_id2 > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id2\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id2) {
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
        <td width="54"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal2" type="text" class="texto_normal" id="x_codigo_postal2" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal2) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicación:</span></td>
        <td colspan="4"><input name="x_ubicacion2" type="text" class="texto_normal" id="x_ubicacion2" value="<?php echo htmlentities(@$x_ubicacion2) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad2" type="text" class="texto_normal" id="x_antiguedad2" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad2) ?>" size="2" maxlength="2"/>
        (años)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4"><input name="x_telefono2" type="text" class="texto_normal" id="x_telefono2" value="<?php echo htmlspecialchars(@$x_telefono2) ?>" size="20" maxlength="20" />
          <span class="texto_normal">&nbsp;        </span></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Aval </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="texto_normal">Aval: </td>
        <td colspan="3"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center"><span class="texto_normal">
              <input name="x_nombre_completo_aval" type="text" class="texto_normal" id="x_nombre_completo_aval" value="<?php echo htmlentities(@$x_nombre_completo_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">
              <input name="x_apellido_paterno_aval" type="text" class="texto_normal" id="x_apellido_paterno_aval" value="<?php echo htmlentities(@$x_apellido_paterno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">
              <input name="x_apellido_materno_aval" type="text" class="texto_normal" id="x_apellido_materno_aval" value="<?php echo htmlentities(@$x_apellido_materno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="22" class="texto_normal">&nbsp;</td>
        <td colspan="3"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center"><span class="texto_normal">Nombre</span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">Apellido Paterno</span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">Apellido Materno</span></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_normal">Parentesco:</td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_aval) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">RFC:</td>
        <td><input name="x_aval_rfc" type="text" class="texto_normal" id="x_aval_rfc" value="<?php echo htmlspecialchars(@$x_aval_rfc) ?>" size="20" maxlength="20" /></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">CURP:</td>
        <td><input name="x_aval_curp" type="text" class="texto_normal" id="x_aval_curp" value="<?php echo htmlspecialchars(@$x_aval_curp) ?>" size="20" maxlength="20" /></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tipo de Negocio: </td>
        <td colspan="2"><input name="x_tipo_negocio_aval" type="text" class="texto_normal" id="x_tipo_negocio_aval" value="<?php echo htmlspecialchars(@$x_tipo_negocio_aval) ?>" size="80" maxlength="250" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Fecha de Nacimiento:</td>
        <td colspan="2" align="left" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="121" align="left"><span class="texto_normal">
              <input name="x_tit_fecha_nac_aval" type="text" id="x_tit_fecha_nac_aval" value="<?php echo FormatDateTime(@$x_tit_fecha_nac_aval,7); ?>" size="12" maxlength="12" />
              &nbsp;<img src="images/ew_calendar.gif" id="cx_tit_fecha_nac_aval" alt="Calendario" style="cursor:pointer;cursor:hand;" />
              <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_tit_fecha_nac_aval", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_tit_fecha_nac_aval" // ID of the button
            }
            );
            </script>
            </span></td>
            <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
              <input name="x_sexo_aval" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked"<?php if (@$x_sexo_aval == "1") { echo "checked"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo_aval"<?php if (@$x_sexo_aval == "2") { echo "checked"; } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?></span></div></td>
            <td width="243"><div align="left"><span class="texto_normal">Edo. Civil:
              <?php
		$x_estado_civil_idList = "<select name=\"x_estado_civil_id_aval\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estado_civil_id"] == @$x_estado_civil_id_aval) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
            </span></div></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">&nbsp;No. de hijos
          : </td>
        <td colspan="2"><span class="texto_normal">
          <input name="x_numero_hijos_aval" type="text" class="texto_normal" id="x_numero_hijos_aval"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_aval) ?>" size="2" maxlength="1"/>
          Hijos dependientes:
          <input name="x_numero_hijos_dep_aval" type="text" class="texto_normal" id="x_numero_hijos_dep_aval"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_dep_aval) ?>" size="2" maxlength="1"/>
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Nombre del Conyuge:</td>
        <td colspan="2"><input name="x_nombre_conyuge_aval" type="text" class="texto_normal" id="x_nombre_conyuge_aval" value="<?php echo htmlentities(@$x_nombre_conyuge_aval) ?>" size="80" maxlength="250" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Email:</td>
        <td colspan="2"><input name="x_email_aval" type="text" class="texto_normal" id="x_email_aval" value="<?php echo htmlspecialchars(@$x_email_aval) ?>" size="50" maxlength="150" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Nacionalidad:</td>
        <td colspan="2"><span class="texto_normal">
          <?php
		$x_nac_idList = "<select name=\"x_nacionalidad_id_aval\" class=\"texto_normal\">";
		$x_nac_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_nac_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["nacionalidad_id"] == @$x_nacionalidad_id_aval) {
					$x_nac_idList .= "' selected";
				}
				$x_nac_idList .= ">" . htmlentities($datawrk["pais_nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_nac_idList .= "</select>";
		echo $x_nac_idList;

		?>
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tels.:</td>
        <td colspan="2"><span class="texto_normal">
          <input name="x_telefono3" type="text" class="texto_normal" id="x_telefono3" value="<?php echo htmlspecialchars(@$x_telefono3) ?>" size="20" maxlength="20" />
          -
          <input name="x_telefono3_sec" type="text" class="texto_normal" id="x_telefono3_sec" value="<?php echo htmlspecialchars(@$x_telefono3_sec) ?>" size="20" maxlength="20" />
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tel&eacute;fono celular:</td>
        <td><span class="texto_normal">
          <input name="x_telefono_secundario3" type="text" class="texto_normal" id="x_telefono_secundario3" value="<?php echo htmlspecialchars(@$x_telefono_secundario3) ?>" size="20" maxlength="20" />
        </span></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" class="texto_normal" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" class="texto_normal" style=" border: solid 1px #666"><input type="checkbox" name="x_mismos_titluar" value="0" onclick="mismosdomtit()" />
          <em>Mismo domicilio Particular que el  Titular</em></td>
        </tr>
      <tr>
        <td class="texto_normal_bold">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio Particular </td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3" type="text" class="texto_normal" id="x_calle3" value="<?php echo htmlentities(@$x_calle3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3" type="text" class="texto_normal" id="x_colonia3" value="<?php echo htmlentities(@$x_colonia3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint3', 'x_delegacion_id3')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id3) {
					$x_delegacion_idList .= "' selected";
				}

				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="400"><div align="left"><span class="texto_normal">
          <input type="hidden" name="x_delegacion_id_temp1" value="" />
          </span><span class="texto_normal">
		<div id="txtHint3" class="texto_normal">          
          Del/Mun: 
            <?php
		if($x_entidad_id3 > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id3\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id3) {
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
        <td width="16"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal3" type="text" class="texto_normal" id="x_codigo_postal3" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal3) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion3" type="text" class="texto_normal" id="x_ubicacion3" value="<?php echo htmlentities(@$x_ubicacion3) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3" type="text" class="texto_normal" id="x_antiguedad3" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3) ?>" size="2" maxlength="2"/>
          (a&ntilde;os) </span></td>
      </tr>
      <tr>
        <td class="texto_normal"> Tipo de Vivienda:</td>
        <td colspan="4"><span class="texto_normal">
          <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_id2\" class=\"texto_normal\" onchange=\"viviendatipo('2')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_id2) {
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
        </span></td>
      </tr>
      <tr>
        <td ></td>
        <td colspan="4" class="texto_normal">


		<div id="prop1rentada2" class="<?php if($x_vivienda_tipo_id2 == 2){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Arrendatario (Nombre y Tel):&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_renta2" id="x_propietario_renta2" value="<?php echo $x_propietario_renta2; ?>" size="25" maxlength="150" />&nbsp;
		Renta:
		<input class="importe" name="x_gastos_renta_casa2" type="text" id="x_gastos_renta_casa2" value="<?php echo htmlspecialchars(@$x_gastos_renta_casa2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
		<div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_familiar2" id="x_propietario_familiar2" value="<?php echo $x_propietario2; ?>" size="50" maxlength="150" />
		</div>		

		<div id="prop1credito2" class="<?php if($x_vivienda_tipo_id2 == 4){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Empresa:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_ch2" id="x_propietario_ch2" value="<?php echo $x_propietario_ch2; ?>" size="25" maxlength="150" />&nbsp;
        Pago Mensual:
		<input class="importe" name="x_gastos_credito_hipotecario2" type="text" id="x_gastos_credito_hipotecario2" value="<?php echo htmlspecialchars(@$x_gastos_credito_hipotecario2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
                
        </td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-top : solid 1px #666"><input type="checkbox" name="x_mismosava2" value="0" onclick="mismosdomtitneg()" />
          <em>&nbsp;Mismo domicilio de Negocio  (TITULAR).</em></td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-bottom : solid 1px #666">
		<div align="left">
          <input type="checkbox" name="x_mismosava" value="0" onclick="mismosdomava()" />
         <em><span class="texto_normal">Mismo domicilio que el Particular (AVAL).</span></em><span class="texto_normal"></span></div>		</td>
        </tr>
      <tr>
        <td class="texto_normal_bold">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio del Negocio </td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3_neg" type="text" class="texto_normal" id="x_calle3_neg" value="<?php echo htmlentities(@$x_calle3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3_neg" type="text" class="texto_normal" id="x_colonia3_neg" value="<?php echo htmlentities(@$x_colonia3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3_neg\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint3_neg', 'x_delegacion_id3_neg')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id3_neg) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="400"><div align="left"><span class="texto_normal">
          <input type="hidden" name="x_delegacion_id_temp1_2" value="" />
          <input type="hidden" name="x_delegacion_id_temp2" value="" />
           </span><span class="texto_normal">
		<div id="txtHint3_neg" class="texto_normal">
			Del/Mun:           
            <?php
		if($x_entidad_id3_neg > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id3_neg\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id3_neg";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id3_neg) {
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
        <td width="16"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal3_neg" type="text" class="texto_normal" id="x_codigo_postal3_neg" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal3_neg) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion3_neg" type="text" class="texto_normal" id="x_ubicacion3_neg" value="<?php echo htmlentities(@$x_ubicacion3_neg) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3_neg" type="text" class="texto_normal" id="x_antiguedad3_neg" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3_neg) ?>" size="2" maxlength="2"/>
          (a&ntilde;os) </span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.;</td>
        <td colspan="4"><input name="x_telefono3_neg" type="text" class="texto_normal" id="x_telefono3_neg" value="<?php echo htmlspecialchars(@$x_telefono3_neg) ?>" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Ocupaci&oacute;n: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_ocupacion" type="text" class="texto_normal" id="x_ocupacion" value="<?php echo htmlspecialchars(@$x_ocupacion) ?>" size="30" maxlength="150" />
          </span></td>
      </tr>
      <tr>
        <td class="texto_normal">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table>      <!-- 	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Garantías</div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">

	<table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Descripción</span></td>
        <td width="84" class="texto_normal">&nbsp;</td>
        <td width="163" class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="x_garantia_desc" type="text" class="texto_normal" id="x_garantia_desc" value="<?php echo htmlentities(@$x_garantia_desc) ?>" size="115" maxlength="250"  /></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Valor</span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input name="x_garantia_valor" type="text" class="texto_normal" id="x_garantia_valor" value="<?php echo htmlspecialchars(@$x_garantia_valor) ?>" size="20" maxlength="20" onkeypress="return solonumeros(this,event)" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	
<!--	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  

  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales Titular</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240"><span class="texto_normal">Ingresos del Negocio: </span></td>
        <td width="122"><input class="importe" name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td width="134" class="texto_normal">Venta Mensual</td>
        <td width="204">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Otros Ingresos: </span></td>
        <td><input class="importe" name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td class="texto_normal">Origen:</td>
        <td><input class="texto_normal" name="x_origen_ingresos" type="text" id="x_origen_ingresos" value="<?php echo htmlentities(@$x_origen_ingresos) ?>" size="30" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_1" type="text" id="x_ingresos_familiar_1" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
		<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos2" type="text" id="x_origen_ingresos2" value="<?php echo htmlentities(@$x_origen_ingresos2) ?>" size="30" maxlength="150" />
        </div></td>
        </tr>      
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_2" type="text" id="x_ingresos_familiar_2" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" /></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td>
		<?php
		$x_parentesco_tipo_id2List = "<select name=\"x_parentesco_tipo_id_ing_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_id2List .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_id2List .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_2) {
					$x_parentesco_tipo_id2List .= "' selected";
				}
				$x_parentesco_tipo_id2List .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_id2List .= "</select>";
		echo $x_parentesco_tipo_id2List;
		?>		</td>
      </tr>
<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos3" type="text" id="x_origen_ingresos3" value="<?php echo htmlentities(@$x_origen_ingresos3) ?>" size="30" maxlength="150" />
        </div></td>
        </tr>      
      
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6">Gastos Mensuales Titular</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="241">Proveedor 1:</td>
        <td width="459"><input class="importe" name="x_gastos_prov1" type="text" id="x_gastos_prov1" value="<?php echo htmlspecialchars(@$x_gastos_prov1) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 2:</td>
        <td><input class="importe" name="x_gastos_prov2" type="text" id="x_gastos_prov2" value="<?php echo htmlspecialchars(@$x_gastos_prov2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 3:</td>
        <td><input class="importe" name="x_gastos_prov3" type="text" id="x_gastos_prov3" value="<?php echo htmlspecialchars(@$x_gastos_prov3) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otro Proveedor:</td>
        <td><input class="importe" name="x_otro_prov" type="text" id="x_otro_prov" value="<?php echo htmlspecialchars(@$x_otro_prov) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Empleados:</td>
        <td><input class="importe" name="x_gastos_empleados" type="text" id="x_gastos_empleados" value="<?php echo htmlspecialchars(@$x_gastos_empleados) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Renta Local u Oficina del Negocio:</td>
        <td><input class="importe" name="x_gastos_renta_negocio" type="text" id="x_gastos_renta_negocio" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otros Gastos:</td>
        <td><input class="importe" name="x_gastos_otros" type="text" id="x_gastos_otros" value="<?php echo htmlspecialchars(@$x_gastos_otros) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales Aval</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="122"><input class="importe" name="x_ingresos_mensuales" type="text" id="x_ingresos_mensuales" value="<?php echo htmlspecialchars(@$x_ingresos_mensuales) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td width="123" class="texto_normal">&nbsp;</td>
        <td width="215">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Otros Ingresos: </span></td>
        <td><input class="importe" name="x_otros_ingresos_aval" type="text" id="x_otros_ingresos_aval" value="<?php echo htmlspecialchars(@$x_otros_ingresos_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td class="texto_normal">Origen:</td>
        <td><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval" type="text" id="x_origen_ingresos_aval" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval) ?>" size="30" maxlength="150" />
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_1_aval" type="text" id="x_ingresos_familiar_1_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_1_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_1_aval) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval2" type="text" id="x_origen_ingresos_aval2" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval2) ?>" size="30" maxlength="150" />
        </span></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_2_aval" type="text" id="x_ingresos_familiar_2_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_2_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_2_aval) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval3" type="text" id="x_origen_ingresos_aval3" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval3) ?>" size="30" maxlength="150" />
        </span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Gastos Mensuales Aval</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="241">Proveedor 1:</td>
        <td width="459"><input class="importe" name="x_gastos_prov1_aval" type="text" id="x_gastos_prov1_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 2:</td>
        <td><input class="importe" name="x_gastos_prov2_aval" type="text" id="x_gastos_prov2_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov2_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 3:</td>
        <td><input class="importe" name="x_gastos_prov3_aval" type="text" id="x_gastos_prov3_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov3_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otro Proveedor:</td>
        <td><input class="importe" name="x_otro_prov_aval" type="text" id="x_otro_prov_aval" value="<?php echo htmlspecialchars(@$x_otro_prov_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Empleados:</td>
        <td><input class="importe" name="x_gastos_empleados_aval" type="text" id="x_gastos_empleados_aval" value="<?php echo htmlspecialchars(@$x_gastos_empleados_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Renta Local u Oficina del Negocio:</td>
        <td><input class="importe" name="x_gastos_renta_negocio_aval" type="text" id="x_gastos_renta_negocio_aval" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otros Gastos:</td>
        <td><input class="importe" name="x_gastos_otros_aval" type="text" id="x_gastos_otros_aval" value="<?php echo htmlspecialchars(@$x_gastos_otros_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias</td>
    </tr>
  <tr>
    <td colspan="3" class="texto_normal">Indique por lo menos una referencia de trabajo (Cliente ó Proveedor) </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Nombre</span></td>
        <td width="84" class="texto_normal">Teléfono</td>
        <td width="163" class="texto_normal">Parentesco</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_1" type="text" class="texto_normal" id="x_nombre_completo_ref_1" value="<?php echo htmlentities(@$x_nombre_completo_ref_1) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_1" type="text" class="texto_normal" id="x_telefono_ref_1" value="<?php echo htmlspecialchars(@$x_telefono_ref_1) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_2" type="text" class="texto_normal" id="x_nombre_completo_ref_2" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_2) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_2" type="text" class="texto_normal" id="x_telefono_ref_2" value="<?php echo htmlspecialchars(@$x_telefono_ref_2) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_2) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_3" type="text" class="texto_normal" id="x_nombre_completo_ref_3" value="<?php echo htmlentities(@$x_nombre_completo_ref_3) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_3" type="text" class="texto_normal" id="x_telefono_ref_3" value="<?php echo htmlspecialchars(@$x_telefono_ref_3) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_3\"  class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_3) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_4" type="text" class="texto_normal" id="x_nombre_completo_ref_4" value="<?php echo htmlentities(@$x_nombre_completo_ref_4) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_4" type="text" class="texto_normal" id="x_telefono_ref_4" value="<?php echo htmlspecialchars(@$x_telefono_ref_4) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_4\"  class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_4) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_5" type="text" class="texto_normal" id="x_nombre_completo_ref_5" value="<?php echo htmlentities(@$x_nombre_completo_ref_5) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_5" type="text" class="texto_normal" id="x_telefono_ref_5" value="<?php echo htmlspecialchars(@$x_telefono_ref_5) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_5\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_5) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Promotor</span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_promotor" cols="60" rows="5"><?php echo $x_comentario_promotor; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Comite de Cr&eacute;dito </span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo $x_comentario_comite; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

    <td colspan="3" align="left" valign="top" class="texto_normal">
	
	</td>
    </tr>
    <tr>
      <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6"><span class="texto_normal_bold">Checklist Promotores</span></td>
    </tr>
    <tr>
      <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="34">&nbsp;</td>
          <td width="600">&nbsp;</td>
          <td width="66">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="left" class="texto_small">
            <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>TITULAR</strong></td>
                <td>&nbsp;</td>
                <td><strong>Comentarios</strong></td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="38" align="center" valign="middle"><input type="checkbox" name="x_checklist_1" id="x_checklist_1" <?php if($x_checklist_1 == 1){echo "checked='checked'";}?> /></td>
                <td width="9">&nbsp;</td>
                <td width="229">Verificación del Negocio</td>
                <td width="10">&nbsp;</td>
                <td width="364"><input name="x_det_ck1" type="text" id="x_det_ck1" value="<?php echo $x_det_ck1; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input name="x_checklist_2" type="checkbox" id="x_checklist_2"  <?php if($x_checklist_2 == 1){echo "checked='checked'";}?>  /></td>
                <td>&nbsp;</td>
                <td>Verificación Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck2" type="text" id="x_det_ck2" value="<?php echo $x_det_ck2; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_3" id="x_checklist_3" <?php if($x_checklist_3 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Firma Buro de Crédito</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck3" type="text" id="x_det_ck3" value="<?php echo $x_det_ck3; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_4" id="x_checklist_4" <?php if($x_checklist_4 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Identificación oficial</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck4" type="text" id="x_det_ck4" value="<?php echo $x_det_ck4; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_5" id="x_checklist_5" <?php if($x_checklist_5 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio particular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck5" type="text" id="x_det_ck5" value="<?php echo $x_det_ck5; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_6" id="x_checklist_6" <?php if($x_checklist_6 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio de Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck6" type="text" id="x_det_ck6" value="<?php echo $x_det_ck6; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_7" id="x_checklist_7" <?php if($x_checklist_7 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos para estudio de capacidad de pago.</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck7" type="text" id="x_det_ck7" value="<?php echo $x_det_ck7; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_8" id="x_checklist_8" <?php if($x_checklist_8 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos garantía</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck8" type="text" id="x_det_ck8" value="<?php echo $x_det_ck8; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_9" id="x_checklist_9" <?php if($x_checklist_9 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Telefono(s)</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck9" type="text" id="x_det_ck9" value="<?php echo $x_det_ck9; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_10" id="x_checklist_10" <?php if($x_checklist_10 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Referencias</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck10" type="text" id="x_det_ck10" value="<?php echo $x_det_ck10; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_11" id="x_checklist_11" <?php if($x_checklist_11 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Titular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck11" type="text" id="x_det_ck11" value="<?php echo $x_det_ck11; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_12" id="x_checklist_12" <?php if($x_checklist_12 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck12" type="text" id="x_det_ck12" value="<?php echo $x_det_ck12; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_13" id="x_checklist_13" <?php if($x_checklist_13 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck13" type="text" id="x_det_ck13" value="<?php echo $x_det_ck13; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td><strong>AVAL</strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_14" id="x_checklist_14" <?php if($x_checklist_14 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Verificación del Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck14" type="text" id="x_det_ck14" value="<?php echo $x_det_ck14; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_15" id="x_checklist_15" <?php if($x_checklist_15 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Verificación Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck15" type="text" id="x_det_ck15" value="<?php echo $x_det_ck15; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_16" id="x_checklist_16" <?php if($x_checklist_16 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Firma Buro de Crédito</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck16" type="text" id="x_det_ck16" value="<?php echo $x_det_ck16; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_17" id="x_checklist_17" <?php if($x_checklist_17 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Identificación oficial</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck17" type="text" id="x_det_ck17" value="<?php echo $x_det_ck17; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_18" id="x_checklist_18" <?php if($x_checklist_18 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio Particular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck18" type="text" id="x_det_ck18" value="<?php echo $x_det_ck18; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_19" id="x_checklist_19" <?php if($x_checklist_19 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio de Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck19" type="text" id="x_det_ck19" value="<?php echo $x_det_ck19; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_20" id="x_checklist_20" <?php if($x_checklist_20 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos para estudio de capacidad de pago.</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck20" type="text" id="x_det_ck20" value="<?php echo $x_det_ck20; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_21" id="x_checklist_21" <?php if($x_checklist_21 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Telefono(s)</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck21" type="text" id="x_det_ck21" value="<?php echo $x_det_ck21; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_22" id="x_checklist_22" <?php if($x_checklist_22 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Referencias</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck22" type="text" id="x_det_ck22" value="<?php echo $x_det_ck22; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_23" id="x_checklist_23" <?php if($x_checklist_23 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Aval</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck23" type="text" id="x_det_ck23" value="<?php echo $x_det_ck23; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_24" id="x_checklist_24" <?php if($x_checklist_24 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck24" type="text" id="x_det_ck24" value="<?php echo $x_det_ck24; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_25" id="x_checklist_25" <?php if($x_checklist_25 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck25" type="text" id="x_det_ck25" value="<?php echo $x_det_ck25; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="middle">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6"><?php if($_SESSION["crm_UserRolID"] < 4){ ?>
            Checklist Coordinador de Créditos
            <?php } ?></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
          
          <?php if($_SESSION["crm_UserRolID"] < 4){ ?>
          
          
          <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><strong>Revision de Datos</strong></td>
              <td>&nbsp;</td>
              <td><strong>Comentarios</strong></td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_32" id="x_checklist_32" <?php if($x_checklist_32 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Historial Crediticio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck32" type="text" id="x_det_ck32" value="<?php echo $x_det_ck32; ?>" size="60" /></td>
            </tr>
            <tr>
              <td width="38" align="center" valign="middle"><input type="checkbox" name="x_checklist_26" id="x_checklist_26" <?php if($x_checklist_26 == 1){echo "checked='checked'";}?> /></td>
              <td width="9">&nbsp;</td>
              <td width="229">Validacion de datos del arrendador</td>
              <td width="10">&nbsp;</td>
              <td width="364"><input name="x_det_ck26" type="text" id="x_det_ck26" value="<?php echo $x_det_ck26; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input name="x_checklist_27" type="checkbox" id="x_checklist_27"  <?php if($x_checklist_27 == 1){echo "checked='checked'";}?>  /></td>
              <td>&nbsp;</td>
              <td>Validacion de datos del aval</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck27" type="text" id="x_det_ck27" value="<?php echo $x_det_ck27; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_28" id="x_checklist_28" <?php if($x_checklist_28 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion de datos titular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck28" type="text" id="x_det_ck28" value="<?php echo $x_det_ck28; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_29" id="x_checklist_29" <?php if($x_checklist_29 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion de referencias titular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck29" type="text" id="x_det_ck29" value="<?php echo $x_det_ck29; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_30" id="x_checklist_30" <?php if($x_checklist_30 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion referencias aval</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck30" type="text" id="x_det_ck30" value="<?php echo $x_det_ck30; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_31" id="x_checklist_31" <?php if($x_checklist_31 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Opinion de Capacidad de Pago</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck31" type="text" id="x_det_ck31" value="<?php echo $x_det_ck31; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          
          <?php } ?>          
          
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
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


















		  } ?>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center">

    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>


</body>
</html>


