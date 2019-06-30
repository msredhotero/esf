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
include("utilerias/datefunc.php");
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  CREA Technologies</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<p><span class="phpmaker"><br>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="674" border="0" cellspacing="0" cellpadding="0">
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
        <td colspan="4" class="texto_normal"><?php
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
              <?php echo htmlentities($x_promotor_id); ?>
              <?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>        </td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="2" class="texto_normal"><?php
		$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo` where credito_tipo_id = $x_credito_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b>
        <input type="text" name="x_fec_reg" value="<?php echo FormatDateTime($x_fecha_registro,7); ?>"  style=" border: solid 1px #FFFFFF" class="texto_normal" />
        </b> </span>
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
		echo htmlentities($datawrk["descripcion"]);
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
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Actividad Empresarial:</span></td>
        <td colspan="4"><span class="texto_normal" >
          <?php
		$sSqlWrk = "SELECT actividad_id, descripcion FROM actividad where actividad_id = $x_actividad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$x_act_desc = $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		echo $x_act_desc;
?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4" class="texto_normal">
        <?php echo $x_actividad_desc; ?>
        </td>
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
        <td width="165"><span class="texto_normal">Titular:</span></td>
        <td colspan="4" class="texto_normal"><?php echo htmlentities(@$x_nombre_completo." ".$x_apellido_paterno." ".$x_apellido_materno) ?></td>
      </tr>
	  <tr>
	    <td class="texto_normal">RFC:</td>
	    <td colspan="4" class="texto_normal">
	      <?php echo htmlentities(@$x_tit_rfc) ?>	</td>
	    </tr>
	<tr>
	  <td class="texto_normal">CURP:</td>
	  <td colspan="4" class="texto_normal">
		<?php echo htmlentities(@$x_tit_curp) ?></td>
	  </tr>
      <tr>
        <td><span class="texto_normal">Tipo de Negocio: </span></td>
        <td colspan="4" class="texto_normal"><?php echo htmlentities(@$x_tipo_negocio) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
        <td colspan="4"><table width="535" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="81"><div align="left"><span class="texto_normal">
			<?php  echo FormatDateTime(@$x_tit_fecha_nac,7); ?>
            </span></div></td>
            <td width="150"><div align="left"><span class="texto_normal">Genero:
              <?php if (@$x_sexo == "1"){ echo "M"; }else{ echo "F"; } ?>
			</span></div></td>
            <td width="304"><div align="left"><span class="texto_normal">Edo. Civil:
              <?php
		$sSqlWrk = "SELECT estado_civil_id, descripcion FROM estado_civil where estado_civil_id = $x_estado_civil_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
            </span></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><span class="texto_normal">&nbsp;No. de hijos
          : </span></td>
        <td colspan="3"><span class="texto_normal">
          	<?php echo htmlspecialchars(@$x_numero_hijos) ?>&nbsp;
          Hijos dependientes:
			<?php echo htmlspecialchars(@$x_numero_hijos_dep) ?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nombre del Conyuge:</span></td>
        <td width="535" colspan="3" class="texto_normal">
		<?php echo htmlentities(@$x_nombre_conyuge) ?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3" class="texto_normal">
		<?php echo htmlspecialchars(@$x_email) ?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nacionalidad:</span></td>
        <td colspan="3" class="texto_normal"><?php
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad where nacionalidad_id = $x_nacionalidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["pais_nombre"]);
		@phpmkr_free_result($rswrk);
		?></td>
      </tr>
       <tr>
        <td class="texto_normal">Rol en el hogar:</td>
        <td colspan="3"><?php
		if(!empty($x_rol_hogar_id)){
		$sSqlWrk = "SELECT `rol_hogar_id`, `descripcion` FROM `rol_hogar` where rol_hogar_id = $x_rol_hogar_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);		
			$datawrk = phpmkr_fetch_array($rswrk);			
				$x_rol =$datawrk["descripcion"];
	
		@phpmkr_free_result($rswrk);
		
		echo htmlentities($x_rol);
		}
		?></td>
      </tr>
      <tr>
        <td class="texto_normal">Fecha inicio actividad productiva:</td>
        <td colspan="3"><span><?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>
      &nbsp;</span></td>
      </tr>
       <tr>
        <td class="texto_normal">Destino Credito</td>
        <td colspan="3"><?php
		if(!empty($x_destino_credito_id)){
		$sSqlWrk = "SELECT `destino_credito_id`, `descripcion` FROM `destino_credito` where destino_credito_id = $x_destino_credito_id  ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);		
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_detino_credito =$datawrk["descripcion"];				
		@phpmkr_free_result($rswrk);
	
		echo htmlentities($x_detino_credito);
		}
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
        <td width="165"><span class="texto_normal">Calle  : </span></td>
        <td colspan="3" class="texto_normal">
		<?php echo htmlentities(@$x_calle) ?>
		</td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">N&uacute;mero exterior  : </span></td>
        <td colspan="3" class="texto_normal">
		<?php echo ($x_numero_exterior);?>
		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3" class="texto_normal">
		<?php echo htmlentities(@$x_colonia) ?>
		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">Del/Mun: </span><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>

      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_codigo_postal) ?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4" class="texto_normal">
		<?php echo htmlentities(@$x_ubicacion) ?>
		</td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_antiguedad) ?>
          (a&ntilde;os)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tipo de Vivienda: </td>
        <td colspan="4"><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_vivienda_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
      </tr>
      <tr>
        <td ></td>
        <td colspan="4" class="texto_normal"><div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"> Propietario de la Vivienda:&nbsp;
                  <?php echo htmlentities($x_propietario); ?>
        </div></td>
      </tr>
    <tr>
	  <td class="texto_normal">Tels. Particular: </td>
	  <td colspan="4" class="texto_normal">
      <?php echo htmlentities(@$x_telefono) ?>
	    <span class="texto_normal">&nbsp;-
	    <?php echo htmlentities(@$x_telefono_sec) ?>
	    </span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tel. Celular: </td>
	  <td colspan="4"><span class="texto_normal">
	    <?php echo htmlspecialchars(@$x_telefono_secundario) ?>
	    &nbsp;Compa&ntilde;ia&nbsp;<?php
		if(!empty($x_compania_celular_id)){
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`  WHERE compania_celular_id = $x_compania_celular_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			$datawrk = phpmkr_fetch_array($rswrk);
				
				$x_compania = $datawrk["nombre"];
		
		@phpmkr_free_result($rswrk);
		
		echo htmlentities($x_compania);
		}
		?></span></td>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
	<?php //if(@$x_calle2 != ""){ ?>
	<?php if(0 == 0){ ?>

  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio del negocio </td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top">

	<?php //if(@$x_calle2 != ""){ ?>
	<?php if(0 == 0){ ?>
	<table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3" class="texto_normal"><?php echo htmlentities(@$x_empresa) ?></td>
      </tr>
      
       <tr>
        <td class="texto_normal">Giro Negocio</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><?php
		if(!empty($x_giro_negocio_id)){
		$sSqlWrk = "SELECT `giro_negocio_id`, `descripcion` FROM `giro_negocio` where  giro_negocio_id = $x_giro_negocio_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
		$datawrk = phpmkr_fetch_array($rswrk);
				
				$x_g_neg = $datawrk["descripcion"];
		
	
		echo htmlentities($x_g_neg);
		}
		?></td>
            <td width="19%">Tipo Negocio</td>
            <td width="40%"><?php
		if(!empty($x_tipo_inmueble_id)){
		$sSqlWrk = "SELECT `tipo_inmueble_id`, `descripcion` FROM `tipo_inmueble` where tipo_inmueble_id = $x_tipo_inmueble_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
				$x_tipo_inmueble = $datawrk["descripcion"];
		
		@phpmkr_free_result($rswrk);
	
		echo htmlentities($x_tipo_inmueble);
		}
		?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_normal">Atiende Titular</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"> <?php if($x_atiende_titular == "si"){  echo "SI"; }?>
            <?php if($x_atiende_titular == "no"){  echo "NO"; }?>
        </td>
            <td width="19%">No.Personas trabajando</td>
            <td width="40%"><?php echo($x_personas_trabajando);?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3" class="texto_normal"><?php echo htmlentities(@$x_puesto) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3" class="texto_normal">
		<?php echo FormatDateTime(@$x_fecha_contratacion,7); ?>
        </td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3" class="texto_normal"><?php echo FormatNumber(@$x_salario_mensual,0,0,0,1) ?></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3" class="texto_normal">
		<?php echo htmlentities(@$x_calle2) ?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3" class="texto_normal">
		<?php echo htmlentities(@$x_colonia2) ?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
          <?php
   		if($x_entidad_id2 > 0){
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">
          Del/Mun: </span><span class="texto_normal">
            <?php
   		if($x_delegacion_id2 > 0){
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
          </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_codigo_postal2) ?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4" class="texto_normal">
		<?php echo htmlentities(@$x_ubicacion2) ?>		</td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_antiguedad2) ?>
          (a&ntilde;os)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4" class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono2) ?>
              <span class="texto_normal">&nbsp; </span></td>
      </tr>
    </table>


	<?php } ?>



	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php if(@$x_nombre_completo_aval != ""){ ?>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Aval </td>
  </tr>
<?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><!--	<div id="aval" class="TG_hidden"> -->
		<?php if(@$x_nombre_completo_aval != ""){ ?>
        <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="texto_normal">Aval: </td>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_nombre_completo_aval)." ".htmlentities(@$x_apellido_paterno_aval)." ".htmlentities(@$x_apellido_materno_aval); ?>			</td>
          </tr>
          <tr>
            <td class="texto_normal">Parentesco:</td>
            <td class="texto_normal"><?php
  		if($x_parentesco_tipo_id_aval > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo Where parentesco_tipo_id = $x_parentesco_tipo_id_aval";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>		</td>
            <td class="texto_normal">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
<tr>
        <td class="texto_normal">RFC:</td>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_aval_rfc) ?>
        </td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">CURP:</td>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_aval_curp) ?>
        </td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
        <tr>
          <td class="texto_normal">Tipo de Negocio:</td>
          <td colspan="2"><span class="texto_normal"><?php echo htmlentities(@$x_tipo_negocio_aval) ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto_normal">Fecha de Nacimiento:</td>
          <td colspan="2"><table width="535" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="81"><div align="left"><span class="texto_normal">
                <?php  echo FormatDateTime(@$x_tit_fecha_nac_aval,7); ?>
              </span></div></td>
              <td width="150"><div align="left"><span class="texto_normal">Genero:
                <?php if (@$x_sexo_aval == "1"){ echo "M"; }else{ echo "F"; } ?>
              </span></div></td>
              <td width="304"><div align="left"><span class="texto_normal">Edo. Civil:
                <?php
		$sSqlWrk = "SELECT estado_civil_id, descripcion FROM estado_civil where estado_civil_id = $x_estado_civil_id_aval";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
              </span></div></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto_normal">Número de hijos:</td>
          <td colspan="2"><span class="texto_normal"><?php echo htmlspecialchars(@$x_numero_hijos_aval) ?>&nbsp;
          Hijos dependientes: <?php echo htmlspecialchars(@$x_numero_hijos_dep_aval) ?> </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto_normal">Nombre del Conyuge:</td>
          <td colspan="2"><span class="texto_normal"><?php echo htmlentities(@$x_nombre_conyuge) ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto_normal">Email:</td>
          <td colspan="2"><span class="texto_normal"><?php echo htmlspecialchars(@$x_email_aval) ?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto_normal">Nacionalidad:</td>
          <td colspan="2"><span class="texto_normal">
            <?php
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad where nacionalidad_id = $x_nacionalidad_id_aval";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["pais_nombre"]);
		@phpmkr_free_result($rswrk);
		?>
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
        <td class="texto_normal">Tels.:</td>
        <td colspan="2"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_telefono3) ?>
        -
        <?php echo htmlspecialchars(@$x_telefono3_sec) ?>
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tel&eacute;fono celular:</td>
        <td><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_telefono_secundario3) ?>
        </span></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
          <tr>
            <td class="texto_normal_bold">Domicilio Particular </td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td width="151"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_calle3) ?>			</td>
          </tr>
          <tr>
            <td><span class="texto_normal">Colonia: </span></td>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_colonia3) ?>			</td>
          </tr>
          <tr>
            <td><span class="texto_normal">Entidad:</span></td>
            <td width="148"><span class="texto_normal">
              <?php
	  		if($x_entidad_id3 > 0){
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		}
		?>
            </span></td>
            <td width="377"><div align="left"><span class="texto_normal">Del/Mun: </span><span class="texto_normal">
                <?php
	  		if($x_delegacion_id3 > 0){
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
            </span></div></td>
            <td width="24"><div align="left"></div></td>
          </tr>
          <tr> </tr>
          <tr>
            <td><span class="texto_normal">C.P.
              : </span></td>
            <td colspan="4"><span class="texto_normal">
			<?php echo htmlspecialchars(@$x_codigo_postal3) ?>
            </span></td>
          </tr>
          <tr>
            <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
            <td colspan="4" class="texto_normal">
			<?php echo htmlentities(@$x_ubicacion3) ?>			</td>
          </tr>
          <tr>
            <td class="texto_normal">Antiguedad en Domicilio: </td>
            <td colspan="4"><span class="texto_normal">
              <?php echo htmlspecialchars(@$x_antiguedad3) ?>
              (a&ntilde;os) </span></td>
          </tr>
          <tr>
            <td class="texto_normal"> Tipo de Vivienda:</td>
            <td colspan="4"><span class="texto_normal">
              <?php
  		if($x_vivienda_tipo_id2 > 0){
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_vivienda_tipo_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
            </span></td>
          </tr>
          <tr>
            <td ></td>
            <td colspan="4" class="texto_normal"><div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"> Propietario de la Vivienda:&nbsp;
                    <?php echo htmlentities($x_propietario2); ?>
            </div></td>
          </tr>
          <tr>
            <td class="texto_normal_bold">Domicilio del Negocio </td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td width="151"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_calle3_neg) ?>			</td>
          </tr>
          <tr>
            <td><span class="texto_normal">Colonia: </span></td>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_colonia3_neg) ?>			</td>
          </tr>
          <tr>
            <td><span class="texto_normal">Entidad:</span></td>
            <td width="148"><span class="texto_normal">
              <?php
   		if($x_entidad_id3_neg > 0){
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id3_neg";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		}
		?>
            </span></td>
            <td width="377"><div align="left"><span class="texto_normal">Del/Mun: </span><span class="texto_normal">
                <?php
   		if($x_delegacion_id3_neg > 0){
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id3_neg";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
            </span></div></td>
            <td width="24"><div align="left"></div></td>
          </tr>
          <tr> </tr>
          <tr>
            <td><span class="texto_normal">C.P.
              : </span></td>
            <td colspan="4"><span class="texto_normal">
              <?php echo htmlspecialchars(@$x_codigo_postal3_neg) ?>
            </span></td>
          </tr>
          <tr>
            <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
            <td colspan="4" class="texto_normal">
			<?php echo htmlentities(@$x_ubicacion3_neg) ?>			</td>
          </tr>
          <tr>
            <td class="texto_normal">Antiguedad en Domicilio: </td>
            <td colspan="4"><span class="texto_normal">
              <?php echo htmlspecialchars(@$x_antiguedad3_neg) ?>
              (a&ntilde;os) </span></td>
          </tr>
          <tr>
            <td class="texto_normal">Tel.</td>
            <td colspan="4" class="texto_normal">
			<?php echo htmlspecialchars(@$x_telefono3_neg) ?>			</td>
          </tr>
          <tr>
            <td class="texto_normal_bold">Ingresos</td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_normal">Ingresos Mensuales : </td>
            <td colspan="4" class="texto_normal"><?php echo FormatNumber(@$x_ingresos_mensuales,0,0,0,1) ?></td>
          </tr>
          <tr>
            <td class="texto_normal">Otros Ingresos: </td>
            <td colspan="4" class="texto_normal">
			<?php echo FormatNumber(@$x_otros_ingresos_aval,0,0,0,1); ?>
			&nbsp;Origen:
			<?php echo htmlentities(@$x_origen_ingresos_aval) ?>			</td>
          </tr>
          <tr>
            <td class="texto_normal">Ingresos Familiares: </td>
            <td colspan="4" class="texto_normal">
			<?php echo FormatNumber(@$x_ingresos_familiar_1_aval,0,0,0,1) ?>
			Parentesco:
			<?php
			if($x_parentesco_tipo_id_ing_1_aval > 0 ){
			$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo` where parentesco_tipo_id = $x_parentesco_tipo_id_ing_1_aval";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo htmlentities($datawrk["descripcion"]);
			@phpmkr_free_result($rswrk);
			}
			?>			</td>
          </tr>
		<tr>
        <td class="texto_normal">Origen: </td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlentities(@$x_origen_ingresos_aval2) ?>
        </span></td>
      </tr>
          <tr>
            <td class="texto_normal">Ocupaci&oacute;n:</td>
            <td colspan="4" class="texto_normal"><span class="texto_normal"><?php echo htmlentities(@$x_ocupacion) ?>              </span></td>
          </tr>
        </table>

<?php } ?>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php if(@$x_garantia_desc != ""){ ?>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Garant&iacute;as</div></td>
  </tr>
<?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><!-- 	<div id="garantias" class="TG_hidden" > -->
		<?php if(@$x_garantia_desc != ""){ ?>
        <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="165"><span class="texto_normal">Descripci&oacute;n</span></td>
            <td width="84" class="texto_normal">&nbsp;</td>
            <td width="163" class="texto_normal">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" class="texto_normal">
			<?php echo htmlentities(@$x_garantia_desc) ?>
			</td>
          </tr>
          <tr>
            <td><span class="texto_normal">Valor</span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_normal">
			<?php echo FormatNumber(@$x_garantia_valor,0,0,0,1) ?>
			</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
	<?php } ?>
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
        <td width="122" class="texto_normal">
		<?php echo FormatNumber(@$x_ingresos_negocio,0,0,0,1) ?>		</td>
        <td width="129" class="texto_normal">Otros Ingresos: </td>
        <td width="209" class="texto_normal">
		<?php echo FormatNumber(@$x_otros_ingresos,0,0,0,1) ?>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal">Origen:</td>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_origen_ingresos) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td class="texto_normal">
		<?php echo FormatNumber(@$x_ingresos_familiar_1,0,0,0,1) ?>		</td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td class="texto_normal"><?php
   		if($x_parentesco_tipo_id_ing_1 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ing_1";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>		</td>
      </tr>
		<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3" class="texto_normal"><div align="left">
          <?php echo htmlentities(@$x_origen_ingresos2) ?>
        </div></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td class="texto_normal">
		<?php echo FormatNumber(@$x_ingresos_familiar_2,0,0,0,1) ?>		</td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td class="texto_normal"><?php
   		if($x_parentesco_tipo_id_ing_2 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ing_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>		</td>
        </tr>
		<tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3" class="texto_normal"><div align="left">
          <?php echo htmlentities(@$x_origen_ingresos3) ?>
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
    <td colspan="3" class="texto_normal"></td>
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
        <td class="texto_normal">
		<?php echo htmlentities(@$x_nombre_completo_ref_1) ?>
		</td>
        <td class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono_ref_1) ?>
		</td>
        <td class="texto_normal"><?php
		if($x_parentesco_tipo_id_ref_1 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_1";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
		</td>
      </tr>
      <tr>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_nombre_completo_ref_2) ?>
		</td>
        <td class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono_ref_2) ?>
		</td>
        <td class="texto_normal"><?php
		if($x_parentesco_tipo_id_ref_2 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
		</td>
      </tr>
      <tr>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_nombre_completo_ref_3) ?>
		</td>
        <td class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono_ref_3) ?>
		</td>
        <td class="texto_normal"><?php
		if($x_parentesco_tipo_id_ref_3 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
		</td>
      </tr>
      <tr>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_nombre_completo_ref_4) ?>
		</td>
        <td class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono_ref_4) ?>
		</td>
        <td class="texto_normal"><?php
		if($x_parentesco_tipo_id_ref_4 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_4";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
		</td>
      </tr>
      <tr>
        <td class="texto_normal">
		<?php echo htmlentities(@$x_nombre_completo_ref_5) ?>
		</td>
        <td class="texto_normal">
		<?php echo htmlspecialchars(@$x_telefono_ref_5) ?>
		</td>
        <td class="texto_normal"><?php
		if($x_parentesco_tipo_id_ref_5 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_5";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
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
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">T&eacute;rminos y condiciones </div></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="34">&nbsp;</td>
        <td width="600" class="texto_small">
        Fecha: M&eacute;xico, D.F. a
        <input name="x_fec_reg2" type="text" class="texto_small"  style=" border: solid 1px #FFFFFF" value="<?php echo FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_registro))); ?>" size="60" maxlength="60" />
		</td>
        <td width="66">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left" class="texto_small"> Por este conducto autorizo expresamente a Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R., para que por conducto de sus funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento e historia&iacute; crediticio, asi como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga, en las Sociedades de Informaci&oacute;n Crediticia que estime conveniente. As&iacute; mismo, declaro que conozco la naturaleza y alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R. har&aacute; de ta&iacute; informaci&oacute;n y de que &eacute;sta podr&aacute; realzar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que esta autorizaci&oacute;n se encuentre vigente por un periodo de 3 a&ntilde;os contados a partir de la fecha de su expedici&oacute;n y
            en todo caso durante el tiempo que mantengamos una relaci&oacute;n jur&iacute;dica. Estoy conciente y acepto que este documento quede bajo propiedad de Microfinanciera xxx, S. A. de C.V. SOFOM E.N.R. para efectos de control y cumplimiento del art. 28 de la Ley para regular a las Sociedades e informaci&oacute;n Cr&eacute;diticia. <br /><br />De acuerdo al Capítulo II, Sección Primera, Artículo 3, cláusula cuatro de la Ley para la Transparencia y Ordenamiento de los Servicios Financieros Aplicables a los Contratos de Adhesión, Publicidad, Estados de Cuenta y Comprobantes de Operación de las Sociedades Financieras de Objeto Múltiple No Reguladas; por éste medio expreso mi consentimiento que a través del personal facultado de &quot;Microfinanciera xxx SOFOM ENR&quot;, he sido enterado del Costo Anual Total del crédito que estoy interesado en celebrar. También he sido enterado de la tasa de interés moratoria que se cobrará en caso de presentar atraso(s) en alguno(s) de los vencimientos del préstamo. También de acuerdo al Capítulo IV, Sección Primera, Artículo 23 de la misma; estoy de acuerdo en consultar mi estado de cuenta a través de internet mediante la página www.financieraxxx.com
          con el usuario y contraseña que &quot;Microfinanciera xxx SOFOM ENR&quot; a través de su personal facultado me hagan saber toda vez que se firme el pagaré correspondiente al crédito que estoy interesado en pactar.</div></td>
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
            <td width="55"><div align="center"></div></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="639" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td class="texto_normal"><div align="center">CLIENTE</div></td>
        <td class="texto_normal">&nbsp;</td>
        <td class="texto_normal"><div align="center">AVAL</div></td>
      </tr>
      <tr>
        <td width="114">&nbsp;</td>
        <td width="218">&nbsp;</td>
        <td width="53" class="texto_normal">&nbsp;</td>
        <td width="254">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Firma:</td>
        <td>____________________________________</td>
        <td>&nbsp;</td>
        <td>_______________________________________</td>
      </tr>
      <tr>
        <td class="texto_normal">Nombre:</td>
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
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
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
		
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_compania_celular_id"] = $row3["compania_celular_id"];
		

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
