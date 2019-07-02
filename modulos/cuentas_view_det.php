<?php session_start(); ?>
<?php ob_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../crm.css" rel="stylesheet" type="text/css" />
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php

$x_cliente_id = $_GET["key"];
$x_tab_id = $_GET["key2"];
$x_solcre_id = $_GET["key3"];
$x_tipo_id = $_GET["key4"];



if(!empty($x_cliente_id)){
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	LoadData($conn);
}else{
	echo "No se ha espcificadoel Numero de Cliente";
	exit();
}

if(empty($x_tab_id)){
	$x_tab_id = 1;
}

?>
<br />
<br />
<br />
<br />

<?php if($x_tab_id == 1){ ?>
<table align='center' width='700' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td width='165' class="txt_negro_medio">Titular:</td>
        <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_nombre_completo." ".$x_apellido_paterno." ".$x_apellido_materno) ?></span></td>
      </tr>
	  <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
	  <tr>
	    <td class="txt_negro_medio" >RFC:</td>
	    <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_tit_rfc) ?></span></td>
	    </tr>
    <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
    <tr>
	  <td class="txt_negro_medio" >CURP:</td>
	  <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_tit_curp) ?></span></td>
	  </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Tipo de Negocio: </td>
        <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_tipo_negocio) ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Fecha de Nacimiento:</td>
        <td ><table width='535' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='81'><div align='left'>
              <span class="txt_datos_azul"><?php  echo FormatDateTime(@$x_tit_fecha_nac,7); ?></span>
            </div></td>
            <td width='113'><div align='left' class="txt_negro_medio">Genero:&nbsp;&nbsp;<span class="txt_datos_azul"><?php if (@$x_sexo == "1"){ echo "M"; }else{ echo "F"; } ?></span></div></td>
            <td width='341'><div align='left' class="txt_negro_medio">Edo. Civil:&nbsp;&nbsp;<span class="txt_datos_azul">    
                <?php
		$sSqlWrk = "SELECT estado_civil_id, descripcion FROM estado_civil where estado_civil_id = $x_estado_civil_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span>
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td class="txt_negro_medio" >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">No. de hijos
          : </td>
        <td class="txt_negro_medio" ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_numero_hijos) ?></span>&nbsp;Hijos dependientes:&nbsp;<span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_numero_hijos_dep) ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Nombre del Conyuge:</td>
        <td width='535'  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_nombre_conyuge) ?></span>
		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Email:</td>
        <td ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_email) ?></span>

		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Nacionalidad:</td>
        <td  ><span class="txt_datos_azul">
          <?php
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad where nacionalidad_id = $x_nacionalidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["pais_nombre"]);
		@phpmkr_free_result($rswrk);
		?>
        </span>
		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  ></td>
      </tr>
    </table>
<?php } ?>    

<?php if($x_tab_id == 2){ ?>
<table align='center' width='700' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td width='165' class="txt_negro_medio">Calle no. Ext e Int. :</td>
        <td width="535"  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_calle) ?></span></td>
      </tr>
	  <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
	  <tr>
	    <td class="txt_negro_medio" >Colonia:</td>
	    <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_colonia) ?></span></td>
  </tr>
    <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td >&nbsp;</td>
  </tr>
    <tr>
	  <td class="txt_negro_medio" >Entidad:</td>
	  <td ><span class="txt_datos_azul">
	    <?php
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		?>
       </span>
       <span class="txt_negro_medio">
	  &nbsp;&nbsp;&nbsp;Del/Mun:
      </span>
      <span class="txt_datos_azul">
	  <?php
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span>
	  </td>
  </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">C.P. </td>
        <td  ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_codigo_postal) ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Referencia de Ubicaci&oacute;n:</td>
        <td ><span class="txt_datos_azul"><?php echo htmlentities(@$x_ubicacion) ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Antiguedad en Domicilio:</td>
        <td ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_antiguedad) ?></span> <span class="txt_negro_medio">(a&ntilde;os)
		
        </span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Tipo de Vivienda: </td>
        <td ><span class="txt_datos_azul">
          <?php
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_vivienda_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span>
		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >
		<div class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"><span class="txt_negro_medio">Propietario de la Vivienda:</span>&nbsp;                 
		<span class="txt_datos_azul">
		<?php echo htmlentities($x_propietario); ?>
        </span>
        </div>
        </td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Tels. Particular:</td>
        <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_telefono) ?> &nbsp;- <?php echo htmlentities(@$x_telefono_sec) ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Tel. Celular: </td>
        <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_telefono_secundario) ?></span>
		</td>
      </tr>
    </table>
<?php } ?>
<?php if($x_tab_id == 3){ ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="txt_negro_medio">Empresa: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_empresa) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Puesto: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_puesto) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Fecha Contratacion:</td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo FormatDateTime(@$x_fecha_contratacion,7); ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Salario Mensual: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo FormatNumber(@$x_salario_mensual,0,0,0,1) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td width="168" class="txt_negro_medio">Calle no. Ext e Int. : </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_calle2) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Colonia: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_colonia2) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Entidad:</td>
    <td colspan="3">
    <span class="txt_datos_azul">
      <?php
   		if($x_entidad_id2 > 0){							  						  
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		}
		?>    
      </span>
        &nbsp;&nbsp;&nbsp;
        <span class="txt_negro_medio">Del/Mun:</span>
		<span class="txt_datos_azul">
		<?php
   		if($x_delegacion_id2 > 0){							  						  
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span>
    </td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">C.P.:</td>
    <td ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_codigo_postal2) ?> </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Referencia de Ubicaci&oacute;n:</td>
    <td  ><span class="txt_datos_azul"><?php echo htmlentities(@$x_ubicacion2) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio" >Antiguedad en Domicilio: </td>
    <td ><span class="txt_datos_azul"> <?php echo htmlspecialchars(@$x_antiguedad2) ?> </span><span class="txt_negro_medio">(a&ntilde;os)</span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio" >Tel.: </td>
    <td  ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_telefono2) ?> </span></td>
  </tr>
</table>
<?php } ?>

<?php if($x_tab_id == 4){ ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="170" class="txt_negro_medio">Status del Cliente:
</td>
<td width="530" class="txt_datos_azul"><?php echo $GLOBALS["x_cliente_status"]; ?>
</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">Solicitudes:</td>
  <td class="txt_datos_azul"><?php echo $GLOBALS["x_solicitudes_links"]; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio"></td>
  <td class="txt_negro_medio">De clic sobre el numero de solicitud para ver su detalle.</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">Cr&eacute;ditos:</td>
  <td class="txt_datos_azul"><?php echo $GLOBALS["x_creditos_links"]; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_negro_medio">De clic sobre el numero de cr&eacute;dito para ver su detalle.</td>
</tr>
</table>
<?php } ?>



<?php if($x_tab_id == 5){ ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="txt_negro_medio">Solicitud No: </td>
    <td colspan="3"><span class="txt_datos_azul"><?php echo $x_solcre_id; ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Status:</td>
    <td colspan="3"><span class="txt_datos_azul"><b>
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
    </b></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Promotor:</td>
    <td colspan="3"><span class="txt_datos_azul">
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
      <?php echo htmlentities($x_promotor_id); ?>
      <?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>
    </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Tipo de Cr&eacute;dito:</td>
    <td colspan="3">
    <span class="txt_datos_azul">
	<?php
		$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo` where credito_tipo_id = $x_credito_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?> </span> </td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Fecha Solicitud</td>
    <td colspan="3"><span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_registro,7); ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Importe solicitado:</td>
    <td colspan="3"><span class="txt_datos_azul"><?php echo FormatNumber(@$x_importe_solicitado,0,0,0,1) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Plazo:</td>
    <td colspan="3"><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">Forma de pago:</td>
    <td colspan="3"><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">Actividad Empresarial:</td>
    <td colspan="3"><span class="txt_datos_azul" >
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
    <td class="txt_negro_medio">Descripcion Act.</td>
    <td colspan="3"><span class="txt_datos_azul" ><?php echo $x_actividad_desc; ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>

<?php if($x_aval_id != ""){ ?>
  <tr>
    <td class="txt_negro_medio">Aval: </td>
    <td colspan="3" class="texto_normal"><span class="txt_datos_azul"><?php echo htmlentities(@$x_nombre_completo_aval)." ".htmlentities(@$x_apellido_paterno_aval)." ".htmlentities(@$x_apellido_materno_aval); ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Parentesco:</td>
    <td class="texto_normal"><span class="txt_datos_azul"><?php
  		if($x_parentesco_tipo_id_aval > 0){				
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo Where parentesco_tipo_id = $x_parentesco_tipo_id_aval";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?></span></td>
    <td class="texto_normal">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">RFC:</td>
    <td class="texto_normal"><span class="txt_datos_azul"><?php echo htmlentities(@$x_aval_rfc) ?></span></td>
    <td class="texto_normal">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">CURP:</td>
    <td class="texto_normal"><span class="txt_datos_azul"><?php echo htmlentities(@$x_aval_curp) ?></span></td>
    <td class="texto_normal">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Tipo de Negocio:</td>
    <td colspan="2"><span class="txt_datos_azul"><?php echo htmlentities(@$x_tipo_negocio_aval) ?></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Fecha de Nacimiento:</td>
    <td colspan="2"><table width="535" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="81"><div align="left"><span class="txt_datos_azul">
          <?php  echo FormatDateTime(@$x_tit_fecha_nac_aval,7); ?>
        </span></div></td>
        <td width="150"><div align="left"><span class="txt_negro_medio">Genero:</span><span class="txt_datos_azul">
<?php if (@$x_sexo_aval == "1"){ echo "M"; }else{ echo "F"; } ?>
        </span></div></td>
        <td width="304"><div align="left"><span class="txt_negro_medio">Edo. Civil:</span><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">N&uacute;mero de hijos:</td>
    <td colspan="2"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_numero_hijos_aval) ?>&nbsp;
      </span><span class="txt_negro_medio">Hijos dependientes:</span><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_numero_hijos_dep_aval) ?></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Nombre del Conyuge:</td>
    <td colspan="2"><span class="txt_datos_azul"><?php echo htmlentities(@$x_nombre_conyuge) ?></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Email:</td>
    <td colspan="2"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_email_aval) ?></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Nacionalidad:</td>
    <td colspan="2"><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">Tels.:</td>
    <td colspan="2"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_telefono3) ?> - <?php echo htmlspecialchars(@$x_telefono3_sec) ?> </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Tel&eacute;fono celular:</td>
    <td><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_telefono_secundario3) ?> </span></td>
    <td class="texto_normal">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Domicilio Particular </td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="151" class="txt_negro_medio">Calle no. Ext e Int. : </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_calle3) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Colonia: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_colonia3) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Entidad:</td>
    <td width="148"><span class="txt_datos_azul">
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
    <td width="377"><div align="left"><span class="txt_negro_medio">Del/Mun: </span><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">C.P.:</td>
    <td colspan="4"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_codigo_postal3) ?> </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Referencia de Ubicaci&oacute;n:</td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_ubicacion3) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Antiguedad en Domicilio: </td>
    <td colspan="4"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_antiguedad3) ?> </span><span class="txt_negro_medio">(a&ntilde;os) </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio"> Tipo de Vivienda:</td>
    <td colspan="4"><span class="txt_datos_azul">
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
    <td class="txt_negro_medio" ></td>
    <td colspan="4" ><span class="txt_datos_azul"><div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>"> Propietario de la Vivienda:&nbsp; <?php echo htmlentities($x_propietario2); ?> </div></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Domicilio del Negocio </td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td width="151" class="txt_negro_medio">Calle no. Ext e Int. :</td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_calle3_neg) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Colonia: </td>
    <td colspan="3" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_colonia3_neg) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Entidad:</td>
    <td width="148"><span class="txt_datos_azul">
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
    <td width="377"><div align="left"><span class="txt_negro_medio">Del/Mun: </span><span class="txt_datos_azul">
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
    <td class="txt_negro_medio">C.P.: </td>
    <td colspan="4"><span class="txt_datos_azul"> <?php echo htmlspecialchars(@$x_codigo_postal3_neg) ?> </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Referencia de Ubicaci&oacute;n:</td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_ubicacion3_neg) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Antiguedad en Domicilio: </td>
    <td colspan="4"><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_antiguedad3_neg) ?> </span><span class="txt_negro_medio">(a&ntilde;os) </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Tel.</td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo htmlspecialchars(@$x_telefono3_neg) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Ingresos</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Ingresos Mensuales : </td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo FormatNumber(@$x_ingresos_mensuales,0,0,0,1) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Otros Ingresos: </td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo FormatNumber(@$x_otros_ingresos_aval,0,0,0,1); ?></span>&nbsp;<span class="txt_negro_medio">Origen:</span><span class="txt_datos_azul"><?php echo htmlentities(@$x_origen_ingresos_aval) ?></span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Ingresos Familiares: </td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo FormatNumber(@$x_ingresos_familiar_1_aval,0,0,0,1) ?></span>&nbsp;&nbsp;<span class="txt_negro_medio">Parentesco:</span><span class="txt_datos_azul">
	  <?php
			if($x_parentesco_tipo_id_ing_1_aval > 0 ){
			$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo` where parentesco_tipo_id = $x_parentesco_tipo_id_ing_1_aval";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo htmlentities($datawrk["descripcion"]);
			@phpmkr_free_result($rswrk);
			}
			?>
            </span>
      </td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Origen: </td>
    <td colspan="4"><span class="txt_datos_azul"><?php echo htmlentities(@$x_origen_ingresos_aval2) ?> </span></td>
  </tr>
  <tr>
    <td class="txt_negro_medio">Ocupaci&oacute;n:</td>
    <td colspan="4" ><span class="txt_datos_azul"><?php echo htmlentities(@$x_ocupacion) ?></td>
  </tr>
<?php }else{ ?>
    <tr>
        <td class="txt_datos_azul">SIN AVAL </td>
        <td class="txt_datos_azul"> </td>        
    </tr>
<?php } ?>
</table>
<?php } ?>


<?php if($x_tab_id == 6){ ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="132" class="txt_negro_medio">Credito No.
</td>
<td width="108" class="txt_datos_azul"><?php echo $x_credito_num; ?></td>
<td width="41">&nbsp;</td>
<td width="106" class="txt_negro_medio">Status</td>
<td width="313" class="txt_datos_azul">
  <?php
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;
?>
  <?php echo $x_credito_status_id; ?>
  <?php $x_credito_status_id = $ox_credito_status_id; // Restore Original Value ?>
</td>
</tr>
<tr>
  <td class="txt_negro_medio">Tarjeta Num.</td>
  <td class="txt_datos_azul"><?php echo $x_tdp; ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Otorgado</td>
  <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Vencimiento</td>
  <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Importe</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Tasa</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_tasa)) ? FormatPercent(($x_tasa / 100),2,0,0,0) : ($x_tasa / 100); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Num. pagos</td>
  <td class="txt_datos_azul"><?php echo $x_num_pagos; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Tasa Moratorios</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_tasa_moratoria)) ? FormatPercent(($x_tasa_moratoria / 100),2,0,0,0) : ($x_tasa_moratoria / 100); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Forma de pago</td>
  <td class="txt_datos_azul">
    <?php 
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>
  </td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Estado de Cliente</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">
  
<?php
	$sSql = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_solcre_id) ORDER BY vencimiento.vencimiento_num+0";  
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
  
  
?>  
  <table width="100%"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    <!-- Table header -->
    <tr class="ewTableHeader">
      <td width="2%"><span> &nbsp; </span></td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio"><span> No </span></td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>
    <?php


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_moratorios_d = 0;
$x_total_total_d = 0;

$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_moratorios_a = 0;
$x_total_total_a = 0;


while ($row = @phpmkr_fetch_array($rs)) {

		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_total_a = $x_total_total_a + $x_total;

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_total_d = $x_total_total_d + $x_total;
			
		}
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
?>
    <!-- Table body -->
    <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
      <!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
      <td></td>
      <!-- vencimiento_id -->
      <td align="right"> <span class="txt_datos_azul"><?php echo $x_vencimiento_num; ?> </span></td>
      <!-- credito_id -->
      <!-- vencimiento_status_id -->
      <td align="center">
        <span class="txt_datos_azul">
        <?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
        <?php echo $x_vencimiento_status_id; ?>        
        <?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>      
        </span></td>
      <!-- fecha_vencimiento -->
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?> </span></td>
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_pago,7); ?> </span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_saldo)) ? FormatNumber($x_saldo,2,0,0,1) : $x_saldo; ?> </span></td>
      <!-- importe -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?> </span></td>
      <!-- interes -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?> </span></td>
      <!-- interes_moratorio -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?> </span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?> </span></td>
    </tr>
    <?php
$x_saldo = $x_saldo - $x_importe;
}
?>
    <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
    <tr>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_pagos,2,0,0,1); ?></b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_interes,2,0,0,1); ?></b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?></b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_total,2,0,0,1); ?></b></span></td>
    </tr>
    
    <tr >
      <td  colspan="5" align="right" class="txt_negro_medio">SALDO DEUDOR:</td>
      <td  align="right"><span>&nbsp;</span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_pagos_d,2,0,0,1); ?></b></span></td>
      <td align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_interes_d,2,0,0,1); ?></b></span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_moratorios_d,2,0,0,1); ?></b></span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_total_d,2,0,0,1); ?></b></span></td>
    </tr>
    <tr>
      <td colspan="5" align="right" class="txt_negro_medio">TOTAL PAGADO:</td>
      <td  align="right">&nbsp;</td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_pagos_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_interes_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_moratorios_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_total_a,2,0,0,1); ?></b></td>
    </tr>
  </table></td>
  </tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>
<?php } ?>


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
	global $x_cliente_id;
	global $x_tab_id;	
	global $x_solcre_id;		

//CLIENTE
if($x_tab_id == 1){

	$sSql = "select cliente.* from cliente where cliente_id = $x_cliente_id";
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
			
}


//DIR PART
if($x_tab_id == 2){
			
	$sSql = "select * from direccion join delegacion
	on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1";
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
}

// DIR NEG
if($x_tab_id == 3){
	$sSql = "select * from direccion join delegacion
	on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
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
}


//SOL Y CRED 
if($x_tab_id == 4){

//Solicitudes y creditos
	$GLOBALS["x_solicitudes"] = "";
		$sSqlWrk = "SELECT * FROM solicitud_cliente ";
		$sSqlWrk .= " WHERE (cliente_id = $x_cliente_id) order by solicitud_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		while($rowwrk = phpmkr_fetch_array($rswrk)) {
			if($rowwrk["solicitud_id"] > 0 ){
				$GLOBALS["x_solicitudes_links"] .= "<a href=\"php_solicitudedit.php?solicitud_id=".$rowwrk["solicitud_id"]."&win=1\">" . $rowwrk["solicitud_id"] . "</a> - ";
				$GLOBALS["x_solicitudes"] .= $rowwrk["solicitud_id"] . ", ";
				$x_solnew = $rowwrk["solicitud_id"];
			}	
		}
		@phpmkr_free_result($rswrk);

		$GLOBALS["x_solicitudes_links"] = substr($GLOBALS["x_solicitudes_links"], 0, strlen($GLOBALS["x_solicitudes_links"])-3);

		$GLOBALS["x_solicitudes"] = substr($GLOBALS["x_solicitudes"], 0, strlen($GLOBALS["x_solicitudes"])-2);
		
		if($GLOBALS["x_solicitudes"] != ""){
	
			$sSqlWrk = "SELECT * FROM credito ";
			$sSqlWrk .= " WHERE (solicitud_id in(".$GLOBALS["x_solicitudes"].")) order by credito_id ";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			while($rowwrk = phpmkr_fetch_array($rswrk)) {
				
				$GLOBALS["x_creditos"] = $rowwrk["credito_id"] . ", ";

				$GLOBALS["x_creditos_links"] .= "<a href='javascript: solview($x_cliente_id,".$rowwrk["credito_id"].",2);'> " . $rowwrk["credito_num"] . "</a> - ";

			}
			@phpmkr_free_result($rswrk);
			if($GLOBALS["x_creditos"] != ""){
				$GLOBALS["x_creditos"] = substr($GLOBALS["x_creditos"], 0, strlen($GLOBALS["x_creditos"])-2);

				$GLOBALS["x_creditos_links"] = substr($GLOBALS["x_creditos_links"], 0, strlen($GLOBALS["x_creditos_links"])-3);

			}
	
	
			if($GLOBALS["x_creditos"] != ""){	
				$sSqlWrk = "SELECT count(*) as activo FROM credito ";
				$sSqlWrk .= " WHERE (credito_id in(".$GLOBALS["x_creditos"].") AND (credito.credito_status_id = 1))";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
					if($rowwrk["activo"] > 0){
						$GLOBALS["x_cliente_status"] = "ACTIVO";
					}else{
						$GLOBALS["x_cliente_status"] = "IN ACTIVO";		
					}
				}
				@phpmkr_free_result($rswrk);
			}else{
				$GLOBALS["x_cliente_status"] = "IN ACTIVO";					
			}
		}else{
			$GLOBALS["x_cliente_status"] = "IN ACTIVO";			
		}
		
		
//if($GLOBALS["x_cliente_status"] == "IN ACTIVO"){
	
	$GLOBALS["x_solicitudes_links"] .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"php_solicitudrenovacion.php?solicitud_id=$x_solnew\">
[Renovaci&oacute;n de Solicitud: $x_solnew]</a>";

/*
	$GLOBALS["x_solicitudes_links"] .= "&nbsp;&nbsp;<a href=\"php_solicitudrenovacion.php?solicitud_id=$x_solnew\">
    <input type=\"button\" name=\"x_nueva\" id=\"x_nueva\" value=\"Nueva\" onmouseover=\"javascript: this.style.cursor='pointer'\" />
    </a>";
*/

//}

		
}


//SOLICITUD
if($x_tab_id == 5){

	$sSql = "select * from solicitud where solicitud_id = ".$x_solcre_id;
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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


	$sSql = "select * from aval where solicitud_id = ".$x_solcre_id;
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
	on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3";
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
	on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 4";
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



}

//CREDITO
if($x_tab_id == 6){

	$sSql = "select * from credito where credito_id = ".$x_solcre_id;
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_credito_num"] = $row["credito_num"];		
		$GLOBALS["x_cliente_num"] = $row["cliente_num"];				
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_banco_id"] = $row["banco_id"];		
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_num_pagos"] = $row["num_pagos"];				
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];						
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

