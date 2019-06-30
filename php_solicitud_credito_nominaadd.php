<?php include ("esf/phpmkrfn.php") ?>
<?php include("esf/db.php") ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-7" />
<title>Financiera CREA</title>
<link href="css/financieracrea.css" rel="stylesheet" type="text/css" />
<link href="esf/php_project_esf.css" rel="stylesheet" type="text/css" />
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="nasp.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="esf/jscalendar/skins/aqua/theme.css" title="win2k-1" />

<script type="text/javascript" src="esf/jscalendar/calendar.js"></script>
<script type="text/javascript" src="esf/jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="esf/jscalendar/calendar-setup.js"></script>

<script src="esf/paisedohint.js"></script>
<script src="esf/lochint.js"></script>
<script language="javascript" src="esf/modulos/tipoCuenta/formatos/js/carga_telefonos.js"></script>
<!--Fancy-->
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!--Fancy-->
<script type="text/javascript">
		$(document).ready(function() {
						$("#various2").click(function(){
								$('#content_all').load('credito_individual.php');
							});	
							
						$("#various3").click(function(){
								$('#content_all').load('credito_nomina.php');
							});
							
						$("#various4").click(function(){
							$('#content_all').load('credito_solidario.php');
							
							});	
							
						$("#various5").click(function(){
							$('#content_all').load('credito_pyme.php');
							});
						$("#various_v").fancybox({
								'type': 'iframe'
							});	
						});
		
</script>


</head>

<body>
<div id="main">
    	<div id="header">
        <div style="position:absolute; left: 43px; font-size:9px; font-family:Arial, Helvetica, sans-serif; top: 82px;">Control de Audio: 
          <object data="dewplayer.swf" type="application/x-shockwave-flash" name="dewplayer" width="59" height="20" align="middle" id="dewplayer"> <param name="wmode" value="transparent" /><param name="movie" value="dewplayer.swf" /> <param name="flashvars" value="mp3=music/music.mp3&amp;autostart=1&amp;autoreplay=1&amp;showtime=1" /> </object></div>
        	<div style="width:380px; float:left; margin-top:10px;"><img src="images/ingreso.png" width="400" height="99" border="0" usemap="#Map" />
              <map name="Map" id="Map">
                <area shape="rect" coords="252,50,407,72" href="clientes/login.php" />
              </map>
       	  </div>
            <img  src="images/logo_financiera.png" width="389" height="119" style="float:right;" /><br />
       	<div id="nav">
	  <ul>
            	<li><a href="index.php">INICIO <span style="color:#FFF; font-weight:normal;">></span></a></li>
                <li><a href="quienes_somos.php">Quienes Somos <span style="color:#FFF; font-weight:normal;">></span></a></li>
                <li><a href="portafolio.php">Tipos de Credito  <span style="color:#FFF; font-weight:normal;">></span></a></li>
                <li><a href="bolsa.php">Bolsa de Trabajo <span style="color:#FFF; font-weight:normal;">></span></a></li>
                <li><a href="#">Cotizador <span style="color:#FFF; font-weight:normal;">></span></a></li>
                <li><a href="contacto.php">Contactenos <span style="color:#FFF; font-weight:normal;">></span></a></li>
          </ul>
        </div>
</div>
        <div id="banner"><img src="images/encabezado_rojo.png" width="900" height="5" /></div>
<!--  <div id="content" align="justify"><img src="images/Sin-t&iacute;tulo-1_04.png" width="541" height="125" />
  
<iframe name="contacto" src="php_contacto.php" scrolling="no" style="margin-left:0px; width:735px; height:600px" frameborder="0" allowtransparency="true"></iframe></div>-->
		<div id="content">
        <?php $conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);?>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FF0000" class="texto_normal_bold" style="color:#FFFFFF"> Solicitud de Cr&eacute;dito Nomina </td>
    </tr>
 
     <tr>
      <td colspan="11"  align="left" valign="top">
      <table width="100%">
      <tr>
      <td width="14%">Monto solicitado</td>
      <td width="21%"><input class="importe"  size="10" maxlength="10" type="text" name="x_monto_solicitado" id="x_monto_solicitado" value="<?php echo  FormatNumber(@$x_monto_solicitado,0,0,0,0);?>" /></td>
      <td width="16%">&nbsp;</td>
      <td width="49%">&nbsp;</td>
      </tr>
      <tr>
      <td width="14%">Plazo</td>
      <td width="21%">  <?php
		 
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
					$x_valor = $datawrk["descripcion"];
					$x_forma_pago_id = $datawrk["forma_pago_id"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		 if($x_solicitud_status_id < 5){
		echo $x_estado_civil_idList;
		  }else{
			  echo $x_valor;
			  ?>
              <input type="hidden" name="x_forma_pago_id" value="<?php echo $x_forma_pago_id; ?>" />
              <?php
			  
			  }
		?></td>
      <td width="16%">Numero de pagos</td>
      <td width="49%"> <?php if($x_solicitud_status_id < 5){?>        
        <span class="texto_normal"><input type="text" name="x_plazo_id" id="x_plazo_id"  value="<?php echo $x_plazo_id;?>" maxlength="3" size="15" onKeyPress="return solonumeros(this,event)" onChange="valorMax();" <?php echo $x_readonly;?> /><?php } else {  echo $x_plazo_id; ?>
		<input type="hidden" name="x_plazo_id" id="x_plazo_id"  value="<?php echo $x_plazo_id;?>"   />
		<?php }		?></td>
      </tr>
      <tr>
      <td width="14%"></td>
      <td width="21%">&nbsp;</td>
      <td width="16%">&nbsp;</td>
      <td width="49%">&nbsp;</td>
      </tr>
      </table>
      
      </td>
    </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FF0000" class="texto_normal_bold" style="color:#FFFFFF"> Datos Personales </td>
    </tr>

     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="98" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td width="33%">
          <input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlentities($x_nombre_completo)?>" maxlength="250" size="35" <?php echo $x_readonly;?>  />
          </td>
          <td width="34%"><input type="text" name="x_apellido_paterno" id="x_apellido_parterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td width="33%"><input type="text" name="x_apellido_materno" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          </tr>
        <tr>
          <td > Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
     <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" <?php echo $x_readonly;?> id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="25" onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');"  />
              &nbsp;<img src="esf/images/ew_calendar.gif" id="cx_fecha_nacimiento" onClick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%d/%m/%Y',
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span></td>
      <td width="66">Sexo</td>
      <td width="110"><label>
        <select name="x_sexo" id="x_sexo" <?php echo $x_readonly2;?> onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
       <td width="317">Lugar de nacimiento</td>
      <td colspan="5"><?php
		$x_entidad_idList = "<select name=\"x_entidad_nacimiento\" $x_readonly2 id=\"x_entidad_nacimiento\" onchange=\"generaCurpRfc(this,'txtHintcurp', 'txtHintrfc')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_nacimiento_id`, `descripcion`, cve_entidad FROM `entidad_nacimiento`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_nacimiento_id"] == @$x_entidad_nacimiento ) {
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
      <td>RFC</td>
      <td colspan="2"><div id="txtHintrfc"><input type="text" name="x_rfc" id="x_rfc" size="20" maxlength="150" value="<?php echo htmlentities(@$x_rfc) ?>" <?php echo $x_readonly;?> /></div></td>
      <td width="66">CURP</td>
      <td width="110"><div id="txtHintcurp"><input type="text" name="x_curp" id="x_curp" size="20" maxlength="150" value="<?php echo htmlentities(@$x_curp) ?>"  <?php echo $x_readonly;?>/></div></td>
      <td>Correo Electronico</td>
      <td colspan="5"><input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>"  maxlength="100" size="35" <?php echo $x_readonly;?>/></td>
    </tr>
    
    <tr>
      <td>Integrantes Familia</td>
      <td colspan="2"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>" maxlength="30" size="25"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
      <td colspan="2">Dependientes</td>
      <td colspan="6"><input type="text" name="x_dependientes" id="x_dependientes" value="<?php echo htmlspecialchars(@$x_dependientes) ?>"  maxlength="30" size="30"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_esposa" id="x_esposa"  value="<?php echo htmlspecialchars(@$x_esposa) ?>" maxlength="250" size="100" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Escolaridad</td>
      <td colspan="2"><?php
		$x_entidad_idList = "<select name=\"x_estudio_id\"  $x_readonly2 id=\"x_estudio_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estudio_id`, `descripcion` FROM `estudios`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estudio_id"] == @$x_estudio_id) {
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
      <td colspan="2">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td>Fecha inicio de actividad productiva</td>
      <td colspan="2"><input type="text" name="x_fecha_ini_act_prod" id="x_fecha_ini_act_prod" value="<?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>"   maxlength="100" size="30" <?php echo $x_readonly;?>/>
      &nbsp;<img src="esf/images/ew_calendar.gif" id="cxfecha_ini_act_prod" onclick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_ini_act_prod', 
           ifFormat : '%d/%m/%Y', 
            button : 'cxfecha_ini_act_prod' 
            }
            );" style="cursor:pointer;cursor:hand;" /></td>
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
      <td colspan="11"  align="center" valign="top" bgcolor="#FF0000" class="texto_normal_bold" style="color:#FFFFFF">Personas Pol&iacute;ticamente Expuestas</td>
    </tr>
     <tr>
      <td colspan="10">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="4">Usted o alg&uacute;n familiar  cuenta con cargo pol&iacute;tico, dentro o fuera del territorio M&eacute;xicano? </td>
      <td><select name="x_ppe" id="x_ppe" <?php echo $x_readonly2;?> >
        <option value="0">Seleccione</option>
        <option value="1" <?php  if($x_ppe == 1) {?> selected="selected" <?php }?> >Si</option>
        <option value="2" <?php  if($x_ppe == 2) {?> selected="selected" <?php }?>>No</option>
      </select></td>
      <td colspan="6"><table width="341"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="52">Relaci&oacute;n</td>
          <td width="147" ><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ppe\"  $x_readonly2 id=\"x_parentesco_ppe\" >";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ppe) {
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
      </table></td>
      </tr>
    <tr>
      <td colspan="11"  align="left"><table width="71%">
        <tr>
          <td width="26%"><input type="text" name="x_nombre_ppe" id="x_nombre_ppe"  value="<?php  echo $x_nombre_ppe; ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td width="25%"><input type="text" name="x_apellido_paterno_ppe"  value="<?php  echo $x_apellido_paterno_ppe; ?>" id="x_apellido_parterno_ppe" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td width="49%"><input type="text" name="x_apellido_materno_ppe" value="<?php  echo $x_apellido_materno_ppe; ?>" id="x_apellido_materno_ppe" maxlength="250" size="35"  <?php echo $x_readonly;?> /></td>
          </tr>
        <tr>
          <td>Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    
    <tr>
      <td colspan="11" align="center" valign="top" bgcolor="#FF0000" class="texto_normal_bold" style="color:#FFFFFF"> Domicilio </td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><input  type="hidden"  name="x_hidden_mapa_negocio" id="x_hidden_mapa_negocio" value="<?php echo $x_hidden_mapa_negocio;?>" />&nbsp;</td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlentities($x_calle_domicilio);?>"  maxlength="100" size="60" <?php echo $x_readonly;?>/></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>" maxlength="100" size="50" <?php echo $x_readonly;?>/></td>
      <td>C&oacute;digo Postal </td>
      <td colspan="5"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad_domicilio\" id=\"x_entidad_domicilio\" $x_readonly2 onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
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
		$x_delegacion_idList = "<select name=\"x_delegacion_id\"  echo $x_readonly2>";
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
$x_delegacion_idList = "<select name=\"x_localidad_id\" $x_readonly2 >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad";
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
      <input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?>/>
      </strong></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_vivienda\" $x_readonly2 id=\"x_tipo_vivienda\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
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
      <td colspan="5"><input type="text" name="x_antiguedad" id="x_antiguedad"  value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" maxlength="10" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?>" maxlength="20" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td height="24">&nbsp;</td>
      <td width="144">&nbsp;</td>
      <td width="87">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
     <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FF0000" class="texto_normal_bold" style="color:#FFFFFF">Telefonos nueva seccion </td>
    </tr>
    
    <tr>
      <td colspan="11" id="tableHead"><table width="100%" border="0">
  <tr>
    <td colspan="4"  bgcolor="#FFFFFF">Telefonos fijos <?php if($contador_telefono < 1){$contador_telefono = 1;}?> 
      <input type="hidden" name="contador_telefono"  id="contador_telefono" value="<?php echo $contador_telefono ?>"/> </td>
    <td width="58%" colspan="6"  bgcolor="#FFFFFF">Celulares<?php if($contador_celular < 1) {$contador_celular = 1;}?> <input type="hidden" name="contador_celular" id="contador_celular" value="<?php echo $contador_celular;?>" /></td>
    </tr> </table>
    <table width="100%" border="0">
  <tr>
    <td width="41%"><table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_1" type="text" value="<?php echo $x_telefono_casa_1 ?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_1">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_1 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_1 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_1" type="text" value="<?php echo $x_comentario_casa_1; ?>" size="30" maxlength="250" />--></td>
      </tr>
    </table></td>
    <td width="59%"><table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_1" type="text" size="13" maxlength="250" value="<?php echo $x_telefono_celular_1 ?>" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%">
		<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_1\" id=\"x_compania_celular_1\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_1) {
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
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_1" value="<?php echo $x_comentario_celular_1;?>"  size="30" maxlength="250" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_2">
    <?php if($x_telefono_casa_2){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_2" type="text" value="<?php echo $x_telefono_casa_2;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_2">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_2 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_2 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_2'" type="text" value="<?php echo $x_comentario_casa_2;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?>  
    </div></td>
    <td><div id="telefono_celular_2">
    <?php if(!empty($x_telefono_celular_2)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_2"  value="<?php echo $x_telefono_celular_2; ?>" type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_2\" id=\"x_compania_celular_2\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {

			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_2) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_2"  value="<?php echo $x_comentario_celular_2;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_3">
      <?php if($x_telefono_casa_3){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_3" type="text" value="<?php $x_telefono_casa_3;?>"  <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_3">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_3 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_3 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_3'" type="text" value="<?php x_comentario_casa_3;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    
    </div></td>
    <td><div id="telefono_celular_3">
    <?php if(!empty($x_telefono_celular_3)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_3"  value="<?php echo $x_telefono_celular_3; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_3\" id=\"x_compania_celular_3\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_3) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_3"  value="<?php echo $x_comentario_celular_3;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_4">
      <?php if($x_telefono_casa_4){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_4" type="text" value="<?php $x_telefono_casa_4;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_4">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_4 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_4 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_4'" type="text" value="<?php x_comentario_casa_4;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_4">
    <?php if(!empty($x_telefono_celular_4)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_4"  value="<?php echo $x_telefono_celular_4; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_4\" id=\"x_compania_celular_4\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_4) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_4"  value="<?php echo $x_comentario_celular_4;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_5">
      <?php if($x_telefono_casa_5){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_5" type="text" value="<?php $x_telefono_casa_5;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_5">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_5 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_5 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_5'" type="text" value="<?php x_comentario_casa_5;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_5">
    <?php if(!empty($x_telefono_celular_5)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_5"  value="<?php echo $x_telefono_celular_5; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_5\" id=\"x_compania_celular5\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_5) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_5"  value="<?php echo $x_comentario_celular_5;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_6">
      <?php if($x_telefono_casa_6){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_6" type="text" value="<?php $x_telefono_casa_6;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_6">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_6 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_6 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_6'" type="text" value="<?php x_comentario_casa_6;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_6">
    <?php if(!empty($x_telefono_celular_6)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_6"  value="<?php echo $x_telefono_celular_6; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_6\" id=\"x_compania_celular_6\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_6) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_6"  value="<?php echo $x_comentario_celular_6;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_7">
      <?php if($x_telefono_casa_7){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_7" type="text" value="<?php $x_telefono_casa_7;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_7">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_7 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_7 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_7'" type="text" value="<?php x_comentario_casa_7;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_7">
    <?php if(!empty($x_telefono_celular_7)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_7"  value="<?php echo $x_telefono_celular_7; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_7\" id=\"x_compania_celular_7\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_7) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_7"  value="<?php echo $x_comentario_celular_7;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_8">
      <?php if($x_telefono_casa_8){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_8" type="text" value="<?php $x_telefono_casa_8;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_8">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_8 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_8 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_8'" type="text" value="<?php x_comentario_casa_8;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_8">
    <?php if(!empty($x_telefono_celular_8)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_8"  value="<?php echo $x_telefono_celular_8; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_8\" id=\"x_compania_celular_8\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_8) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_8"  value="<?php echo $x_comentario_celular_8;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_9">
      <?php if($x_telefono_casa_9){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_9" type="text" value="<?php $x_telefono_casa_9;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_9">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_9 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_9 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_9'" type="text" value="<?php x_comentario_casa_9;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_9">
    <?php if(!empty($x_telefono_celular_9)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_9"  value="<?php echo $x_telefono_celular_9; ?>"type="text"  size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_9\" id=\"x_compania_celular_9\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_9) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_9"  value="<?php echo $x_comentario_celular_9;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_10">
      <?php if($x_telefono_casa_10){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_10" type="text" value="<?php $x_telefono_casa_10;?>"  <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_10">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_10 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_10 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_10'" type="text" value="<?php x_comentario_casa_10;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_10">
    <?php if(!empty($x_telefono_celular_10)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_10"  value="<?php echo $x_telefono_celular_10; ?>"type="text" <?php echo $x_readonly;?> size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_10\" id=\"x_compania_celular_10\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_10) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_10"  value="<?php echo $x_comentario_celular_10;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_11">
    </div></td>
    <td><div id="telefono_celular_11">
    </div></td>
  </tr>
  
  <tr>
    <td align="right"><input type="button" value="Agregar telefono casa" onClick="actualizaReferencia(1);" /></td>
    <td align="right"><input type="button" value="Agregar celular" onClick="actualizaReferencia(2);" /></td>
  </tr>
</table>

</td>
    </tr>   
    
    
    
    
    
    
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
    </tr>
  
  <tr>
      <td colspan="11">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#ff0000" class="texto_normal_bold" style="color:#FFF">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="250"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_com_1" id="x_referencia_com_1" value="<?php echo htmlspecialchars(@$x_referencia_com_1) ?>"  maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1"  value="<?php echo htmlspecialchars(@$x_tel_referencia_1) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td width="80">Parentesco</td>
      <td colspan="6">
         <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_1\" $x_readonly2 id=\"x_parentesco_ref_1\" >";
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
      <td colspan="2"><table width="250"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_com_2" id="x_referencia_com_2"  value="<?php echo htmlspecialchars(@$x_referencia_com_2) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2"  value="<?php echo htmlspecialchars(@$x_tel_referencia_2) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_2\" id=\"x_parentesco_ref_2\"  $x_readonly2 >";
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
      <td colspan="2"><table width="250"><tr>
        <td width="48">3.-</td>
      <td width="276"><input type="text" name="x_referencia_com_3" id="x_referencia_com_3"  value="<?php echo htmlspecialchars(@$x_referencia_com_3) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="19">Tel</td>
        <td width="152"><input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3"  value="<?php echo htmlspecialchars(@$x_tel_referencia_3) ?>"  maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_3\" id=\"x_parentesco_ref_3\"  $x_readonly2 >";
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
      <td width="187">&nbsp;</td>
      <td width="72">&nbsp;</td>
      <td width="169">&nbsp;</td>
      <td width="61">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="24">&nbsp;</td>
      <td width="68">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="27">&nbsp;</td>
    </tr>
    
</table></td>
  </tr>
  <?php  if ($x_fecha_registro > "2012-07-02") {
	  // si la fecha de registro es mayor al 28/06/2012 entonces las solcitudes ya se gaurdaron con los nuevos campos de ingresos
	  // y al editar se debe mostrar los campos
	  
	  ?>
  <tr>
    <td colspan="11">&nbsp;</td></tr><?php } ?>
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr>
  
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
  </table>

          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
  </div>
        <div id="footer">
        <div style="padding-top:30px;"><a href="mailto:rsanchez@financieracrea.com" style="margin-left:20px;"><strong>Quejas y sugerencias de click aqui</strong></a><span style="margin-left:400px;"><strong>Todos los derechos reservados Financiera CREA, 2012</strong></span></div>
        </div>
</div>

</body>
</html>