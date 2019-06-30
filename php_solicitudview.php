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

// Get key
$x_solicitud_id = @$_GET["solicitud_id"];
if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	ob_end_clean();
	header("Location: php_solicitudlist.php");
	exit();
}

//$x_solicitud_id = (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker"><br>
  <br>
<a href="php_solicitudlist.php">Regresar a la lista</a></span></p>
<p>



<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="674" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="texto_normal"><a href="../crm/modulos/php_galeria.php?x_cliente_id= <?php echo $x_cliente_id; ?>" target="_blank">Fotograf&iacute;as</a></td>
        <td colspan="2">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Folio:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_folio) ?> </b></div></td>
        <td class="texto_normal"><div align="right">Status:</div></td>
        <td class="texto_normal"><b> &nbsp;
                  <?php
if ((!is_null($x_solicitud_status_id)) && ($x_solicitud_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `solicitud_status`";
	$sTmp = $x_solicitud_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `solicitud_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_solicitud_status_id = $x_solicitud_status_id; // Backup Original Value
$x_solicitud_status_id = $sTmp;
?>
                  <?php echo $x_solicitud_status_id; ?>
                  <?php $x_solicitud_status_id = $ox_solicitud_status_id; // Restore Original Value ?>
        </b></td>
      </tr>
      <tr>
        <td class="texto_normal">Cliente No:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_cliente_id) ?> </b></div></td>
        <td><div align="right"><span class="texto_normal">Credito No:</span></div></td>
        <td><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_credito_id) ?> </b></div></td>
      </tr>
      <tr>
        <td class="texto_normal">Promotor:</td>
        <td colspan="4" class="texto_normal">
            <?php
if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `promotor`";
	$sTmp = $x_promotor_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_id = $x_promotor_id; // Backup Original Value
$x_promotor_id = $sTmp;
?>
            <?php echo $x_promotor_id; ?>
            <?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>        </td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="2" class="texto_normal"><?php
		$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo` where credito_tipo_id = $x_credito_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo FormatDateTime($x_fecha_registro,7); ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php FormatDateTime($x_fecha_registro,7); ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe solicitado: </span></td>
        <td width="111" class="texto_normal"><div align="left"> <?php echo FormatNumber(@$x_importe_solicitado,0,0,0,1) ?> </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Plazo:</span></div></td>
        <td><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT `descripcion` FROM `plazo` where plazo_id = $x_plazo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
        <td><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT `descripcion` FROM `forma_pago` where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Actividad Empresarial:</span></td>
        <td colspan="4"><span class="phpmaker">
          <?php
		$x_estado_civil_idList = "<select name=\"x_actividad_id\" class=\"texto_normal\" onchange='act()'>";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `actividad_id`, `descripcion` FROM `actividad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["actividad_id"] == @$x_actividad_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4" class="texto_normal">      &nbsp;
      <div id="actividad1" class="TG_visible">Específicamente:</div>
      <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td>

      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><textarea name="x_actividad_desc" cols="60" rows="5" id="x_actividad_desc"><?php echo $x_actividad_desc; ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165" class="texto_normal">Titular:</td>
        <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center">
              <input name="x_nombre_completo" type="text" class="texto_normal" id="x_nombre_completo" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>" size="25" maxlength="250" />
            </div></td>
            <td width="178"><div align="center">
              <input name="x_apellido_paterno" type="text" class="texto_normal" id="x_apellido_paterno" value="<?php echo htmlspecialchars(@$x_apellido_paterno) ?>" size="25" maxlength="250" />
            </div></td>
            <td width="201"><div align="center">
              <input name="x_apellido_materno" type="text" class="texto_normal" id="x_apellido_materno" value="<?php echo htmlspecialchars(@$x_apellido_materno) ?>" size="25" maxlength="250" />
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
        <td colspan="4"><input name="x_tit_rfc" type="text" class="texto_normal" id="x_tit_rfc" value="<?php echo htmlspecialchars(@$x_tit_rfc) ?>" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td class="texto_normal">CURP:</td>
        <td colspan="4"><input name="x_tit_curp" type="text" class="texto_normal" id="x_tit_curp" value="<?php echo htmlspecialchars(@$x_tit_curp) ?>" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Oficio:</td>
        <td colspan="4"><span class="texto_normal">
          <?php
		$x_vivienda_tipo_idList = "<select name=\"x_oficio_id\" class=\"texto_normal\" >";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `oficio_id`, `descripcion` FROM `oficio`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["oficio_id"] == @$x_oficio_id) {
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
        <td class="texto_normal">Otro oficio:</td>
        <td colspan="4"><input name="x_oficio" type="text" class="texto_normal" id="x_oficio" value="<?php echo htmlspecialchars(@$x_oficio) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal"><span class="texto_normal">Tipo de Negocio: </span></td>
        <td colspan="4"><span class="texto_normal">
          <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_negocio_id\" class=\"texto_normal\" >";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `tipo_negocio_id`, `descripcion` FROM `tipo_negocio`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["tipo_negocio_id"] == @$x_tipo_negocio_id) {
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
        <td class="texto_normal">Otro tipo de negocio:</td>
        <td colspan="4"><input name="x_tipo_negocio" type="text" class="texto_normal" id="x_tipo_negocio" value="<?php echo htmlspecialchars(@$x_tipo_negocio) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
        <td colspan="4"><table width="533" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="121" align="left"><span class="texto_normal">
              <input name="x_tit_fecha_nac" type="text" id="x_tit_fecha_nac" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="12" maxlength="12" />
              &nbsp;<img src="images/ew_calendar.gif" id="cx_tit_fecha_nac" alt="Calendario" style="cursor:pointer;cursor:hand;" />
              <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_tit_fecha_nac", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_tit_fecha_nac" // ID of the button
            }
            );
            </script>
            </span></td>
            <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
              <input name="x_sexo" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo == "1") {  echo "checked='checked'"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo" <?php if (@$x_sexo == "2") { echo "checked='checked'"; } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?></span></div></td>
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
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td width="535" colspan="3"><input name="x_nombre_conyuge" type="text" class="texto_normal" id="x_nombre_conyuge" value="<?php echo htmlspecialchars(@$x_nombre_conyuge) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3"><input name="x_email" type="text" class="texto_normal" id="x_email" value="<?php echo htmlspecialchars(@$x_email) ?>" size="50" maxlength="150" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Nacionalidad:</td>
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
				$x_nac_idList .= ">" . $datawrk["pais_nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_nac_idList .= "</select>";
		echo $x_nac_idList;

		?>
        </span></td>
      </tr>
      
      <tr>
        <td class="texto_normal">Rol en el hogar:</td>
        <td colspan="3"><?php
		$x_entidad_idList = "<select name=\"x_rol_hogar_id\" id=\"x_rol_hogar_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_hogar_id`, `descripcion` FROM `rol_hogar`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_hogar_id"] == @$x_rol_hogar_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
      </tr>
      <tr>
        <td class="texto_normal">Fecha inicio actividad productiva:</td>
        <td colspan="3"><span><input type="text" name="x_fecha_ini_act_prod" id="x_fecha_ini_act_prod" value="<?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>"   maxlength="100" size="30"/>
      &nbsp;<img src="images/ew_calendar.gif" id="cxfecha_ini_act_prod" onclick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_ini_act_prod', 
           ifFormat : '%d/%m/%Y', 
            button : 'cxfecha_ini_act_prod' 
            }
            );" style="cursor:pointer;cursor:hand;" /></span></td>
      </tr>
       <tr>
        <td class="texto_normal">Destino Credito</td>
        <td colspan="3"><?php
		$x_entidad_idList = "<select name=\"x_destino_credito_id\" id=\"x_destino_credito_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `destino_credito_id`, `descripcion` FROM `destino_credito`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["destino_credito_id"] == @$x_destino_credito_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
      </tr>
    </table></td>
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
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Calle : </span></td>
        <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlentities(@$x_calle) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">No exterior  : </span></td>
        <td colspan="3"><input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20"/></td>
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
	    &nbsp;Compa&ntilde;ia&nbsp;<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id\" id=\"x_compania_celular_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></span></td>
    </table></td>
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
        <td colspan="4"><div align="left">
          <input type="checkbox" name="x_mismos" value="0" onclick="mismosdom()" />
          &nbsp;<span class="texto_normal">Mismos que el Dom. Part.</span> </div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><input name="x_empresa" type="text" class="texto_normal" id="x_empresa" value="<?php echo htmlspecialchars(@$x_empresa) ?>" size="80" maxlength="250" /></td>
      </tr>
        
      <tr>
        <td class="texto_normal">Giro Negocio</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><?php
		$x_entidad_idList = "<select name=\"x_giro_negocio_id\" id=\"x_giro_negocio_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `giro_negocio_id`, `descripcion` FROM `giro_negocio`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["giro_negocio_id"] == @$x_giro_negocio_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
            <td width="19%">Tipo Negocio</td>
            <td width="40%"><?php
		$x_entidad_idList = "<select name=\"x_tipo_inmueble_id\" id=\"x_tipo_inmueble_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `tipo_inmueble_id`, `descripcion` FROM `tipo_inmueble`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["tipo_inmueble_id"] == @$x_tipo_inmueble_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
          </tr>
        </table></td>
      </tr>
       <tr>
        <td class="texto_normal">Atiende Titular</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><select name="x_atiende_titular">
      <option>Seleccione</option>
		<option value="si" <?php if($x_atiende_titular == "si"){?> selected="selected" <?php } ?>>Si</option>
        <option value="no" <?php if($x_atiende_titular == "no"){?> selected="selected" <?php } ?>>No</option>
      </select></td>
            <td width="19%">No.Personas trabajando</td>
            <td width="40%"><input type="text" name="x_personas_trabajando" value="<?php echo($x_personas_trabajando);?>" maxlength="20" size="10" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3"><input name="x_puesto" type="text" class="texto_normal" id="x_puesto" value="<?php echo htmlspecialchars(@$x_puesto) ?>" size="80" maxlength="250" /></td>
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
        <td colspan="3"><input name="x_calle2" type="text" class="texto_normal" id="x_calle2" value="<?php echo htmlspecialchars(@$x_calle2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia2" type="text" class="texto_normal" id="x_colonia2" value="<?php echo htmlspecialchars(@$x_colonia2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id2\" class=\"texto_normal\" onchange=\"buscadelegacion()\">";
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
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal2" type="text" class="texto_normal" id="x_codigo_postal2" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal2) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion2" type="text" class="texto_normal" id="x_ubicacion2" value="<?php echo htmlspecialchars(@$x_ubicacion2) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad2" type="text" class="texto_normal" id="x_antiguedad2" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad2) ?>" size="2" maxlength="2"/>
          (a&ntilde;os)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4"><input name="x_telefono2" type="text" class="texto_normal" id="x_telefono2" value="<?php echo htmlspecialchars(@$x_telefono2) ?>" size="20" maxlength="20" />
              <span class="texto_normal">&nbsp; </span></td>
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
              <input name="x_nombre_completo_aval" type="text" class="texto_normal" id="x_nombre_completo_aval" value="<?php echo htmlspecialchars(@$x_nombre_completo_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">
              <input name="x_apellido_paterno_aval" type="text" class="texto_normal" id="x_apellido_paterno_aval" value="<?php echo htmlspecialchars(@$x_apellido_paterno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">
              <input name="x_apellido_materno_aval" type="text" class="texto_normal" id="x_apellido_materno_aval" value="<?php echo htmlspecialchars(@$x_apellido_materno_aval) ?>" size="25" maxlength="100" />
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
              <input name="x_sexo_aval" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo_aval == "1") { echo "checked='checked'"; } ?>" />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo_aval" <?php if (@$x_sexo_aval == "2") { echo "checked='checked'"; } ?>" value="<?php echo htmlspecialchars("2"); ?>" />
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
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td colspan="2"><input name="x_nombre_conyuge_aval" type="text" class="texto_normal" id="x_nombre_conyuge_aval" value="<?php echo htmlspecialchars(@$x_nombre_conyuge_aval) ?>" size="80" maxlength="250" /></td>
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
				$x_nac_idList .= ">" . $datawrk["pais_nombre"] . "</option>";
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
        <td colspan="4" class="texto_normal"><input type="checkbox" name="x_mismos_titluar" value="0" onclick="mismosdomtit()" />
          Mismos domicilios que el  Titular.</td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio Particular </td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3" type="text" class="texto_normal" id="x_calle3" value="<?php echo htmlspecialchars(@$x_calle3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3" type="text" class="texto_normal" id="x_colonia3" value="<?php echo htmlspecialchars(@$x_colonia3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3\" class=\"texto_normal\" onchange=\"buscadelegacion()\">";
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
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
          Del/Mun: </span><span class="texto_normal">
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
        <td colspan="4"><input name="x_ubicacion3" type="text" class="texto_normal" id="x_ubicacion3" value="<?php echo htmlspecialchars(@$x_ubicacion3) ?>" size="80" maxlength="250" /></td>
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
        <td colspan="4" class="texto_normal"><div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"> Propietario de la Vivienda:&nbsp;
          <input class="texto_normal" type="text" name="x_propietario2" value="<?php echo $x_propietario2; ?>" size="50" maxlength="150" />
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal"><div align="left">
          <input type="checkbox" name="x_mismosava" value="0" onclick="mismosdomava()" />
          &nbsp;Mismos que el Dom. Part. </div></td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio del Negocio </td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3_neg" type="text" class="texto_normal" id="x_calle3_neg" value="<?php echo htmlspecialchars(@$x_calle3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3_neg" type="text" class="texto_normal" id="x_colonia3_neg" value="<?php echo htmlspecialchars(@$x_colonia3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3_neg\" class=\"texto_normal\" onchange=\"buscadelegacion()\">";
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
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
          Del/Mun: </span><span class="texto_normal">
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
        <td width="16"><div align="left"></div></td>
      </tr>
      <!---
	  <tr>
	    <td class="texto_normal">Otra delegaci&oacute;n: </td>
	    <td colspan="4">
		<input name="x_otra_delegacion3" type="text" class="texto_normal" id="x_otra_delegacion3" value="<?php echo htmlspecialchars(@$x_otra_delegacion3) ?>" size="80" maxlength="250" />		</td>
	    </tr>
		-->
      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal3_neg" type="text" class="texto_normal" id="x_codigo_postal3_neg" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal3_neg) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion3_neg" type="text" class="texto_normal" id="x_ubicacion3_neg" value="<?php echo htmlspecialchars(@$x_ubicacion3_neg) ?>" size="80" maxlength="250" /></td>
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
        <td class="texto_normal_bold">Ingresos</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Ingresos Mensuales : </td>
        <td colspan="4"><input class="importe" name="x_ingresos_mensuales" type="text" id="x_ingresos_mensuales" value="<?php echo htmlspecialchars(@$x_ingresos_mensuales) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td class="texto_normal">Otros Ingresos: </td>
        <td colspan="4"><input class="importe" name="x_otros_ingresos_aval" type="text" id="x_otros_ingresos_aval" value="<?php echo htmlspecialchars(@$x_otros_ingresos_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" />
          <span class="texto_normal">&nbsp;Origen:
            <input class="texto_normal" name="x_origen_ingresos_aval" type="text" id="x_origen_ingresos_aval" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval) ?>" size="30" maxlength="150" />
          </span></td>
      </tr>
      <tr>
        <td class="texto_normal">Ingresos Familiares: </td>
        <td colspan="4"><input class="importe" name="x_ingresos_familiar_1_aval" type="text" id="x_ingresos_familiar_1_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>
          <span class="texto_normal">Parentesco:</span>
          <?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td class="texto_normal">Origen: </td>
        <td colspan="4"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval2" type="text" id="x_origen_ingresos_aval2" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval2) ?>" size="30" maxlength="150" />
        </span></td>
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
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Garant&iacute;as</div></td>
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
            <td width="165"><span class="texto_normal">Descripci&oacute;n</span></td>
            <td width="84" class="texto_normal">&nbsp;</td>
            <td width="163" class="texto_normal">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><input name="x_garantia_desc" type="text" class="texto_normal" id="x_garantia_desc" value="<?php echo htmlspecialchars(@$x_garantia_desc) ?>" size="115" maxlength="250"></td>
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

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="122"><input class="importe" name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td width="120" class="texto_normal">Otros Ingresos: </td>
        <td width="218"><input class="importe" name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="texto_normal">Origen:</td>
        <td>
		<input class="texto_normal" name="x_origen_ingresos" type="text" id="x_origen_ingresos" value="<?php echo htmlspecialchars(@$x_origen_ingresos) ?>" size="30" maxlength="150"  /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_1" type="text" id="x_ingresos_familiar_1" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>        </td>
      </tr>
<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos2" type="text" id="x_origen_ingresos2" value="<?php echo htmlspecialchars(@$x_origen_ingresos2) ?>" size="30" maxlength="150" />
        </div></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_2" type="text" id="x_ingresos_familiar_2" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td><?php
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
				$x_parentesco_tipo_id2List .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_id2List .= "</select>";
		echo $x_parentesco_tipo_id2List;
		?>        </td>
      </tr>
<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos3" type="text" id="x_origen_ingresos3" value="<?php echo htmlspecialchars(@$x_origen_ingresos3) ?>" size="30" maxlength="150" />
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
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias</td>
  </tr>
  <tr>
    <td colspan="3" class="texto_normal">Indique por lo menos una referencia de trabajo (Cliente &oacute; Proveedor) </td>
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
        <td width="84" class="texto_normal">Tel&eacute;fono</td>
        <td width="163" class="texto_normal">Parentesco</td>
      </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_1" type="text" class="texto_normal" id="x_nombre_completo_ref_1" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_1) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_1" type="text" class="texto_normal" id="x_telefono_ref_1" value="<?php echo htmlspecialchars(@$x_telefono_ref_1) ?>" size="20" maxlength="20" /></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td><input name="x_nombre_completo_ref_2" type="text" class="texto_normal" id="x_nombre_completo_ref_2" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_2) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_2" type="text" class="texto_normal" id="x_telefono_ref_2" value="<?php echo htmlspecialchars(@$x_telefono_ref_2) ?>" size="20" maxlength="20" /></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td><input name="x_nombre_completo_ref_3" type="text" class="texto_normal" id="x_nombre_completo_ref_3" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_3) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_3" type="text" class="texto_normal" id="x_telefono_ref_3" value="<?php echo htmlspecialchars(@$x_telefono_ref_3) ?>" size="20" maxlength="20" /></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td><input name="x_nombre_completo_ref_4" type="text" class="texto_normal" id="x_nombre_completo_ref_4" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_4) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_4" type="text" class="texto_normal" id="x_telefono_ref_4" value="<?php echo htmlspecialchars(@$x_telefono_ref_4) ?>" size="20" maxlength="20" /></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td><input name="x_nombre_completo_ref_5" type="text" class="texto_normal" id="x_nombre_completo_ref_5" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_5) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_5" type="text" class="texto_normal" id="x_telefono_ref_5" value="<?php echo htmlspecialchars(@$x_telefono_ref_5) ?>" size="20" maxlength="20" /></td>
        <td><?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
    <td align="left" valign="top"><div align="center">
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
    <td align="left" valign="top"><div align="center">
      <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo $x_comentario_comite; ?></textarea>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">T&eacute;rminos y condiciones </div></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="34">&nbsp;</td>
        <td width="600">&nbsp;</td>
        <td width="66">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left" class="texto_small">Por este conducto autorizo expresamente a Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R., para que por conducto de sus funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento e historia&iacute; crediticio, asi como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga, en las Sociedades de Informaci&oacute;n Crediticia que estime conveniente. As&iacute; mismo, declaro que conozco la naturaleza y alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R. har&aacute; de ta&iacute; informaci&oacute;n y de que &eacute;sta podr&aacute; realzar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que esta autorizaci&oacute;n se encuentre vigente por un periodo de 3 a&ntilde;os contados a partir de la fecha de su expedici&oacute;n y
            en todo caso durante el tiempo que mantengamos una relaci&oacute;n jur&iacute;dica. Estoy conciente y acepto que este documento quede bajo propiedad de Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R. para efectos de control y cumplimiento del art. 28 de la Ley para regular a las Sociedades e informaci&oacute;n Cr&eacute;diticia. <br />
            <br />
            De acuerdo al Cap&iacute;tulo II, Secci&oacute;n Primera, Art&iacute;culo 3, cl&aacute;usula cuatro de la Ley para la Transparencia y Ordenamiento de los Servicios Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto M&uacute;ltiple No Reguladas; por &eacute;ste medio expreso mi consentimiento que a trav&eacute;s del personal facultado de &quot;Microfinanciera xxx SOFOM ENR&quot;, he sido enterado del Costo Anual Total del cr&eacute;dito que estoy interesado en celebrar. Tambi&eacute;n he sido enterado de la tasa de inter&eacute;s moratoria que se cobrar&aacute; en caso de presentar atraso(s) en alguno(s) de los vencimientos del pr&eacute;stamo. Tambi&eacute;n de acuerdo al Cap&iacute;tulo IV, Secci&oacute;n Primera, Art&iacute;culo 23 de la misma; estoy de acuerdo
            en consultar mi estado de cuenta a trav&eacute;s de internet mediante la p&aacute;gina www.financieraxxx.com con el usuario y contrase&ntilde;a que &quot;Microfinanciera xxx SOFOM ENR&quot; a trav&eacute;s de su personal facultado me hagan saber toda vez que se firme el pagar&eacute; correspondiente al cr&eacute;dito que estoy interesado en pactar.</div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55"><div align="center">
              <input name="x_acepto" type="checkbox" class="texto_normal" value="1" />
            </div></td>
            <td width="245" class="texto_normal">Acepto estos T&eacute;rminos y condiciones.</td>
          </tr>
        </table></td>
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
      <input name="button" type="button" class="boton_medium" onclick="window.open('php_solicitud_print.php?solicitud_id=<?php echo $x_solicitud_id; ?>','Solicitud','width=700,height=500,left=150,top=150,scrollbars=yes');" value="Imprimir" />
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
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
	global $x_solicitud_id;
	$sSql = "SELECT * FROM `solicitud`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
	$sWhere .= "(`solicitud_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $row["solicitud_status_id"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];
		$GLOBALS["x_contrato"] = $row["contrato"];
		$GLOBALS["x_pagare"] = $row["pagare"];
		$GLOBALS["x_comentario_promotor"] = $row["comentario_promotor"];
		$GLOBALS["x_comentario_comite"] = $row["comentario_comite"];
		$GLOBALS["x_actividad_id"] = $row["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $row["actividad_desc"];



//CLIENTE

		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];
		$GLOBALS["x_tit_rfc"] = $row2["rfc"];
		$GLOBALS["x_tit_curp"] = $row2["curp"];
		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];

		$GLOBALS["x_oficio_id"] = $row2["oficio_id"];
		$GLOBALS["x_oficio"] = $row2["oficio"];
		$GLOBALS["x_tipo_negocio_id"] = $row2["tipo_negocio_id"];
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];		

		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep"] = $row2["numero_hijos_dep"];
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];
		$GLOBALS["x_empresa"] = $row2["empresa"];
		$GLOBALS["x_puesto"] = $row2["puesto"];
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];
		$GLOBALS["x_rol_hogar_id"] = $row2["rol_hogar_id"];
		$GLOBALS["x_fecha_ini_act_prod"] = $row2["fecha_ini_act_prod"];
		
		
		$sSql = "select * from negocio where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];


		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		$GLOBALS["x_calle"] = $row3["calle"];
		$GLOBALS["x_colonia"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_id"] = $row3["entidad_id"];
		$GLOBALS["x_codigo_postal"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_sec"] = $row3["telefono_movil"];
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
		$GLOBALS["x_telefono_sec"] = $row3["telefono_movil"];					
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];

		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];


		$sSql = "select * from aval where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row5 = phpmkr_fetch_array($rs5);
		$GLOBALS["x_aval_id"] = $row5["aval_id"];
		$GLOBALS["x_nombre_completo_aval"] = $row5["nombre_completo"];
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];
		$GLOBALS["x_aval_rfc"] = $row5["rfc"];
		$GLOBALS["x_aval_curp"] = $row5["curp"];
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];

		$GLOBALS["x_tipo_negocio_aval"] = $row5["tipo_negocio"];
		$GLOBALS["x_edad_aval"] = $row5["edad"];

		$GLOBALS["x_tit_fecha_nac_aval"] = $row5["fecha_nac"];
		$GLOBALS["x_sexo_aval"] = $row5["sexo"];
		$GLOBALS["x_estado_civil_id_aval"] = $row5["estado_civil_id"];
		$GLOBALS["x_numero_hijos_aval"] = $row5["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep_aval"] = $row5["numero_hijos_dep"];
		$GLOBALS["x_nombre_conyuge_aval"] = $row5["nombre_conyuge"];
		$GLOBALS["x_email_aval"] = $row5["email"];
		$GLOBALS["x_nacionalidad_id_aval"] = $row5["nacionalidad_id"];

		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){
			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3 order by direccion_id desc limit 1";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id3"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_propietario2"] = $row6["propietario"];
			$GLOBALS["x_entidad_id3"] = $row6["entidad_id"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono3_sec"] = $row6["telefono_movil"];
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];


			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 4 order by direccion_id desc limit 1";
			$rs6_2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6_2 = phpmkr_fetch_array($rs6_2);
			$GLOBALS["x_direccion_id4"] = $row6_2["direccion_id"];
			$GLOBALS["x_calle3_neg"] = $row6_2["calle"];
			$GLOBALS["x_colonia3_neg"] = $row6_2["colonia"];
			$GLOBALS["x_delegacion_id3_neg"] = $row6_2["delegacion_id"];
			$GLOBALS["x_propietario2_neg"] = $row6_2["propietario"];
			$GLOBALS["x_entidad_id3_neg"] = $row6_2["entidad_id"];
			$GLOBALS["x_codigo_postal3_neg"] = $row6_2["codigo_postal"];
			$GLOBALS["x_ubicacion3_neg"] = $row6_2["ubicacion"];
			$GLOBALS["x_antiguedad3_neg"] = $row6_2["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2_neg"] = $row6_2["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = $row6_2["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3_neg"] = $row6_2["telefono"];
			$GLOBALS["x_telefono_secundario3_neg"] = $row6_2["telefono_secundario"];


			$sSql = "select * from ingreso_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row8 = phpmkr_fetch_array($rs8);
			$GLOBALS["x_ingreso_aval_id"] = $row8["ingreso_aval_id"];
			$GLOBALS["x_ingresos_mensuales"] = $row8["ingresos_negocio"];
			$GLOBALS["x_ingresos_familiar_1_aval"] = $row8["ingresos_familiar_1"];
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = $row8["parentesco_tipo_id"];
//			$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
//			$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
			$GLOBALS["x_otros_ingresos_aval"] = $row8["otros_ingresos"];
			$GLOBALS["x_origen_ingresos_aval"] = $row8["origen_ingresos"];
			$GLOBALS["x_origen_ingresos_aval2"] = $row8["origen_ingresos_fam_1"];




		}else{

			$GLOBALS["x_ingreso_id_aval"] = "";
			$GLOBALS["x_ingresos_mensuales"] = "";
			$GLOBALS["x_ingresos_familiar_1_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = "";
//			$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
//			$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
			$GLOBALS["x_otros_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval"] = "";

			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_propietario2"] = "";
			$GLOBALS["x_entidad_id3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";

			$GLOBALS["x_direccion_id4"] = "";
			$GLOBALS["x_calle3_neg"] = "";
			$GLOBALS["x_colonia3_neg"] = "";
			$GLOBALS["x_delegacion_id3_neg"] = "";
			$GLOBALS["x_propietario2_neg"] = "";
			$GLOBALS["x_entidad_id3_neg"] = "";
			$GLOBALS["x_codigo_postal3_neg"] = "";
			$GLOBALS["x_ubicacion3_neg"] = "";
			$GLOBALS["x_antiguedad3_neg"] = "";
			$GLOBALS["x_vivienda_tipo_id2_neg"] = "";
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = "";
			$GLOBALS["x_telefono3_neg"] = "";
			$GLOBALS["x_telefono_secundario3_neg"] = "";

		}


		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];

		$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];
		$GLOBALS["x_origen_ingresos"] = $row8["origen_ingresos"];
		$GLOBALS["x_origen_ingresos2"] = $row8["origen_ingresos_fam_1"];
		$GLOBALS["x_origen_ingresos3"] = $row8["origen_ingresos_fam_2"];


		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";


		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_nombre_completo_ref_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_telefono_ref_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}


//CREDITO
		if ($GLOBALS["x_solicitud_status_id"] == 6){
			$sSql = "select credito_id from credito where solicitud_id = ".$GLOBALS["x_solicitud_id"];
			$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row10 = phpmkr_fetch_array($rs10);
			$GLOBALS["x_credito_id"] = $row10["credito_id"];
		}else{
			$x_credito_id = "";
		}

	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);
	phpmkr_free_result($rs3);
	phpmkr_free_result($rs4);
	phpmkr_free_result($rs5);
	phpmkr_free_result($rs6);
	phpmkr_free_result($rs6_2);
	phpmkr_free_result($rs7);
	phpmkr_free_result($rs8);
	phpmkr_free_result($rs9);
	phpmkr_free_result($rs10);

	return $bLoadData;
}

?>
