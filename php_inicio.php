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
<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1  || $_SESSION["php_project_esf_status_UserRolID"] == 3) {?>  
<table width="500" border="0" align="left" cellpadding="0" cellspacing="0" class="phpmaker">
  <tr>
    <td colspan="5" class="ewTableHeaderThin"><div align="center" style="font-size: 12px">Informaci&oacute;n del d&iacute;a </div></td>
    </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="220"><div align="right" class="ewTableRow" style="font-weight: bold; font-size: 10px">
      <div align="left">Cobranza </div>
    </div></td>
    <td width="16">&nbsp;</td>
    <td width="31">&nbsp;</td>
    <td width="171"><div align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//COBRANZA
		$sSql = "SELECT sum(importe + interes + interes_moratorio) as cobranza FROM vencimiento where vencimiento_status_id = 1 and fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."'";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cobranza = $row["cobranza"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cobranza,2,0,0,1);
	?>
    </span></div></td>
    <td width="58"><div align="right"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"></div></td>
    <td><div align="right"></div></td>
  </tr>
  <tr>
    <td><div align="right" class="ewTableRow" style="font-weight: bold; font-size: 10px">
      <div align="left">Total de cr&eacute;ditos ACTIVOS </div>
    </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><span style="font-size: 10px; font-weight: bold"><?php
	//COBRANZA
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_status_id = 1";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?></span></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><span class="ewTableRow" style="font-weight: bold; font-size: 10px">Total de cr&eacute;ditos CANCELADOS</span></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//COBRANZA
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_status_id = 2";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?></span>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><span class="ewTableRow" style="font-weight: bold; font-size: 10px">Total de cr&eacute;ditos LIQUIDADOS</span></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//COBRANZA
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_status_id = 3";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?></span>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><span class="ewTableRow" style="font-weight: bold; font-size: 10px">Total de cr&eacute;ditos COBRANZA EXT.</span></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//COBRANZA
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_status_id = 4";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?></span>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><span class="ewTableRow" style="font-weight: bold; font-size: 10px">Total de cr&eacute;ditos INCOBRABLES</span></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//COBRANZA
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_status_id = 5";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?></span>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><span class="ewTableRow" style="font-weight: bold; font-size: 10px">Total de cr&eacute;ditos CARTERA VENCIDA</span></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><span style="font-size: 10px; font-weight: bold">
      <?php
	//CARTERA VENC
		$sSql = "SELECT count(*) as cred_act FROM credito where credito_id in (
		select credito_id from vencimiento where vencimiento_status_id = 3) and credito_status_id = 1";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cred_act = $row["cred_act"];
	phpmkr_free_result($rs);
	echo FormatNumber($x_cred_act,0,0,0,1);
	?>
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="center" valign="top" class="ewTableHeaderThin">Informaci&oacute;n por Promotor</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="left" valign="top">



	<table align="left" width="500" border="0" cellpadding="0" cellspacing="0" class="phpmaker">
    <tr>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Promotor
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Cobranza
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Act.
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Can.
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Liq.
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        C.ext
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        Inc.
        </b>
        </span>
        </td>
    	<td align="center" style="border-right: solid 1px #333"><span class="phpmaker"><b>
        C.Ven.
        </b>
        </span>
        </td>
	</tr>
        



<?php
	$sSql = "SELECT * FROM promotor order by nombre_completo desc ";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while ($row = phpmkr_fetch_array($rs)){
		$x_promotor_id = $row["promotor_id"];
		$x_nombre_completo = $row["nombre_completo"];		

		//COBRANZA
		$sSql = "SELECT sum(vencimiento.importe + vencimiento.interes + vencimiento.interes_moratorio) as cobranza FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where vencimiento.vencimiento_status_id = 1 and vencimiento.fecha_vencimiento = '".ConvertDateToMysqlFormat($currdate)."' and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cobranza = $rowwrk["cobranza"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_act FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_status_id = 1 and solicitud.promotor_id = $x_promotor_id ";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_act = $rowwrk["cred_act"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_can FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_status_id = 2 and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_can = $rowwrk["cred_can"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_liq FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_status_id = 3 and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_liq = $rowwrk["cred_liq"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_ext FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_status_id = 4 and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_ext = $rowwrk["cred_ext"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_inc FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_status_id = 5 and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_inc = $rowwrk["cred_inc"];
		phpmkr_free_result($rswrk);

		$sSql = "SELECT count(*) as cred_ven FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito_id in (
		select credito_id from vencimiento where vencimiento_status_id = 3) and credito_status_id = 1 and solicitud.promotor_id = $x_promotor_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_cred_ven = $rowwrk["cred_ven"];
		phpmkr_free_result($rswrk);

?>

    <tr>
    	<td style="border-right: solid 1px #333" align="left"><span class="phpmaker">
        <?php echo $x_nombre_completo; ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cobranza,2,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_act,0,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_can,0,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_liq,0,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_ext,0,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_inc,0,0,0,1); ?>
        </span>
        </td>
    	<td style="border-right: solid 1px #333" align="right"><span class="phpmaker">
        <?php echo FormatNumber($x_cred_ven,0,0,0,1); ?>
        </span>
        </td>
	</tr>
        


<?php
	}
	phpmkr_free_result($rs);
?>
	</table>


    
    </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php } ?>
<br>
<?php include ("footer.php") ?>
