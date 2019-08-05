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

/*if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}*/

?>

<?php include("../../../db.php");?>
<?php include("../../../phpmkrfn.php");?>


<?php
include("../../../datefunc.php");

// Get key
$x_credito_id = @$_GET["credito_id"];
if (($x_credito_id == "") || ((is_null($x_credito_id)))) {
	ob_end_clean();
	echo "No se localizo el credito";
	//exit();
}





$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData2($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			echo "No se localizo la solicitud";
			exit();
		}
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<link href="../../../crm.css"  rel="stylesheet" type="text/css" />
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.tableContrato{
	margin: auto;
	text-align:justify;
	}
</style>
</head>

<body>
<table width="800" border="0" class="tableContrato"   cellpadding="0" cellspacing="0">
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>P A G A R E</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp; &nbsp; &nbsp;Bueno por <strong>$___________________ (-___________________  00/100 M.N.-) </strong>&nbsp; &nbsp; <br />
  POR  VALOR RECIBIDO, el Suscriptor <strong>___________________</strong>, por el presente  Pagar&eacute; promete pagar incondicionalmente a la orden del Beneficiario  MICROFINANCIERA CRECE S.A. DE C.V. SOFOM E.N.R. (&quot;CRECE&quot;), la suma  principal de <strong>$_____________________ (-_____________________ PESOS 00/100  M.N.-) </strong>, m&aacute;s intereses ordinarios a raz&oacute;n de una tasa de _______________%(-____________________________-)  semanal a partir de su fecha de suscripci&oacute;n, mismos que se pagar&aacute;n con el  principal, en fondos inmediatamente disponibles, mediante ____(-  ___________________-) amortizaciones de principal, e intereses ordinarios  iguales y consecutivas, todas ellas por la cantidad de $_________________ (-______________________________  PESOS 00/100 M.N.-) , mismas que vencer&aacute;n y ser&aacute;n pagaderas en las fechas que  se indica en la siguiente tabla, en el entendido de que si alguna de dichas  fechas no fuere un d&iacute;a h&aacute;bil, dicho pago se har&aacute; el d&iacute;a h&aacute;bil inmediato anterior  (cada una referida como una &quot;Fecha de pago&quot;). </p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Los  pagos de capital y sus accesorios deber&aacute;n de ser abonados por el suscriptor a  la tarjeta de cr&eacute;dito No. <strong>_________________________</strong> emitida por BANCO INBURSA, S.A., INSTITUCION  DE BANCA MULTIPLE, GRUPO FINANCIERO INBURSA.<br />
  Para el caso en que los  pagos de capital o intereses ordinarios no sean cubiertos en la fecha de  vencimiento, &eacute;ste pagar&eacute; causar&aacute; un Intereses moratorio correspondiente a la  cantidad de <strong>$10.00 (- DIEZ PESOS 00/100 M.N.-) </strong>diarios sobre cada uno de  los vencimientos que presenten atraso, calculado desde la fecha en la que debi&oacute;  de realizarse el pago y hasta la fecha de su pago total.</p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>CRECE  tambi&eacute;n podr&aacute; dar por vencido de manera anticipada el pago de todo el cr&eacute;dito y  tendr&aacute; derecho a que se le pague la totalidad del capital adeudado m&aacute;s los  intereses moratorios que se hubiesen generado a la fecha que se de por vencido  y hasta la fecha que se realice el pago total del adeudo.<br />
  Todas las cantidades  debidas conforme al presente Pagar&eacute; se har&aacute;n libres y sin deducci&oacute;n &oacute; retenci&oacute;n  alguna por raz&oacute;n de cualquier impuesto, derechos, recargos &oacute; cargos de  cualquier naturaleza que se establezcan, impongan o cobren las autoridades en  M&eacute;xico.</p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">El Suscriptor renuncia a toda diligencia, protesto,  presentaci&oacute;n o aviso de intenci&oacute;n, de anticipaci&oacute;n, de Fecha de pago, de no  pago o incumplimiento, o cualquier aviso de cualquier otro tipo respecto a este  Pagar&eacute;. La falta de ejercicio por parte de CRECE de cualquiera de los derechos  bajo este Pagar&eacute; en cualquier caso concreto no constituir&aacute; renuncia al mismo en  dicho caso o en cualquier caso subsecuente.</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>El  Suscriptor conviene en extender el plazo de presentaci&oacute;n del presente Pagar&eacute;  para efectos del Art&iacute;culo 128 de la Ley General de T&iacute;tulos y operaciones de  Cr&eacute;dito por un plazo de 365 (trescientos sesenta y cinco) d&iacute;as naturales  contados a partir de la &uacute;ltima fecha de pago.</p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Para la interpretaci&oacute;n, ejecuci&oacute;n  y cumplimiento de este Pagar&eacute;, as&iacute; como para cualquier otro requerimiento  judicial de pago bajo el mismo, el Suscriptor se somete expresamente a la  jurisdicci&oacute;n y competencia de los tribunales de ______________________________  , y renuncia expresamente a cualquier otro fuero que por raz&oacute;n de su domicilio  presente o futuro &uacute; otra causa pudiera corresponderle.</p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;_________________ de __________________ del  a&ntilde;o _________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>
  <div align="center">
    <table border="0" cellspacing="0" cellpadding="0" width="373">
      <tr>
        <td><p align="center">&nbsp;</p>
          <p align="center">EL    SUSCRIPTOR</p></td>
        <td><p>&nbsp;</p></td>
        <td></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p></td>
        <td width="5"><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td><p>                              _______________________________</p></td>
        <td><p>&nbsp;</p></td>
      </tr>
    </table>
  </div></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Una vez le&iacute;do y enteradas las partes de su  contenido y alcance, suscriben el presente contrato <strong> de pr&eacute;stamo de conformidad,  el d&iacute;a dos de diciembre del a&ntilde;o 2010.</strong></p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>ENTERADOS DEL CONTENIDO Y AL ALCANCE JURIDICO DE LAS  OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES CON LA CELEBRACION DE ESTE  CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN, MANIFESTANDO QUE TIENEN  CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE ADQUIEREN  SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI COMO LOS  CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR SU MOTIVO  DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O GASTOS  DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA  CIUDAD&nbsp; DE ___________________________ EL  DIA _______ DE_____________</p></td>
<td>&nbsp;</td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

</tr>
<tr>
<td>&nbsp;</td>
<td width="398" align="center"><span>_____________________________________</span></td>
<td width="398" align="center"><span>_____________________________________</span></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="398" align="center"><strong>JAVIER  ARTURO FONCERRADA SANCHEZ</strong></td>
<td width="398" align="center">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="398" align="center"><strong>REPRESENTANTE  LEGAL </strong></td>
<td width="398" align="center">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="398" align="center"><strong>MICROFINANCIERA  CRECE S.A. DE C.V. SOFOM ENR</strong></td>
<td width="398" align="center">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>



</table>
</body>
</html>