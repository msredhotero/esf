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
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("header.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
?>
<p><span class="phpmaker">Bienvenido</span></p>

<table width="500" border="0" align="left" cellpadding="0" cellspacing="0" class="phpmaker">
  <tr>
    <td colspan="5" class="ewTableHeaderThin"><div align="center" style="font-size: 14px">Informaci&oacute;n del d&iacute;a </div></td>
    </tr>
  <tr>
    <td width="71" class="ewTableAltRow"><div align="right"></div></td>
    <td width="10" class="ewTableAltRow">&nbsp;</td>
    <td width="235" class="ewTableAltRow">&nbsp;</td>
    <td width="51" class="ewTableAltRow"><div align="right"></div></td>
    <td width="45" class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) {?>  
  <tr>
    <td><div align="right" class="ewTableRow" style="font-weight: bold; font-size: 12px">Contactos </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Nuevos:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	
	<?php	
	//CONTACTOS NUEVOS
	$sSql = "SELECT count(*) as nuevos FROM contacto where contacto_status_id = 1";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_contacto_nuevo = $row["nuevos"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_contacto_nuevo,0,0,0,1);
	?>
	
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow"><span style="font-size: 12px">Atendidos</span>:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php	
	//CONTACTOS NUEVOS
	$sSql = "SELECT count(*) as nuevos FROM contacto where contacto_status_id = 2";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_contacto_nuevo = $row["nuevos"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_contacto_nuevo,0,0,0,1);
	?>	
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>

<?php } ?>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td><div align="right" class="ewTableRow" style="font-weight: bold; font-size: 12px">Solciitudes</div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Nuevas:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php	
	//SOLICITUDES NUEVAS
	$sSql = "SELECT count(*) as nuevas FROM solicitud where solicitud_status_id = 1 ";
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql .= "	AND promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitudes_nuevas = $row["nuevas"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_solicitudes_nuevas,0,0,0,1);
	?>		
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">En Investigaci&oacute;n:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//SOLICITUDES INV
	$sSql = "SELECT count(*) as investiga FROM solicitud where solicitud_status_id = 2 ";
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql .= "	AND promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
	}	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitudes_investiga = $row["investiga"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_solicitudes_investiga,0,0,0,1);
	?>			
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Aceptadas:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//SOLICITUDES ACEPTADAS
	$sSql = "SELECT count(*) as aceptadas FROM solicitud where solicitud_status_id = 3 ";
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql .= "	AND promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
	}	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitudes_aceptadas = $row["aceptadas"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_solicitudes_aceptadas,0,0,0,1);
	?>				
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow"><div align="right"></div></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Rechazadas:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//SOLICITUDES RECHAZADAS
	$sSql = "SELECT count(*) as rechazadas FROM solicitud where solicitud_status_id = 4";
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql .= "	AND promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
	}	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitudes_rechazadas = $row["rechazadas"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_solicitudes_rechazadas,0,0,0,1);
	?>					
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td><div align="right" class="ewTableRow" style="font-weight: bold; font-size: 12px">Cr&eacute;ditos </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Otrogados:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//CREDITOS OTROGADOS
	if($_SESSION["php_project_esf_status_UserRolID"] == 1) {
		$sSql = "SELECT count(*) as otrogados FROM credito where credito_status_id = 1";
	}
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql = "SELECT count(*) as otrogados FROM credito join solicitud 
		on solicitud.solicitud_id = credito.solicitud_id 
		where credito.credito_status_id = 1	AND solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_creditos_otrogados = $row["otrogados"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_creditos_otrogados,0,0,0,1);
	?>					
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Vencimientos:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//VENC
	if($_SESSION["php_project_esf_status_UserRolID"] == 1) {
		$sSql = "SELECT count(*) as num_venc FROM vencimiento where vencimiento_status_id = 1 and fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."'";
	}
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql = "SELECT count(*) as num_venc FROM vencimiento join credito
		on credito.credito_id = vencimiento.credito_id join solicitud
		on solicitud.solicitud_id = credito.solicitud_id
		where vencimiento.vencimiento_status_id = 1 and vencimiento.fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."' AND solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];	
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_vencimientos = $row["num_venc"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_vencimientos,0,0,0,1);
	?>						
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td class="ewTableAltRow"><div align="right"></div></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableAltRow" style="font-size: 12px">Cobranza:</td>
    <td class="ewTableAltRow"><div align="right" style="font-size: 12px; font-weight: bold">
	<?php
	//COBRANZA
	if($_SESSION["php_project_esf_status_UserRolID"] == 1) {
		$sSql = "SELECT sum(importe + interes + interes_moratorio) as cobranza FROM vencimiento where vencimiento_status_id = 1 and fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."'";
	}
	if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
		$sSql = "SELECT sum(vencimiento.importe + vencimiento.interes + vencimiento.interes_moratorio) as cobranza FROM vencimiento join credito
		on credito.credito_id = vencimiento.credito_id join solicitud
		on solicitud.solicitud_id = credito.solicitud_id
		where vencimiento.vencimiento_status_id = 1 and vencimiento.fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."' AND solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];	
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cobranza = $row["cobranza"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cobranza,2,0,0,1);
	?>							
	</div></td>
    <td class="ewTableAltRow"><div align="right"></div></td>
  </tr>
  <tr>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
</table>
<br>
<?php include ("footer.php") ?>
