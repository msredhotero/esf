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
<?php include("../../../db.php");?>
<?php include("../../../phpmkrfn.php");?>


<?php
include("js/datefunc.php");

// Get key
$x_solicitud_id = @$_GET["solicitud_id"];
if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	ob_end_clean();
	echo "No se localizo la solicitud";
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
			echo "No se localizo la solicitud";
			exit();
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  CREA Technologies</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
}

td img {display: block;
}

</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<form name="solicitudedit" id="solicitudedit" action="php_solicitudeditMaquinaria.php" method="post" >
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<span class="texto_normal">
<?php if($x_win == 1){ ?>
<a href="cuentas_view.php?key=<?php echo $x_cliente_id; ?>">Regresar a los datos del Cliente</a>
<?php 
}
if($x_win == 2){
	$sSqlWrk = "
	SELECT 
		crm_caso_id
	FROM 
		crm_caso
	WHERE 
		solicitud_id = ".$x_solicitud_id."
		and crm_caso_tipo_id = 1
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_caso_id = $datawrk["crm_caso_id"];
	@phpmkr_free_result($rswrk);
?>

<a href="casos_view.php?key=<?php echo $x_crm_caso_id; ?>">Regresar a los datos del Caso</a>
<?php 
}
if($x_win == 3){
?>
<a href='javascript: window.close();'>De clic aqui para cerrar esta Ventana</a>
<?php } ?>
<?php if($x_win == 4){ ?>
<a href="avales_view.php?key=<?php echo $x_aval_id; ?>">Regresar a los datos del Aval</a>
<?php } ?>
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
        <td class="texto_normal"></td>
        <td colspan="2">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;
       
        </td>
      </tr>
      <tr>
        <td class="texto_normal">Folio:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_folio) ?>
                  <input type="hidden" name="x_folio" value="<?php echo htmlspecialchars(@$x_folio) ?>" />
        </b></div></td>
        <td class="texto_normal"><div align="right">Status:</div></td>
        <td><?php 
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
                <?php echo $sTmp; ?>
                <?php $x_solicitud_status_id = $ox_solicitud_status_id; // Restore Original Value ?>
            
                      
      
        
        </td>
      </tr>
      <tr>
        <td class="texto_normal">Cliente No:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_cliente_id) ?>
                  <input type="hidden" name="x_cliente_id" value="<?php echo htmlspecialchars(@$x_cliente_id) ?>" />
        </b></div></td>
        <td><div align="right"><span class="texto_normal">Credito No:</span></div></td>
        <td><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_credito_id) ?> </b></div></td>
      </tr>
      <tr>
        <td class="texto_normal">Promotor:</td>
        <td colspan="4"><div class="phpmaker">
           <?php  if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
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
              <?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>       
			
			
			
        </div></td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="2" class="texto_normal"><b><?php echo($x_tipo_credito_descripcion) ?></b>
		<input type="hidden" name="x_credito_tipo_id" value="<?php echo ($x_credito_tipo_id) ;?>" />
        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo $currdate; ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe solicitado: </span></td>
        <td width="111"><div align="left">
            <?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?>
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos :</span></div></td>
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Actividad Empresarial:</span></td>
        <td colspan="4"><span class="phpmaker">
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <!-- <td colspan="4" class="texto_normal">      &nbsp;
      <div id="actividad1" class="TG_visible">Espec&iacute;ficamente:</div>
      <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td> -->
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><?php //echo htmlentities($x_actividad_desc); ?>
          </td>
        </tr>
    </table></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr></table>
  
  
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
         <?php echo htmlentities($x_nombre_completo)?>
          </td>
          <td><?php echo htmlentities(@$x_apellido_paterno) ?></td>
          <td><?php echo htmlentities(@$x_apellido_materno) ?></td>
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
      <td colspan="4"><?php echo htmlentities(@$x_rfc) ?></td>
      <td width="172">CURP</td>
      <td colspan="5"><?php echo htmlentities(@$x_curp) ?></td>
    </tr>
    <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>
              &nbsp;
              </span></td>
      <td width="105">Sexo</td>
      <td width="103"><label>
        
       <?php if($x_sexo == 1){echo("Masculino");} else if($x_sexo == 2){echo("Femenino");}?>  
		
      </label></td>
      <td>Integrantes Familia</td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_integrantes_familia) ?></td>
    </tr>
    <tr>
      <td>Dependientes</td>
      <td colspan="2"><?php echo htmlspecialchars(@$x_dependientes) ?></td>
      <td colspan="2">Correo Electronico</td>
      <td colspan="6"><?php echo htmlspecialchars(@$x_correo_electronico) ?></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="10"><?php echo htmlspecialchars(@$x_esposa) ?></td>
    </tr>
    <tr>
      <td>Rol en el hogar</td>
      <td colspan="2"><?php
		if(!empty($x_rol_hogar_id)){
		$sSqlWrk = "SELECT `rol_hogar_id`, `descripcion` FROM `rol_hogar` where rol_hogar_id = $x_rol_hogar_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);		
			$datawrk = phpmkr_fetch_array($rswrk);			
				$x_rol =$datawrk["descripcion"];
	
		@phpmkr_free_result($rswrk);
		
		echo htmlentities($x_rol);
		}
		?></td>
      <td colspan="2">Fecha inicio de actividad productiva</td>
      <td colspan="6"><?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?></td>
    </tr>
    <tr>
      <td>Destino Credito</td>
      <td colspan="2"><?php
		if(!empty($x_destino_credito_id)){
		$sSqlWrk = "SELECT `destino_credito_id`, `descripcion` FROM `destino_credito` where destino_credito_id = $x_destino_credito_id  ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);		
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_detino_credito =$datawrk["descripcion"];				
		@phpmkr_free_result($rswrk);
	
		echo htmlentities($x_detino_credito);
		}
		?></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
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
     </tr>
     <td>Calle</td>
      <td colspan="4"><?php echo htmlentities($x_calle_domicilio);?></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<?php echo ($x_numero_exterior);?></td>
    <tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><?php echo htmlspecialchars(@$x_colonia_domicilio) ?></td>
      <td>C&oacute;digo Postal </td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4">
	  
	  	<?php
		$sSqlWrk = "SELECT entidad_id, nombre as descripcion FROM entidad where entidad_id = $x_entidad_domicilio";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$x_act_desc = $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		echo $x_act_desc;
?>

      </td>
      <td>Municipio/localidad</td>
      <td colspan="5"><?php $sqlD = "SELECT  descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
	  		$resd = phpmkr_query($sqlD,$conn);
			$rowD = phpmkr_fetch_array($resd);
			
			$x_delegacion = $rowD["descripcion"];
			
			?><?php echo($x_delegacion);?></td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      
      	<?php
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_tipo_vivienda";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$x_act_desc = $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		echo $x_act_desc;
?>

      </td>
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_antiguedad) ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?></td>
    </tr>
    <tr>
      <td height="24">Tel Domicilio</td>
      <td width="133"><?php echo htmlspecialchars(@$x_telefono_domicilio) ?></td>
      <td width="76">Otro Tel</td>
      <td colspan="2"><?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?></td>
      <td>Renta Mensual</td>
      <td colspan="5"><?php echo number_format(@$x_renta_mensula_domicilio, 2, '.', '') ?></td>
    </tr>
    <tr>
      <td>Celular</td>
      <td><?php echo $x_celular;?></td>
      <td>Compa&ntilde;ia</td>
      <td colspan="2"><?php
		if(!empty($x_compania_celular_id)){
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`  WHERE compania_celular_id = $x_compania_celular_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			$datawrk = phpmkr_fetch_array($rswrk);
				
				$x_compania = $datawrk["nombre"];
		
		@phpmkr_free_result($rswrk);
		
		echo htmlentities($x_compania);
		}
		?></td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td>Otro Cel</td>
      <td><?php echo $x_otro_telefono_domicilio_2;?></td>
      <td><p>Compa&ntilde;ia</p></td>
      <td colspan="2"><?php
		
		if(!empty($x_compania_celular_id_2)){
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`  WHERE compania_celular_id = $x_compania_celular_id_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			$datawrk = phpmkr_fetch_array($rswrk);
				
				$x_compania = $datawrk["nombre"];
		
		@phpmkr_free_result($rswrk);
		
		echo htmlentities($x_compania);
		}
	
		?></td>
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
      <td colspan="4"><?php
		if(!empty($x_giro_negocio_id)){
		$sSqlWrk = "SELECT `giro_negocio_id`, `descripcion` FROM `giro_negocio` where  giro_negocio_id = $x_giro_negocio_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
		$datawrk = phpmkr_fetch_array($rswrk);
				
				$x_g_neg = $datawrk["descripcion"];
		
	
		echo htmlentities($x_g_neg);
		}
		?></td>
      <td><p>Tipo Negocio</p></td>
      <td colspan="5"><?php
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
    <tr>
      <td>Atiende Titular</td>
      <td colspan="4"><?php if($x_atiende_titular == "si"){  echo "SI"; }?>
            <?php if($x_atiende_titular == "no"){  echo "NO"; }?></td>
      <td><p>No.Personas trabajando</p></td>
      <td colspan="5"><?php echo($x_personas_trabajando);?></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><?php echo htmlspecialchars(@$x_calle_negocio) ?></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><?php echo htmlspecialchars(@$x_colonia_negocio) ?></td>
      <td><p>C&oacute;digo Postal</p></td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_codigo_postal_negocio)?></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4">
	  <?php
		$sSqlWrk = "SELECT entidad_id, nombre as descripcion FROM entidad where entidad_id = $x_entidad_negocio";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$x_act_desc = $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		echo $x_act_desc;
?>
      </td>
      <td>Municipio/localidad</td>
      <td colspan="5"><?php $sqlD2 = "SELECT  descripcion FROM delegacion where delegacion_id = $x_delegacion_id2";
	  		$resd2 = phpmkr_query($sqlD2,$conn) or die(phpmkr_error()."sq	l:".$sqlD2);
			$rowD2 = phpmkr_fetch_array($resd2);
			
			$x_delegacion_2 = $rowD2["descripcion"];
			
			?><?php echo($x_delegacion_2);?></td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><?php echo htmlspecialchars(@$x_codigo_postal_negocio) ?></td>
    </tr>
    <tr>
      <td>Tipo Local</td>
      <td colspan="4">
      
      
      	<?php
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_tipo_local_negocio";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);
		$x_act_desc = $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		echo $x_act_desc;
?>
      
      </td>
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_antiguedad_negocio) ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><?php echo htmlspecialchars(@$x_tel_arrendatario_negocio) ?></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><?php echo htmlspecialchars(@$x_tel_negocio) ?></td>
      <td>Renta Mensual</td>
      <td colspan="5"><?php echo number_format(@$x_renta_mensual, 2, '.', '') ?></td>
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
      <td colspan="11"><center><?php echo @$x_solicitud_compra; ?></center></td>
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
        <td width="276"><?php echo htmlspecialchars(@$x_referencia_com_1) ?></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="36">Tel</td>
        <td width="137"><?php echo htmlspecialchars(@$x_tel_referencia_1) ?></td></tr></table></td>
      <td width="107">Parentesco</td>
      <td colspan="6">
         <?php
		$sqlP = "SELECT descripcion  FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_ref_1";
		$resP = phpmkr_query($sqlP,$conn) or die ("error en parentesco".phpmkr_error()."");
		$rowP = phpmkr_fetch_array($resP);
		$x_des_parentesco =  $rowP["descripcion"];
		phpmkr_free_result($rowP);
		echo htmlentities($x_des_parentesco);
		?> 
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">2.-</td>
        <td width="277"><?php echo htmlspecialchars(@$x_referencia_com_2) ?></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="36">Tel</td>
        <td width="137"><?php echo htmlspecialchars(@$x_tel_referencia_2) ?></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
	  $sqlP = "SELECT descripcion  FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_ref_2";
		$resP = phpmkr_query($sqlP,$conn) or die ("error en parentesco".phpmkr_error()."");
		$rowP = phpmkr_fetch_array($resP);
		$x_des_parentesco =  $rowP["descripcion"];
		phpmkr_free_result($rowP);
		echo htmlentities($x_des_parentesco);
	  
	  ?> 
           
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">3.-</td>
        <td width="276"><?php echo htmlspecialchars(@$x_referencia_com_3) ?></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="34">Tel</td>
        <td width="137"><?php echo htmlspecialchars(@$x_tel_referencia_3) ?></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
       <?php 
	   
	   $sqlP = "SELECT descripcion  FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_ref_3";
		$resP = phpmkr_query($sqlP,$conn) or die ("error en parentesco".phpmkr_error()."");
		$rowP = phpmkr_fetch_array($resP);
		$x_des_parentesco =  $rowP["descripcion"];
		phpmkr_free_result($rowP);
		echo htmlentities($x_des_parentesco);?>
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">4.-</td>
        <td width="276"><?php echo htmlspecialchars(@$x_referencia_com_4) ?></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="35">Tel</td>
        <td width="137"><?php echo htmlspecialchars(@$x_tel_referencia_4) ?></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
	  
	  	$sqlP = "SELECT descripcion  FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_ref_4";
		$resP = phpmkr_query($sqlP,$conn) or die ("error en parentesco".phpmkr_error()."");
		$rowP = phpmkr_fetch_array($resP);
		$x_des_parentesco =  $rowP["descripcion"];
		phpmkr_free_result($rowP);
		echo htmlentities($x_des_parentesco);
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
        <td colspan="4"><?php echo number_format(@$x_ing_fam_negocio, 2, '.', '') ?></td>
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
        <td colspan="4"><?php echo number_format(@$x_ing_fam_otro_th, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar 1</td>
        <td colspan="4"><?php echo number_format(@$x_ing_fam_1, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar  2</td>
        <td colspan="4"><?php echo number_format(@$x_ing_fam_2, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_ing_fam_cuales_3) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 1</td>
        <td colspan="4"><?php echo number_format(@$x_ing_fam_deuda_1, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 2</td>
        <td colspan="4"><?php echo number_format(@$x_ing_fam_deuda_2, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Total</td>
        <td colspan="4"><?php echo number_format(@$x_ing_fam_total, 2, '.', '') ?></td>
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
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_ventas, 2, '.', '') ?></td>
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
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_proveedor_1, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 2</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_proveedor_2, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 3</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_proveedor_3, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 4</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_proveedor_4, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 1</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_gasto_1, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 2</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_gasto_2, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 3</td>
        <td colspan="4"><?php echo number_format(@$x_flujos_neg_gasto_3, 2, '.', '') ?></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Negocio</td>
        <td colspan="4"><?php echo number_format(@$x_ingreso_negocio, 2, '.', '') ?></td>
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
          <td width="226"><?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?></td>
          <td colspan="4"><?php echo number_format(@$x_inv_neg_fija_valor_1, 2, '.', '') ?></td>
          <td colspan="2">&nbsp;</td>
          <td><?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?></td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_var_valor_1, 2, '.', '') ?></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?></td>
          <td colspan="4"><?php echo number_format(@$x_inv_neg_fija_valor_2, 2, '.', '') ?></td>
          <td colspan="2">&nbsp;</td>
          <td><?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?></td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_var_valor_2, 2, '.', '') ?></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?></td>
          <td colspan="4"><?php echo number_format(@$x_inv_neg_fija_valor_3, 2, '.', '') ?></td>
          <td colspan="2">&nbsp;</td>
          <td><?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?></td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_var_valor_3, 2, '.', '') ?></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?></td>
          <td colspan="4"><?php echo number_format(@$x_inv_neg_fija_valor_4, 2, '.', '') ?></td>
          <td colspan="2">&nbsp;</td>
          <td><?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?></td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_var_valor_4, 2, '.', '') ?></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226" align="right">Total: &nbsp;</td>
          <td colspan="4"><?php echo number_format(@$x_inv_neg_total_fija, 2, '.', '') ?></td>
          <td colspan="2">&nbsp;</td>
          <td align="right">Total: &nbsp;</td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_total_var, 2, '.', '') ?></td>
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
          <td align="right">ACTIVOS TOTALES: &nbsp;</td>
          <td width="171" colspan="-2"><?php echo number_format(@$x_inv_neg_activos_totales, 2, '.', '') ?></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <tr>
          <td colspan="19"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Declaraciones</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <tr>
          <td width="226"></td>
          <td colspan="4"></td>
          <td>&nbsp;</td>
          <td width="171" colspan="-2">&nbsp;</td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr><br />
          <br />
          <td  colspan="19" id="declaracione" align="justify"
          ><p align="justify">Autorizo a Microfinanciera Crece SA de CV SOFOM ENR (CRECE), para que lleve a cabo investigaciones sobre mi comportamiento e historial crediticio, as&iacute; como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga en las sociedades de informaci&oacute;n crediticia que estime convenientes. As&iacute; mismo, declaro que conozco el alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que CRECE har&aacute; de tal informaci&oacute;n y que est&aacute; podra realizar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que dicha autorizaci&oacute;n se encuentre vigentepor un per&iacute;odo de tres a&ntilde;os a partir de la fecha de su expedici&oacute;n y en todo caso, durante el tiempo en que mantegamos una relaci&oacute;n jur&iacute;dica.<br />
          </p>
            <p>Estoy consciente y acepto que &eacute;ste documento quede bajo propiedad de CRECE para efectos de control y cumplimiento del art&iacute;culo 28 de la ley para regular a las sociedades de informaci&oacute;n crediticia<br />
            </p>
            <p>De acuerdo al cap&iacute;tulo II, secci&oacute;n I, art&iacute;culo 3, clausula 4 de la ley para la transparencia y ordenamiento de los servicios financieros aplicables a los contratos de adhesi&oacute;n, publicidad, estados de cuenta y comprobantes de operaci&oacute;n de las sociedades financieras de objeto m&uacute;ltiple no reguladas; por &eacute;ste medio expreso mi consentimiento que a trav&eacute;s del personal facultado de CRECE he sido enterado del costo anual total ( CAT) del cr&eacute;dito que estoy interesado(a) en celebrar. Tambi&eacute;n he sido enterado de la tasa de inter&eacute;s moratoria que se cobrar&aacute; en caso de presentar atraso(s) en alguno(s) de los vencimientos del pr&eacute;stamo.<br />
            </p>
            <p> Tambi&eacute;n de acuerdo al cap&iacute;tulo IV, secci&oacute;n I, art&iacute;culo 23 de la ley mencionada anteriormente, estoy de acuerdo en consultar mi estado de cuenta a trav&eacute;s de medios electr&oacute;nicos; espec&iacute;ficamente mediante la p&aacute;gina www.financieracrea.com con el usuario y contrase&ntilde;a que CRECE a trav&eacute;s de su personal facultado me haga saber toda vez que se firme el  pagar&eacute; correspondiente al cr&eacute;dito &oacute; a los cr&eacute;ditos que celebre con ellos.<br />
            </p>
            <p>Manifiesto bajo protesta decir verdad, que la informaci&oacute;n que proporcion&eacute; en &eacute;ste documento esta apegada estrictamente a la realidad, y por tanto, soy responsable  de la veracidad de la misma. As&iacute; mismo, manifiesto conocer que en caso de falseo &oacute; alteraci&oacute;n de dicha informaci&oacute;n,se aplicar&aacute;n las sanciones correspondientes. </p></td>
        </tr>
        <tr>
          <td id="tablePag2" style="display:none"></td>
        </tr>
        <tr align="right">
          <td colspan="19" style="display:none"></td>
        </tr>
      </table>
  
  
  <!-- aqui termina.....................................................................................................................-->
  	
	            
             
            
     </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><div align="center">Acepto estos T&eacute;rminos y condiciones.</div><p></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="639" border="0" cellspacing="0" cellpadding="0" align="center">
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

    <td><div align="center">
      
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

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

		// Get the field contents $x_cliente_id 
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





		//TIPO DE SOLICITUD
		$sqlTC ="SELECT descripcion FROM  credito_tipo JOIN solicitud ON(solicitud.credito_tipo_id = credito_tipo.credito_tipo_id ) ";
		$sqlTC.= "WHERE solicitud.solicitud_id = $x_solicitud_id";
		
		$rsTC = phpmkr_query($sqlTC,$conn) or die("Failed to execute query: TIPO CREDITO" . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowTC = phpmkr_fetch_array($rsTC);
		
		$GLOBALS["x_tipo_credito_descripcion"] = $rowTC["descripcion"];
		
		
		//CLIENTE		
		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];		
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
		
		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];		
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];				
		$GLOBALS["x_rfc"] = $row2["rfc"];
		$GLOBALS["x_curp"] = $row2["curp"];						
		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];					
		$GLOBALS["x_esposa"] = $row2["nombre_conyuge"];
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_integrantes_familia"] = $row2["numero_hijos"];
		$GLOBALS["x_dependientes"] = $row2["numero_hijos_dep"];		
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_correo_electronico"] = $row2["email"];		
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];				
		$GLOBALS["x_empresa"] = $row2["empresa"];		
		$GLOBALS["x_puesto"] = $row2["puesto"];		
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];														
		
		
		
		
		$sSql = "select * from negocio where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];
		
		//valor de la llave cliente_id
		//$sqlClienteid = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_cliente ="


		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_tipo_vivienda"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono_domicilio"] = $row3["telefono"];		
		$GLOBALS["x_celular"] = $row3["telefono_movil"];					
		$GLOBALS["x_otro_tel_domicilio_1"] = $row3["telefono_secundario"];
		$GLOBALS["x_tel_arrendatario_domicilio"] = $row3["propietario"];
		//falta telefono secundario
		
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_compania_celular_id"] = $row3["compania_celular_id"];
		
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row3["telefono_movil_2"];
		$GLOBALS["x_compania_celular_id_2"] = $row3["compania_celular_id_2"];
		
		
		
		

		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];
			$GLOBALS["x_tipo_local_negocio"] = $row4["vivienda_tipo_id"];
			$GLOBALS["x_antiguedad_negocio"] = $row4["antiguedad"];
			$GLOBALS["x_tel_arrendatario_negocio"] = $row4["propietario"]; 
			$GLOBALS["x_renta_mensual"] = $row4["renta_mensual"]; //renta mensula esta en gasto...mas bien no esta guardada deberia estar en gastos
			$GLOBALS["x_tel_negocio"] = $row4["telefono"];
		$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
		
		
		/*$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		
//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];*/


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
			
			//notenemos aval en este tipo de solicitud...este codigo no se ejecuta
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

			//este codigo no se ejecuta	
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

			//la tabla ingreso aval no se usa en esta solicitud
			$sSql = "select * from ingreso_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row8 = phpmkr_fetch_array($rs8);
			$GLOBALS["x_ingreso_aval_id"] = $row8["ingreso_aval_id"];
			$GLOBALS["x_ingresos_mensuales"] = $row8["ingresos_negocio"];
			$GLOBALS["x_ingresos_familiar_1_aval"] = $row8["ingresos_familiar_1"];
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = $row8["parentesco_tipo_id"];
			$GLOBALS["x_ingresos_familiar_2_aval"] = $row8["ingresos_familiar_2"];
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = $row8["parentesco_tipo_id2"];
			$GLOBALS["x_otros_ingresos_aval"] = $row8["otros_ingresos"];
			$GLOBALS["x_origen_ingresos_aval"] = $row8["origen_ingresos"];		
			$GLOBALS["x_origen_ingresos_aval2"] = $row8["origen_ingresos_fam_1"];										
			$GLOBALS["x_origen_ingresos_aval3"] = $row8["origen_ingresos_fam_2"];													

			//la tabla gasto aval no se usa en este tipo de solicitud
			$sSql = "select * from gasto_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row12 = phpmkr_fetch_array($rs12);
			$GLOBALS["x_gasto_aval_id"] = $row12["gasto_aval_id"];
			$GLOBALS["x_gastos_prov1_aval"] = $row12["gastos_prov1"];
			$GLOBALS["x_gastos_prov2_aval"] = $row12["gastos_prov2"];
			$GLOBALS["x_gastos_prov3_aval"] = $row12["gastos_prov3"];
			$GLOBALS["x_otro_prov_aval"] = $row12["otro_prov"];			
			$GLOBALS["x_gastos_empleados_aval"] = $row12["gastos_empleados"];					
			$GLOBALS["x_gastos_renta_negocio_aval"] = $row12["gastos_renta_negocio"];
			$GLOBALS["x_gastos_renta_casa2"] = $row12["gastos_renta_casa"];
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = $row12["gastos_credito_hipotecario"];
			$GLOBALS["x_gastos_otros_aval"] = $row12["gastos_otros"];		


			if(!empty($GLOBALS["x_propietario2"])){
				if(!empty($GLOBALS["x_gastos_renta_casa2"])){
					$GLOBALS["x_propietario_renta2"] = $GLOBALS["x_propietario2"];
					$GLOBALS["x_propietario2"] = "";				
				}else{
					if(!empty($GLOBALS["x_gastos_credito_hipotecario_aval"])){
						$GLOBALS["x_propietario_ch2"] = $GLOBALS["x_propietario2"];
						$GLOBALS["x_propietario2"] = "";
					}
				}
			}

			
		}else{

			$GLOBALS["x_ingreso_aval_id"] = "";
			$GLOBALS["x_ingresos_mensuales"] = "";
			$GLOBALS["x_ingresos_familiar_1_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = "";
			$GLOBALS["x_ingresos_familiar_2_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = "";
			$GLOBALS["x_otros_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval2"] = "";
			$GLOBALS["x_origen_ingresos_aval3"] = "";

			$GLOBALS["x_gasto_aval_id"] = "";
			$GLOBALS["x_gastos_prov1_aval"] = "";
			$GLOBALS["x_gastos_prov2_aval"] = "";
			$GLOBALS["x_gastos_prov3_aval"] = "";
			$GLOBALS["x_otro_prov_aval"] = "";
			$GLOBALS["x_gastos_empleados_aval"] = "";
			$GLOBALS["x_gastos_renta_negocio_aval"] = "";
			$GLOBALS["x_gastos_renta_casa2"] = "";
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = "";
			$GLOBALS["x_gastos_otros_aval"] = "";



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

		//la tabla garantia no se usa ene sta solicitud,,. no hay campo garantia solo existe  solicitud dde compra
		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_id"] = $row7["garantia_id"];		
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		
		
		
		
		//seleccion de adquisicion Maquinaria

		$sSql = "SELECT * FROM adquisicionmaquinaria WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."" ;
		
		
		//$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row8["otro_telefono_domicilio_2"];
		//$GLOBALS["x_giro_negocio"] = $row8["giro_negocio"];
		$GLOBALS["x_solicitud_compra"] = $row8["solicitud_compra"];
		$GLOBALS["x_ing_fam_negocio"] = $row8["ing_fam_negocio"];
		$GLOBALS["x_ing_fam_otro_th"] = $row8["ing_fam_otro_th"];//este no estaba
		$GLOBALS["x_ing_fam_1"] = $row8["ing_fam_1"];
		$GLOBALS["x_ing_fam_2"] = $row8["ing_fam_2"];
		$GLOBALS["x_ing_fam_deuda_1"] = $row8["ing_fam_deuda_1"];   
		$GLOBALS["x_ing_fam_deuda_2"] = $row8["ing_fam_deuda_2"];
		$GLOBALS["x_ing_fam_total"] = $row8["ing_fam_total"];
		$GLOBALS["x_ing_fam_cuales_1"] = $row8["ing_fam_cuales_1"];
		$GLOBALS["x_ing_fam_cuales_2"] = $row8["ing_fam_cuales_2"];
		$GLOBALS["x_ing_fam_cuales_3"] = $row8["ing_fam_cuales_3"];
		$GLOBALS["x_ing_fam_cuales_4"] = $row8["ing_fam_cuales_4"];
		$GLOBALS["x_ing_fam_cuales_5"] = $row8["ing_fam_cuales_5"];
		$GLOBALS["x_flujos_neg_ventas"] = $row8["flujos_neg_ventas"];
		$GLOBALS["x_flujos_neg_proveedor_1"] = $row8["flujos_neg_proveedor_1"];
		$GLOBALS["x_flujos_neg_proveedor_2"] = $row8["flujos_neg_proveedor_2"];
		$GLOBALS["x_flujos_neg_proveedor_3"] = $row8["flujos_neg_proveedor_3"];
		$GLOBALS["x_flujos_neg_proveedor_4"] = $row8["flujos_neg_proveedor_4"];
		$GLOBALS["x_flujos_neg_gasto_1"] = $row8["flujos_neg_gasto_1"];
		$GLOBALS["x_flujos_neg_gasto_2"] = $row8["flujos_neg_gasto_2"];
		$GLOBALS["x_flujos_neg_gasto_3"] = $row8["flujos_neg_gasto_3"];
		$GLOBALS["x_flujos_neg_cual_1"] = $row8["flujos_neg_cual_1"];
		$GLOBALS["x_flujos_neg_cual_2"] = $row8["flujos_neg_cual_2"];
		$GLOBALS["x_flujos_neg_cual_3"] = $row8["flujos_neg_cual_3"];
		$GLOBALS["x_flujos_neg_cual_4"] = $row8["flujos_neg_cual_4"];		
		$GLOBALS["x_flujos_neg_cual_5"] = $row8["flujos_neg_cual_5"];
		$GLOBALS["x_flujos_neg_cual_6"] = $row8["flujos_neg_cual_6"];
		$GLOBALS["x_flujos_neg_cual_7"] = $row8["flujos_neg_cual_7"];
		$GLOBALS["x_ingreso_negocio"] = $row8["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row8["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row8["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row8["inv_neg_fija_conc_3"];		
		$GLOBALS["x_inv_neg_fija_conc_4"] = $row8["inv_neg_fija_conc_4"];
		$GLOBALS["x_inv_neg_fija_valor_1"] = $row8["inv_neg_fija_valor_1"];
		$GLOBALS["x_inv_neg_fija_valor_2"] = $row8["inv_neg_fija_valor_2"];		
		$GLOBALS["x_inv_neg_fija_valor_3"] = $row8["inv_neg_fija_valor_3"];
		$GLOBALS["x_inv_neg_fija_valor_4"] = $row8["inv_neg_fija_valor_4"];
		$GLOBALS["x_inv_neg_total_fija"] = $row8["inv_neg_total_fija"];
		$GLOBALS["x_inv_neg_var_conc_1"] = $row8["inv_neg_var_conc_1"];
		$GLOBALS["x_inv_neg_var_conc_2"] = $row8["inv_neg_var_conc_2"];
		$GLOBALS["x_inv_neg_var_conc_3"] = $row8["inv_neg_var_conc_3"];
		$GLOBALS["x_inv_neg_var_conc_4"] = $row8["inv_neg_var_conc_4"];
		$GLOBALS["x_inv_neg_var_valor_1"] = $row8["inv_neg_var_valor_1"];
		$GLOBALS["x_inv_neg_var_valor_2"] = $row8["inv_neg_var_valor_2"];
		$GLOBALS["x_inv_neg_var_valor_3"] = $row8["inv_neg_var_valor_3"];
		$GLOBALS["x_inv_neg_var_valor_4"] = $row8["inv_neg_var_valor_4"];
		$GLOBALS["x_inv_neg_total_var"] = $row8["inv_neg_total_var"];
		$GLOBALS["x_inv_neg_activos_totales"] = $row8["inv_neg_activos_totales"];
													


		//gasto de la renta de la casa verificar lso datos............  estos datos no se gauardaorn cuando se levanto la solicitud
		$sSql = "select * from gasto where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row12 = phpmkr_fetch_array($rs12);
		$GLOBALS["x_gasto_id"] = $row12["gasto_id"];
		$GLOBALS["x_gastos_prov1"] = $row12["gastos_prov1"];
		$GLOBALS["x_gastos_prov2"] = $row12["gastos_prov2"];
		$GLOBALS["x_gastos_prov3"] = $row12["gastos_prov3"];
		$GLOBALS["x_otro_prov"] = $row12["otro_prov"];			
		$GLOBALS["x_gastos_empleados"] = $row12["gastos_empleados"];					
		$GLOBALS["x_renta_mensual"] = $row12["gastos_renta_negocio"]; //RENTA DEL NEGOCIO
		$GLOBALS["x_renta_mensula_domicilio"] = $row12["gastos_renta_casa"]; //RENTA DEL DOMICILIO
		/*$GLOBALS["x_gastos_credito_hipotecario"] = $row12["gastos_credito_hipotecario"];
		$GLOBALS["x_gastos_otros"] = $row12["gastos_otros"];	*/	

		if(!empty($GLOBALS["x_propietario"])){
			if(!empty($GLOBALS["x_gastos_renta_casa"])){
				$GLOBALS["x_propietario_renta"] = $GLOBALS["x_propietario"];
				$GLOBALS["x_propietario"] = "";				
			}else{
				if(!empty($GLOBALS["x_gastos_credito_hipotecario"])){
					$GLOBALS["x_propietario_ch"] = $GLOBALS["x_propietario"];
					$GLOBALS["x_propietario"] = "";
				}
			}
		}





		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";
	

		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_referencia_com_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_tel_referencia_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_ref_$x_count"] = $row9["parentesco_tipo_id"];
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
	phpmkr_free_result($rs11);										

	return $bLoadData;
}
?>

<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables


function EditData($conn){
	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
	
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
		$bEditData = false; // Update Failed
	}else{
	
		$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "0";
		$fieldList["`credito_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
		$fieldList["`solicitud_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`folio`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "0";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
		$fieldList["`importe_solicitado`"] = $theValue;
		$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "0";
		$fieldList["`plazo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
		$fieldList["`forma_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;


		$theValue = ($GLOBALS["x_actividad_id"] != "") ? intval($GLOBALS["x_actividad_id"]) : "0";
		$fieldList["`actividad_id`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_actividad_desc"]) : $GLOBALS["x_actividad_desc"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`actividad_desc`"] = $theValue;


		// update
		$sSql = "UPDATE `solicitud` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;

		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			
			exit();
		}
		
		//CLIENTE
		$fieldList = NULL;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		 
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;	
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"];  
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;	
			
		$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
		$fieldList["`fecha_nac`"] = $theValue;
		
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "0";
		$fieldList["`sexo`"] = $theValue;	
		
		$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;
		
		$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
		$fieldList["`numero_hijos_dep`"] = $theValue;
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_esposa"]) : $GLOBALS["x_esposa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;
		
		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;
		
		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"] ;
		$x_result = phpmkr_query($sSql,$conn)or die("erro_".phpmkr_errro()."sql".$sSql);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		
		//DIR PARTICULAR


		if($GLOBALS["x_direccion_id"] > 0 ){

			$fieldList = NULL;
			
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
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;		
		
		// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id"];
			$x_result = phpmkr_query($sSql,$conn) or die("Error: ".phpmkr_error()."statement :".sSql);
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente par: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle_domicilio"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 1;
			
				// Field calle
				
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
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		
				
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn) or die("error  direccion:". phpmkr_error()."statement:".$sSql);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}// ELSE DIRECCION
		
		//DIR NEG

		if($GLOBALS["x_direccion_id2"] > 0 ){
	
		$fieldList = NULL;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		
	
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		
		
	
			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE cliente_id = ". $GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
			$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);
			
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle_negocio"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 2;
			
				//*------------------------------------------------------------------------------------------------------
				
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
					
				
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn) or die("Error  insert domicilio dos:".phpmkr_error()."statement".$sSql);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}
		
		
		//GARANTIAS no hay garantias en esta solicitud solo hay solicitud de compra..............


		/*if($GLOBALS["x_garantia_id"] != ""){
			
			$fieldList = NULL;		
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`descripcion`"] = $theValue;
			$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
			$fieldList["`valor`"] = $theValue;
			
			// update
			$sSql = "UPDATE `garantia` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE garantia_id = " . $GLOBALS["x_garantia_id"];
			/*$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
			
		}else{
		
			if($GLOBALS["x_garantia_desc"] != ""){
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			
				// Field descripcion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`descripcion`"] = $theValue;
			
				// Field valor
				$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
				$fieldList["`valor`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `garantia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
		
			}//FIN GARANTIAS*/
			
		//GASTOS

	if($GLOBALS["x_solicitud_id"] > 0){ 
		$fieldList = NULL;
		
		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;	

		// update
		$sSql = "UPDATE `gasto` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE gasto_id = " . $GLOBALS["x_gasto_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
	}else{
		$fieldList = NULL;
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		
		
		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
		
		
		
		// insert into database
		$sSql = "INSERT INTO `gasto` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
	
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
	}		
			
			
		
		//referencias
		
		
		//REFERENCIAS

		$sSql = " delete from referencia WHERE solicitud_id = " . $GLOBALS["x_solicitud_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$x_counter = 1;
		while($x_counter < 6){
	
			$fieldList = NULL;
			// Field cliente_id
	//		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
			$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
	
	
			if($GLOBALS["x_referencia_com_$x_counter"] != ""){
			
				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_$x_counter"]) : $GLOBALS["x_referencia_com_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_$x_counter"]) : $GLOBALS["tel_referencia_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_ref_$x_counter"] != "") ? intval($GLOBALS["x_parentesco_ref_$x_counter"]) : "NULL";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `referencia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
	
			}
	
	
			$x_counter++;
		}
		
		
		
		
			
			
			
			//ADQUISICION MAQUINARIA

	if($GLOBALS["x_cliente_id"] > 0){ 
	$fieldList = NULL;
		//no esta el campo en la tabla domicilio por esa razon se inserta en la tabla adquisicionmaquinaria
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_telefono_domicilio_2"]) : $GLOBALS["x_otro_telefono_domicilio_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_telefono_domicilio_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_solicitud_compra"]) : $GLOBALS["x_solicitud_compra"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`solicitud_compra`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]). "'" : "NULL";
		$fieldList["`ing_fam_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]). "'" : "NULL";
		$fieldList["`ing_fam_otro_th`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]). "'" : "NULL";
		$fieldList["`ing_fam_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]). "'" : "NULL";
		$fieldList["`ing_fam_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
		$fieldList["`ing_fam_total`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_4`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_5`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" .doubleval( $GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
		$fieldList["`flujos_neg_ventas`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"] ). "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]). "'" : "NULL";
		$fieldList["`flujos_neg_gasto_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_4`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_5`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_6`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_7`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]). "'" : "NULL";
		$fieldList["`ingreso_negocio`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
		$fieldList["`inv_neg_total_fija`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" .doubleval( $GLOBALS["x_inv_neg_var_valor_1"]). "'" : "NULL";
		$fieldList["`inv_neg_var_valor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
		$fieldList["`inv_neg_total_var`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
		$fieldList["`inv_neg_activos_totales`"] = $theValue;
		
		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;
		
		$sSql = "UPDATE `adquisicionmaquinaria` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."";
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
		}
	}
			
			

		
	
	}//fin else  phpmkr_num_rows($rs) == 0
	
	
	
	
	return true;
	}

function EditData2($conn)
{

	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
	
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
		$bEditData = false; // Update Failed
	}else{
	
		$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "0";
		$fieldList["`credito_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
		$fieldList["`solicitud_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`folio`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "0";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
		$fieldList["`importe_solicitado`"] = $theValue;
		$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "0";
		$fieldList["`plazo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
		$fieldList["`forma_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;


		$theValue = ($GLOBALS["x_actividad_id"] != "") ? intval($GLOBALS["x_actividad_id"]) : "0";
		$fieldList["`actividad_id`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_actividad_desc"]) : $GLOBALS["x_actividad_desc"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`actividad_desc`"] = $theValue;


		// update
		$sSql = "UPDATE `solicitud` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;

		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			
			exit();
		}
		



//CLIENTE

		$fieldList = NULL;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		 
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;	
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"];  
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;	
			
		$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
		$fieldList["`fecha_nac`"] = $theValue;
		
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "0";
		$fieldList["`sexo`"] = $theValue;	
		
		$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;
		
		$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
		$fieldList["`numero_hijos_dep`"] = $theValue;
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_esposa"]) : $GLOBALS["x_esposa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;
		
		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;
		
		////////////////////////////////////////------------------------------------------------------------------------------
		/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;

		


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tit_rfc"]) : $GLOBALS["x_tit_rfc"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tit_curp"]) : $GLOBALS["x_tit_curp"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tipo_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "0";
		$fieldList["`edad`"] = $theValue;

		$theValue = ($GLOBALS["x_tit_fecha_nac"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac"]) . "'" : "Null";
		$fieldList["`fecha_nac`"] = $theValue;
		
		
		$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "0";
		$fieldList["`estado_civil_id`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "0";
		$fieldList["`numero_hijos`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos_dep"] != "") ? intval($GLOBALS["x_numero_hijos_dep"]) : "0";
		$fieldList["`numero_hijos_dep`"] = $theValue;		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;

		$theValue = ($GLOBALS["x_nacionalidad_id"] != "") ? intval($GLOBALS["x_nacionalidad_id"]) : "0";
		$fieldList["`nacionalidad_id`"] = $theValue;


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_empresa"]) : $GLOBALS["x_empresa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`empresa`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_piesto"]) : $GLOBALS["x_puesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`puesto`"] = $theValue;
	
		// Field fecha_registro
		$theValue = ($GLOBALS["x_fecha_contratacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_contratacion"]) . "'" : "Null";
		$fieldList["`fecha_contratacion`"] = $theValue;
	
		$theValue = ($GLOBALS["x_salario_mensual"] != "") ? " '" . doubleval($GLOBALS["x_salario_mensual"]) . "'" : "NULL";
		$fieldList["`salario_mensual`"] = $theValue;

		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue; */

		// update
		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"] ;
		$x_result = phpmkr_query($sSql,$conn)or die("erro_".phpmkr_errro()."sql".$sSql);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}


//DIR PAR


		if($GLOBALS["x_direccion_id"] > 0 ){

			$fieldList = NULL;
			
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
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		
		
			
			
			
			
			
			//----------------------------------------------------------------------------------------------------------------
			/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_telefono_domicilio_2"]) : $GLOBALS["x_otro_telefono_domicilio_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_telefono_domicilio_2`"] = $theValue;*/
			
			//-----------------------------------------------------------------------------------------------------------------	
			/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
			$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
			$fieldList["`delegacion_id`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`propietario`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
			$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "0";
			$fieldList["`antiguedad`"] = $theValue;
			$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "0";
			$fieldList["`vivienda_tipo_id`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`otro_tipo_vivienda`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;


			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sec"]) : $GLOBALS["x_telefono_sec"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_movil`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_secundario`"] = $theValue; */
	
			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id"];
			$x_result = phpmkr_query($sSql,$conn);
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente par: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 1;
			
				// Field calle
				
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad_domicilio`"] = $theValue;
		
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
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? " '" . $GLOBALS["x_renta_mensula_domicilio"] . "'" : "NULL";
		$fieldList["`renta_mensula_domicilio`"] = $theValue;
				
				
				
				
				/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;

				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field propietario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;

				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sec"]) : $GLOBALS["x_telefono_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;

				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue; */
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}

//DIR NEG

		if($GLOBALS["x_direccion_id2"] > 0 ){
	
		$fieldList = NULL;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad_domicilio`"] = $theValue;
		
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
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? " '" . $GLOBALS["x_renta_mensula_domicilio"] . "'" : "NULL";
		$fieldList["`renta_mensula_domicilio`"] = $theValue;
			
			
			//--------------------------------------------------------------------------------------------------------------------------
			/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle2"]) : $GLOBALS["x_calle2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia2"]) : $GLOBALS["x_colonia2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
			$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
			$fieldList["`delegacion_id`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal2"]) : $GLOBALS["x_codigo_postal2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
			$theValue = ($GLOBALS["x_antiguedad2"] != "") ? intval($GLOBALS["x_antiguedad2"]) : "0";
			$fieldList["`antiguedad`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono2"]) : $GLOBALS["x_telefono2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_secundario`"] = $theValue; */
	
			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id2"];
			$x_result = phpmkr_query($sSql,$conn);
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle2"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 2;
			
				//*------------------------------------------------------------------------------------------------------
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`entidad_domicilio`"] = $theValue;
		
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
		
		
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`antiguedad`"] = $theValue;
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`propietario`"] = $theValue;
			$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? " '" . $GLOBALS["x_renta_mensula_domicilio"] . "'" : "NULL";
			$fieldList["`renta_mensula_domicilio`"] = $theValue;
					
				// Field calle
				/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle2"]) : $GLOBALS["x_calle2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia2"]) : $GLOBALS["x_colonia2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;

				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal2"]) : $GLOBALS["x_codigo_postal2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad2"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono2"]) : $GLOBALS["x_telefono2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario2"]) : $GLOBALS["x_telefono_secundario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;*/
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}
//AVAL NO SE COUPA ESTA TABLA NO TENEMOS CAMPOS PARA AVAL

		if($GLOBALS["x_aval_id"] > 0){
	
			$fieldList = NULL;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo_aval"]) : $GLOBALS["x_nombre_completo_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;


			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno_aval"]) : $GLOBALS["x_apellido_paterno_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`apellido_paterno`"] = $theValue;
	
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno_aval"]) : $GLOBALS["x_apellido_materno_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`apellido_materno`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_rfc"]) : $GLOBALS["x_aval_rfc"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`rfc`"] = $theValue;
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_curp"]) : $GLOBALS["x_aval_curp"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`curp`"] = $theValue;
			
			$theValue = ($GLOBALS["x_parentesco_tipo_id_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_aval"]) : "0";
			$fieldList["`parentesco_tipo_id`"] = $theValue;


			// Field tipo_negocio
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio_aval"]) : $GLOBALS["x_tipo_negocio_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`tipo_negocio`"] = $theValue;
		
		
			// Field fecha_registro
			$theValue = ($GLOBALS["x_tit_fecha_nac_aval"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac_aval"]) . "'" : "Null";
			$fieldList["`fecha_nac`"] = $theValue;
		
		
			// Field edad
			$theValue = ($GLOBALS["x_edad_aval"] != "") ? intval($GLOBALS["x_edad_aval"]) : "0";
			$fieldList["`edad`"] = $theValue;
		
			// Field sexo
			$theValue = ($GLOBALS["x_sexo_aval"] != "") ? intval($GLOBALS["x_sexo_aval"]) : "0";
			$fieldList["`sexo`"] = $theValue;
		
			// Field estado_civil_id
			$theValue = ($GLOBALS["x_estado_civil_id_aval"] != "") ? intval($GLOBALS["x_estado_civil_id_aval"]) : "0";
			$fieldList["`estado_civil_id`"] = $theValue;
		
			// Field numero_hijos
			$theValue = ($GLOBALS["x_numero_hijos_aval"] != "") ? intval($GLOBALS["x_numero_hijos_aval"]) : "0";
			$fieldList["`numero_hijos`"] = $theValue;
		
		
			// Field numero_hijos_dep
			$theValue = ($GLOBALS["x_numero_hijos_dep_aval"] != "") ? intval($GLOBALS["x_numero_hijos_dep_aval"]) : "0";
			$fieldList["`numero_hijos_dep`"] = $theValue;
		
			// Field nombre_conyuge
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge_aval"]) : $GLOBALS["x_nombre_conyuge_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_conyuge`"] = $theValue;
		
			// Field nombre_conyuge
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email_aval"]) : $GLOBALS["x_email_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`email`"] = $theValue;
		
		
			$theValue = ($GLOBALS["x_nacionalidad_id_aval"] != "") ? intval($GLOBALS["x_nacionalidad_id_aval"]) : "0";
			$fieldList["`nacionalidad_id`"] = $theValue;



			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "NULL";
			$fieldList["`ingresos_mensuales`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ocupacion"]) : $GLOBALS["x_ocupacion"];
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ocupacion`"] = $theValue;
	
			// update
			$sSql = "UPDATE `aval` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			/*$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}


			if($GLOBALS["x_direccion_id3"] > 0) {

				$fieldList = NULL;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
				$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
	
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;
				
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
		
				// update
				$sSql = "UPDATE `direccion` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id3"];
				$x_result = phpmkr_query($sSql,$conn);
	
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL aval par: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}else{

				if($GLOBALS["x_calle3"] != ""){

					$fieldList = NULL;
					// Field cliente_id
				//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
					$fieldList["`cliente_id`"] = 0;
				
					// Field aval_id
				//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
					$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
				
					// Field promotor_id
				//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
					$fieldList["`promotor_id`"] = 0;
				
					// Field direccion_tipo_id
				//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
					$fieldList["`direccion_tipo_id`"] = 3;
				
					// Field calle
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`calle`"] = $theValue;

					// Field colonia
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`colonia`"] = $theValue;
				
					// Field delegacion_id
					$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
					$fieldList["`delegacion_id`"] = $theValue;
				
					// Field propietario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`propietario`"] = $theValue;
				
					// Field codigo_postal
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`codigo_postal`"] = $theValue;
				
					// Field ubicacion
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`ubicacion`"] = $theValue;
				
					// Field antiguedad
					$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
					$fieldList["`antiguedad`"] = $theValue;
				
					// Field vivienda_tipo_id
					$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
					$fieldList["`vivienda_tipo_id`"] = $theValue;
				
					// Field otro_tipo_vivienda
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda3"]) : $GLOBALS["x_otro_tipo_vivienda3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`otro_tipo_vivienda`"] = $theValue;
				
					// Field telefono
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono`"] = $theValue;

					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono_movil`"] = $theValue;
				
					// Field telefono_secundario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono_secundario`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `direccion` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
				
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
				}*/
			}


//DIR NEG AVAL

			if($GLOBALS["x_direccion_id4"] > 0) {
				$fieldList = NULL;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
				$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2_neg"]) : $GLOBALS["x_propietario2_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
	/*			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad3"]) : $GLOBALS["x_entidad3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`entidad`"] = $theValue;
	*/			
				/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
		
				// update
				$sSql = "UPDATE `direccion` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id4"];
				$x_result = phpmkr_query($sSql,$conn);
	
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL aval neg: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}*/

			}/*else{

				if($GLOBALS["x_calle3_neg"] != ""){

					$fieldList = NULL;
					// Field cliente_id
				//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
					$fieldList["`cliente_id`"] = 0;
				
					// Field aval_id
				//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
					$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
				
					// Field promotor_id
				//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
					$fieldList["`promotor_id`"] = 0;
				
					// Field direccion_tipo_id
				//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
					$fieldList["`direccion_tipo_id`"] = 4;
				
					// Field calle
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`calle`"] = $theValue;
				
					// Field colonia
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`colonia`"] = $theValue;
				
					// Field delegacion_id
					$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
					$fieldList["`delegacion_id`"] = $theValue;
				
					// Field propietario

					// Field codigo_postal
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`codigo_postal`"] = $theValue;
				
					// Field ubicacion
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`ubicacion`"] = $theValue;
				
					// Field antiguedad
					$theValue = ($GLOBALS["x_antiguedad3_neg"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
					$fieldList["`antiguedad`"] = $theValue;
				
					// Field vivienda_tipo_id
					$theValue = ($GLOBALS["x_vivienda_tipo_id2_neg"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
					$fieldList["`vivienda_tipo_id`"] = $theValue;
				
					// Field otro_tipo_vivienda
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario3_neg"]) : $GLOBALS["x_propietario3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`propietario`"] = $theValue;

					// Field telefono
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono`"] = $theValue;
				
					// Field telefono_secundario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono_secundario`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `direccion` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
				
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
				}
			}


			//ING AVAL
			if($GLOBALS["x_ingreso_aval_id"] > 0){			
				$fieldList = NULL;

				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_2`"] = $theValue;


				// update
				$sSql = "UPDATE `ingreso_aval` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
				$x_result = phpmkr_query($sSql,$conn);
		
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}else{

				//Ingresos AVAL
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_2`"] = $theValue;



				// insert into database
				$sSql = "INSERT INTO `ingreso_aval` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			}
			
			
			//gastos aval
			if($GLOBALS["x_gasto_aval_id"] > 0){
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
				$fieldList["`gastos_otros`"] = $theValue;
			
			
				// update
				$sSql = "UPDATE `gasto_aval` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
				$x_result = phpmkr_query($sSql,$conn);
		
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			}else{
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
				$fieldList["`gastos_otros`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `gasto_aval` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}

			}


		}else{
		

			if($GLOBALS["x_nombre_completo_aval"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			
				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo_aval"]) : $GLOBALS["x_nombre_completo_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno_aval"]) : $GLOBALS["x_apellido_paterno_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`apellido_paterno`"] = $theValue;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno_aval"]) : $GLOBALS["x_apellido_materno_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`apellido_materno`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_rfc"]) : $GLOBALS["x_aval_rfc"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`rfc`"] = $theValue;
			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_curp"]) : $GLOBALS["x_aval_curp"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`curp`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;


	
				// Field tipo_negocio
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio_aval"]) : $GLOBALS["x_tipo_negocio_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`tipo_negocio`"] = $theValue;
			
			
				// Field fecha_registro
				$theValue = ($GLOBALS["x_tit_fecha_nac_aval"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac_aval"]) . "'" : "Null";
				$fieldList["`fecha_nac`"] = $theValue;
			
			
				// Field edad
				$theValue = ($GLOBALS["x_edad_aval"] != "") ? intval($GLOBALS["x_edad_aval"]) : "0";
				$fieldList["`edad`"] = $theValue;
			
				// Field sexo
				$theValue = ($GLOBALS["x_sexo_aval"] != "") ? intval($GLOBALS["x_sexo_aval"]) : "0";
				$fieldList["`sexo`"] = $theValue;
			
				// Field estado_civil_id
				$theValue = ($GLOBALS["x_estado_civil_id_aval"] != "") ? intval($GLOBALS["x_estado_civil_id_aval"]) : "0";
				$fieldList["`estado_civil_id`"] = $theValue;
			
				// Field numero_hijos
				$theValue = ($GLOBALS["x_numero_hijos_aval"] != "") ? intval($GLOBALS["x_numero_hijos_aval"]) : "0";
				$fieldList["`numero_hijos`"] = $theValue;
			
			
				// Field numero_hijos_dep
				$theValue = ($GLOBALS["x_numero_hijos_dep_aval"] != "") ? intval($GLOBALS["x_numero_hijos_dep_aval"]) : "0";
				$fieldList["`numero_hijos_dep`"] = $theValue;
			
				// Field nombre_conyuge
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge_aval"]) : $GLOBALS["x_nombre_conyuge_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_conyuge`"] = $theValue;
			
				// Field nombre_conyuge
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email_aval"]) : $GLOBALS["x_email_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`email`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_nacionalidad_id_aval"] != "") ? intval($GLOBALS["x_nacionalidad_id_aval"]) : "0";
				$fieldList["`nacionalidad_id`"] = $theValue;


				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field ingresos_mensuales
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "NULL";
				$fieldList["`ingresos_mensuales`"] = $theValue;
			
				// Field ocupacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ocupacion"]) : $GLOBALS["x_ocupacion"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ocupacion`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `aval` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				$x_aval_id = mysql_insert_id();
				
			//DOM PART AVAL
			
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = 0;
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 3;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field propietario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
		/*
				// Field entidad
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad3"]) : $GLOBALS["x_entidad3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`entidad`"] = $theValue;
		*/	
				// Field codigo_postal
				/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad

				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda3"]) : $GLOBALS["x_otro_tipo_vivienda3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;

				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
		
		
			//DOM NEG AVAL
			
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = 0;
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 4;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad3_neg"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2_neg"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario3_neg"]) : $GLOBALS["x_propietario3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}


				//Ingresos AVAL
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_2`"] = $theValue;


				// insert into database
				$sSql = "INSERT INTO `ingreso_aval` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}

				
				//gasto aval

				
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $x_aval_id;
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
				$fieldList["`gastos_otros`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `gasto_aval` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}

			}*/

		}


//GARANTIAS no hay garantias en esta solicitud solo hay solicitud de compra..............


		if($GLOBALS["x_garantia_id"] != ""){
			
			$fieldList = NULL;		
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`descripcion`"] = $theValue;
			$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
			$fieldList["`valor`"] = $theValue;
			
			// update
			$sSql = "UPDATE `garantia` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE garantia_id = " . $GLOBALS["x_garantia_id"];
			/*$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
			
		}else{*/
		
			if($GLOBALS["x_garantia_desc"] != ""){
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			
				// Field descripcion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`descripcion`"] = $theValue;
			
				// Field valor
				$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
				$fieldList["`valor`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `garantia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
		
			}
		
		/*}*/

//ADQUISICION MAQUINARIA

	if($GLOBALS["x_cliente_id"] > 0){ 
		$fieldList = NULL;
		
		
		$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]). "'" : "NULL";
		$fieldList["`ing_fam_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]). "'" : "NULL";
		$fieldList["`ing_fam_otro_th`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]). "'" : "NULL";
		$fieldList["`ing_fam_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]). "'" : "NULL";
		$fieldList["`ing_fam_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
		$fieldList["`ing_fam_total`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_4`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_5`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" .doubleval( $GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
		$fieldList["`flujos_neg_ventas`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"] ). "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_1`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_2`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]). "'" : "NULL";
		$fieldList["`flujos_neg_gasto_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_4`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_5`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_6`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_7`"] = $theValue;
		$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]). "'" : "NULL";
		$fieldList["`ingreso_negocio`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
		$fieldList["`inv_neg_total_fija`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" .doubleval( $GLOBALS["x_inv_neg_var_valor_1"]). "'" : "NULL";
		$fieldList["`inv_neg_var_valor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
		$fieldList["`inv_neg_total_var`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
		$fieldList["`inv_neg_activos_totales`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;
		
		$sSql = "UPDATE `adquisicionmaquinaria` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= "WHERE cliente_id = ".$GLOBALS["x_cliente_id"];
		$x_result = phpmkr_query($sSql,$conn);
		
		
		
		
		
		
		
		
		
		//---------------------------------------------------------------------------------------------------------------------------

		// Field ingresos_negocio
		/*$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
		$fieldList["`ingresos_negocio`"] = $theValue;
	
		// Field ingresos_familiar_1
		$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_1`"] = $theValue;
	
		// Field parentesco_tipo_id
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1"]) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
	
		// Field ingresos_familiar_2
		$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_2`"] = $theValue;
	
		// Field parentesco_tipo_id2
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
		$fieldList["`parentesco_tipo_id2`"] = $theValue;
	
		// Field otros_ingresos
		$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
		$fieldList["`otros_ingresos`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos"] != "") ? " '" . $GLOBALS["x_origen_ingresos"] . "'" : "NULL";
		$fieldList["`origen_ingresos`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_origen_ingresos2"] != "") ? " '" . $GLOBALS["x_origen_ingresos2"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_1`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos3"] != "") ? " '" . $GLOBALS["x_origen_ingresos3"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_2`"] = $theValue;

		// update
		$sSql = "UPDATE `ingreso` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE ingreso_id = " . $GLOBALS["x_ingreso_id"];
		$x_result = phpmkr_query($sSql,$conn);*/

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
	}else{		
		
			
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		
		
		$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]). "'" : "NULL";
		$fieldList["`ing_fam_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]). "'" : "NULL";
		$fieldList["`ing_fam_otro_th`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]). "'" : "NULL";
		$fieldList["`ing_fam_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]). "'" : "NULL";
		$fieldList["`ing_fam_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
		$fieldList["`ing_fam_total`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_4`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_5`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" .doubleval( $GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
		$fieldList["`flujos_neg_ventas`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"] ). "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_1`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_2`"] = $theValue;
		$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]). "'" : "NULL";
		$fieldList["`flujos_neg_gasto_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_4`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_5`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_6`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_7`"] = $theValue;
		$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]). "'" : "NULL";
		$fieldList["`ingreso_negocio`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
		$fieldList["`inv_neg_total_fija`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" .doubleval( $GLOBALS["x_inv_neg_var_valor_1"]). "'" : "NULL";
		$fieldList["`inv_neg_var_valor_1`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_2`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_3`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_4`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
		$fieldList["`inv_neg_total_var`"] = $theValue;
		$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
		$fieldList["`inv_neg_activos_totales`"] = $theValue;
		
		
		
	
		// Field ingresos_negocio
		/*$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
		$fieldList["`ingresos_negocio`"] = $theValue;
	
		// Field ingresos_familiar_1
		$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_1`"] = $theValue;
	
		// Field parentesco_tipo_id
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1"]) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
	
		// Field ingresos_familiar_2
		$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_2`"] = $theValue;
	
		// Field parentesco_tipo_id2
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
		$fieldList["`parentesco_tipo_id2`"] = $theValue;
	
		// Field otros_ingresos
		$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
		$fieldList["`otros_ingresos`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos"] != "") ? " '" . $GLOBALS["x_origen_ingresos"] . "'" : "NULL";
		$fieldList["`origen_ingresos`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_origen_ingresos2"] != "") ? " '" . $GLOBALS["x_origen_ingresos2"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_1`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos3"] != "") ? " '" . $GLOBALS["x_origen_ingresos3"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_2`"] = $theValue;*/
	
		// insert into database
		
		
		
		$sSql = "INSERT INTO `adquisicionmaquinaria`(";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";											 
		$x_result = phpmkr_query($sSql, $conn);											 
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		
		
		
		
		
		
		
		
		/*$sSql = "INSERT INTO `ingreso` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
	
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}*/
	}	//fin else.....	
		





//gastos

	if($GLOBALS["x_solicitud_id"] > 0){ 
		$fieldList = NULL;
	
		/*$theValue = ($GLOBALS["x_gastos_prov1"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1"]) . "'" : "NULL";
		$fieldList["`gastos_prov1`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov2"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2"]) . "'" : "NULL";
		$fieldList["`gastos_prov2`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov3"] != "") ? doubleval($GLOBALS["x_gastos_prov3"]) : "NULL";
		$fieldList["`gastos_prov3`"] = $theValue;


		$theValue = ($GLOBALS["x_otro_prov"] != "") ? doubleval($GLOBALS["x_otro_prov"]) : "NULL";
		$fieldList["`otro_prov`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_empleados"] != "") ? doubleval($GLOBALS["x_gastos_empleados"]) : "NULL";
		$fieldList["`gastos_empleados`"] = $theValue; */


		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
	
	
		/*$theValue = ($GLOBALS["x_gastos_credito_hipotecario"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario"]) . "'" : "NULL";
		$fieldList["`gastos_credito_hipotecario`"] = $theValue;
	
		$theValue = ($GLOBALS["x_gastos_otros"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros"]) . "'" : "NULL";
		$fieldList["`gastos_otros`"] = $theValue; */

		// update
		$sSql = "UPDATE `gasto` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE gasto_id = " . $GLOBALS["x_gasto_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
	}else{		
		
			
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		/*$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		
		$theValue = ($GLOBALS["x_gastos_prov1"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1"]) . "'" : "NULL";
		$fieldList["`gastos_prov1`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov2"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2"]) . "'" : "NULL";
		$fieldList["`gastos_prov2`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov3"] != "") ? doubleval($GLOBALS["x_gastos_prov3"]) : "NULL";
		$fieldList["`gastos_prov3`"] = $theValue;


		$theValue = ($GLOBALS["x_otro_prov"] != "") ? doubleval($GLOBALS["x_otro_prov"]) : "NULL";
		$fieldList["`otro_prov`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_empleados"] != "") ? doubleval($GLOBALS["x_gastos_empleados"]) : "NULL";
		$fieldList["`gastos_empleados`"] = $theValue; */


		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
	
	
		/*$theValue = ($GLOBALS["x_gastos_credito_hipotecario"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario"]) . "'" : "NULL";
		$fieldList["`gastos_credito_hipotecario`"] = $theValue;
	
		$theValue = ($GLOBALS["x_gastos_otros"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros"]) . "'" : "NULL";
		$fieldList["`gastos_otros`"] = $theValue; */
		
		// insert into database
		$sSql = "INSERT INTO `gasto` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
	
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
	}		


//REFERENCIAS

		$sSql = " delete from referencia WHERE solicitud_id = " . $GLOBALS["x_solicitud_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$x_counter = 1;
		while($x_counter < 6){
	
			$fieldList = NULL;
			// Field cliente_id
	//		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
			$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
	
	
			if($GLOBALS["x_referencia_com_$x_counter"] != ""){
			
				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_$x_counter"]) : $GLOBALS["x_referencia_com_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["tel_referencia_$x_counter"]) : $GLOBALS["tel_referencia_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_ref_$x_counter"] != "") ? intval($GLOBALS["x_parentesco_ref_$x_counter"]) : "NULL";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `referencia` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
	
			}
	
	
			$x_counter++;
		}
		




	}


		phpmkr_query('commit;', $conn);	 


		$bEditData = true; // Update Successful
		
	
	
	
	
	return $bEditData;
}

?>