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

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Documento sin t&iacute;tulo</title>
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
<td width="4">&nbsp;</td>
<td colspan="2" ><p>CONTRATO DE LINEA DE CREDITO REVOLVENTE CELEBRAN POR UNA  PARTE <strong>MICROFINANCIERA CRECE S.A. DE C.V.  SOFOM ENR.</strong>, A QUIEN EN LO SUCECIVO SE LE DENOMINARA <strong>“CREA”</strong> Y POR OTRA PARTE &nbsp;EL  C.__________________ Y QUE EN LO SUCESIVO SE LE DENOMINARA COMO EL <strong>“ACREDITADO”</strong> QUE EN SU CONJUNTO SE LES  DENOMINARA COMO <strong>“LAS PARTES”</strong>,  QUIENES DE CONFORMIDAD SE &nbsp;HAN  MANIFESTADO SU PLENA VOLUNTAD EN SUJETARSE AL TENOR DE LAS SIGUIENTES:</p></td>
<td width="10" >&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" align="center"><strong>DECLARACIONES</strong></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>A).-EL ACREDITADO DECLARA LO  SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.- </strong>Ser persona f&iacute;sica mayor de edad, con  nacionalidad Mexicana, con domicilio en_____________________________________ y  que cuenta con la capacidad legal para la celebraci&oacute;n del presente contrato.<br />
    <strong>2.-</strong><strong> </strong> Que es su  voluntad celebrar con <strong>CREA </strong>el presente Contrato, a efecto obtener una  l&iacute;nea de cr&eacute;dito por &nbsp;la cantidad  se&ntilde;alada en la cl&aacute;usula Primera y de garantizar el puntual pago de las  cantidades a disponer, as&iacute; como de los intereses y dem&aacute;s accesorios legales que  se llegaran a generar a favor de <strong>CREA </strong>y a su cargo. <br />
&nbsp;<strong>3.-</strong> Que presta servicios personales independientes consistentes en: &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;________________  situaci&oacute;n que acredita con los documentos que se agregan al (y forman parte  del) presente contrato como <strong>anexo1. </strong> <br />
<strong>4.- </strong> Que destinará el préstamo a que se refiere  este contrato a la adquisición de bienes de inversión, o bien para la  adquisición de las materias primas y materiales y en el pago de los jornales,  salarios y gastos directos de explotación indispensables para los fines de su  empresa. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>B).- CREA DECLARA LO  SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.-</strong> Ser una persona moral de nacionalidad  Mexicana, legalmente constituida conforme a las leyes de la Republica Mexicana  en los t&eacute;rminos de la escritura p&uacute;blica No. 122,166, de fecha 19 de abril de  2007, basada ante la fe del Notario P&uacute;blico No. 152 del Distrito Federal,&nbsp; el Lic. Cecilio Gonzales M&aacute;rquez.<br />
  <strong>2.-</strong> Que su representante legal, el C.  Javier Arturo Foncerrada S&aacute;nchez est&aacute; debidamente facultado para celebrar el  presente contrato, seg&uacute;n consta en la escritura p&uacute;blica &nbsp;No. 122,166, de fecha 19 de abril de 2007, basada  ante la fe del Notario P&uacute;blico No. 152 del Distrito Federal,&nbsp; el Lic. Cecilio Gonzales M&aacute;rquez.,  manifestando bajo protesta de decir verdad que hasta la fecha no le han sido  revocados, ni limitadas sus facultades en forma alguna.<br />
  <strong>3.-</strong> Estar constituida en el domicilio de  Av. Revoluci&oacute;n No. 1909 piso 2, colonia San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n en  M&eacute;xico Distrito Federal. <br />
  <strong>4.-</strong> Que considerando las declaraciones  del ACREDITADO es de su voluntad otorgar una l&iacute;nea de cr&eacute;dito por la cantidad  de $_____________ . <br />
  Expuestas las anteriores declaraciones, las partes que  suscriben el presente contrato manifiestan su voluntad de otorgar y sujetarse  al tenor de las siguientes clausulas.</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" align="center"><strong>CLAUSULAS</strong>&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>PRIMERA.-</strong> <strong><u>OBJETO  DEL CONTRATO</u>.-</strong> En este acto CREA otorga al ACREDITADO y el ACREDITADO  recibe, una l&iacute;nea de cr&eacute;dito &nbsp;por la  cantidad de _________________(____________________), el cual podr&aacute; hacer uso en  una o varias operaciones a petici&oacute;n del mismo y con previa autorizaci&oacute;n de  CREA, por el monto deseado, siempre y cuando no sea superior al monto total  autorizado.</p>
  <p> Las partes acuerdan expresamente que CREA podrá en cualquier  momento sin causa alguna variar,  limitar  o cancelar el uso del crédito aprobado. <br />
 Una vez otorgada la  línea de crédito las partes se obligan a cumplir conforme a los términos y  condiciones del presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEGUNDA.-</strong> el ACREDITADO se compromete a  destinar el importe del cr&eacute;dito solicitado para la actividad econ&oacute;mica  consistente en___________________________. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>TERCERA.- </strong>El ACREDITADO tendr&aacute; acceso al cr&eacute;dito  en el momento que lo requiera siempre y cuando lo haya solicitado con un d&iacute;a de  anterioridad, ya sea expresa o t&aacute;citamente&nbsp;  a CREA. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>CUARTA.-</strong> <strong><u>LUGAR  Y FORMA DE PAGO</u>.-</strong> el ACREDITADO se obliga a:</p>
  <ol>
    <li>A pagar los montos desembolsados bajo la l&iacute;nea de cr&eacute;dito, que  corresponda mediante pagos ____________, as&iacute; como los intereses y accesorios  dependiendo del monto del desembolso realizado. </li>
    <li>Pagar los montos desembolsados bajo la l&iacute;nea de cr&eacute;dito y accesorios,  deber&aacute;n realizarse a la tarjeta de cr&eacute;dito No. ______________________ emitida  por Banco inbursa.</li>
    <li>Solventar el cr&eacute;dito mediante un pagare emitido por CREA en forma in  completa, por lo que en caso de incumplimiento del ACREDITADO,&nbsp; CREA quedara facultado para completar el  mismo por el monto que resulte de las obligaciones vencidas y no vencidas,  incluido los intereses::::::&nbsp; producto  del financiamiento, as&iacute; como intereses moratorios correspondientes a  ___________________, _______________.. </li>
  </ol></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>QUINTA.-</strong> <strong><u>INTERES  ORDINARIOS</u>.- </strong> las partes acuerdan que la cantidad de la clausula primera se  le aplicara por concepto de interés ordinario una tasa del _______(________) lo  cual es equivalente a una tasa _____________(________) anual.</p>
  <p>Los intereses que se generen con motivo del presente  contrato, deber&aacute;n ser pagados por el ACREDITADO dentro del dia h&aacute;bil inmediato  siguiente a la semana transcurrida, y por la que se genero dicho inter&eacute;s,  mientras el saldo del capital se&ntilde;alado en la cl&aacute;usula primera se encuentre  insoluto. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEXTA.-</strong> <strong><u>INTERES  MORATORIO</u>.-</strong> el incumplimiento del ACREDITADO a cualesquiera de las obligaciones  pactadas en el presente contrato, traer&aacute; como consecuencia, adem&aacute;s del  vencimiento anticipado de la l&iacute;nea de cr&eacute;dito, el pago de un inter&eacute;s moratorio  correspondiente a la cantidad de _______________(______) ______ sobre cada uno  de los vencimientos que presenten atrasos, calculado desde la fecha en que  debi&oacute; realizarse el pago y hasta la fecha de su pago total, los cuales deber&aacute;n  ser cubiertos por el ACREDITADO dentro del d&iacute;a natural siguiente al que se generen.  As&iacute; mismo el incumplimiento del ACREDITADO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEPTIMA.- <u>COMISIONES</u>.-</strong> se le informa al ACREDITADO que no se cobrara ning&uacute;n tipo de comisi&oacute;n, siendo &uacute;nicamente los pagos  descritos en la tabla anterior los que se compromete a pagar mediante la firma  del presente contrato, con excepci&oacute;n de los cobros que se puedan efectuar por  motivo de cualesquiera retrasos en el pago de los vencimientos descritos en la  clausula quinta.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>OCTAVA.-&nbsp; <u>DE LAS MODIFICACIONES DE LA LINEA DE  CREDITO.-</u></strong>CREA  podr&aacute; realizar las siguientes modificaciones sin necesidad de autorizaci&oacute;n  previa o autorizaci&oacute;n por parte del acreditado.</p>
  <ul>
    <li>Modificaci&oacute;n  en tasas de inter&eacute;s, comisiones o gastos aplicables al presente contrato, las  cuales entraran en vigor 15 d&iacute;as naturales posteriores a la comunicaci&oacute;n por  escrito del ACREDITADO.</li>
    <li>La  modificaci&oacute;n de cuales quiera de las condiciones de la l&iacute;nea de cr&eacute;dito materia  del presente contrato.</li>
  </ul>
  <p>Por lo anterior las partes acuerdan que previo a cualquiera  de las modificaciones descritas con anterioridad, se le deber&aacute; informar al  ACREDITADO, por medio de aviso escrito que deber&aacute; ser entregado en su  domicilio, sin necesidad de autorizaci&oacute;n previa. <br />
No ser&aacute; exigible aviso escrito cuando la modificaci&oacute;n sea a  favor del ACREDITADO, bastara con el permanente y constante uso de la l&iacute;nea de  cr&eacute;dito por parte del ACREDITADO para que se comprenda conforme a las mismas,  por lo que al no estar de acuerdo el ACREDITADO, deber&aacute;, manifestarlo por  escrito dentro de los siguientes ___ d&iacute;as h&aacute;biles de recibir la comunicaci&oacute;n,  para cesar el servicio no aceptado, siempre y cuando haya liquidado el monto  adeudado y dem&aacute;s obligaciones directas e indirectas que a la fecha mantenga con  CREA. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>NOVENA.- <u>PAGOS  ANTICIPADOS</u>.-</strong> El  ACREDITADO podr&aacute; realizar pagos anticipados de cuotas por vencer del cr&eacute;dito, o  en su caso de los montos desembolsados bajo la l&iacute;nea de cr&eacute;dito, seg&uacute;n  corresponda, dichos anticipos se efectuaran de la siguiente forma:</p>
  <ul>
    <li>El  monto minimo para el pago anticipado ser&aacute; de ______</li>
    <li>A  la realizaci&oacute;n de pagos anticipados CREA reducir&aacute; un ________ de intereses  correspondientes</li>
  </ul></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA.- CANCELACION  ANTICIPADA POR PARTE DEL ACREDITADO.- </strong>El ACREDITADO podr&aacute; cancelar anticipadamente el total de su  cr&eacute;dito, o el monto desembolsado bajo la l&iacute;nea de cr&eacute;dito, seg&uacute;n corresponda,  siempre y cuando este al corriente en sus pagos.<br />
  En caso de no estar al corriente en sus pagos, la l&iacute;nea de  cr&eacute;dito se cancelara para uso del ACREDITADO., y el saldo deudor al momento,  ser&aacute; exigible en su totalidad, as&iacute; como los intereses ordinarios establecidos e  intereses moratorios que se generen desde el dia natural siguiente a la  cancelaci&oacute;n y hasta la liquidaci&oacute;n total de la deuda.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA PRIMERA.-  INFORMACION.-</strong> El  ACREDITADO podr&aacute; consultar el estado de cuenta de su l&iacute;nea de cr&eacute;dito en la pagina  ____________________________________________.</p>
  <p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEGUNDA.- DE LA  LINEA DE CREDITO.-</strong><br />
La l&iacute;nea de cr&eacute;dito que otorga CREA al CREDITADO es de  car&aacute;cter revolvente y de libre disponibilidad.<br />
CREA no aceptara sobregiros en la l&iacute;nea de cr&eacute;dito, asi mismo  tendr&aacute; facultad para suspender la utilizaci&oacute;n de la l&iacute;nea de cr&eacute;dito, en caso  de que el ACREDITADO incumpla con el pago oportuno de sus obligaciones,  conforme a sus estados de cuenta. <br />
La l&iacute;nea de cr&eacute;dito ser&aacute; cancelada por el ACREDITADO, a  trav&eacute;s de pagos________ por el monto que CREA indique en ________&nbsp;&nbsp;&nbsp; correspondientes a cada desembolso, por concepto de  capital, intereses, y otras comisiones o atributos correspondientes en ____________________<br />
El plazo de la l&iacute;nea de cr&eacute;dito ser&aacute; de manera ______, la  cual se renovara de forma autom&aacute;tica, por periodos iguales, siempre y cuando el  ACREDITADO haya realizado el pago de sus cuotas conforme a los t&eacute;rminos y  condiciones previamente establecidos.</p>
En  caso de que El ACREDITADO no cobre el titulo de cr&eacute;dito al que se&nbsp; hace referencia en la clausula primera,  dentro del termino fijado, no los excluye, ni los libera de la obligaci&oacute;n de  pagar </td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong> DECIMA TERCERA.- <u>SEGURO</u>.-</strong> EL ACREDITADO autoriza a CREA para  que contrate a su nombre un seguro individual de vida con METROPOLITANA COMPA&Ntilde;IA  DE SEGUROS S.A., la cual, en caso de fallecimiento del ACREDITADO&nbsp; &oacute; un familiar directo; esposa &oacute; hijos, pagar&aacute;  el monto total adeudado por el ACREDITADO, m&aacute;s una suma asegurada de $10,000.00  (diez mil pesos 00/100 M.N.), que ser&aacute; entregable al beneficiario indicado por  dicho acreditado. </p>
  <p>EL Seguro de vida no tiene costo alguno para el ACREDITADO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.-<u> CAUSALES DE RESCICION Y/O VENCIMIENTO.-</u></strong> ambas partes aceptan que el presente contrato se dar&aacute;  por rescindido y/o por vencido anticipadamente y la cantidad adeudada al  momento ser&aacute; exigible en su totalidad mas sus accesorios pactados en este  contrato, mas los legales que correspondan en caso de los siguientes supuestos:<br />
    <strong>a).-</strong> Cuando El ACREDITADO deje de pagar  una o mas cuotas en los t&eacute;rminos establecidos.<br />
    <strong>b).-</strong> Si se comprobara la existencia de  falsedad en la informaci&oacute;n o documentaci&oacute;n proporcionada por el ACREDITADO.<br />
    <strong>c).- </strong>Si el ACREDITADO o su AVAL  incumplieran en cuales quiera de las obligaciones establecidas en el presente  contrato.<br />
    <strong>d).-</strong> Si ocurre alguna circunstancia extraordinaria  que a Juicio de CREA, haga improbable que el ACREDITADO no pueda cumplir con  las obligaciones del presente contrato.<br />
De que se incurra en cualquiera de los supuestos anteriores  CREA podr&aacute; dar por vencidos todos los plazos de las obligaciones del  ACREDITADO, y en su caso solicitar el pago inmediato de las mismas, o exigir el  pago del total del cr&eacute;dito insoluto, previa deducci&oacute;n de las cuotas pagadas, as&iacute;  como el pago de intereses ordinarios e intereses moratorios estipulados con  anterioridad.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>. <strong>DECIMA QUINTA.- DEL  AVAL.-</strong> El Aval se  obliga frente CREA de forma solidaria, incondicionada, irrevocable, ilimitada a  garantizar el cumplimiento de las obligaciones del ACREDITADO, as&iacute; como  cualquier incumplimiento por parte del ACREDITADO, tambi&eacute;n el Aval se obliga a  pagar el importe vencido e insoluto del presente cr&eacute;dito.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEXTA.- <u>PRELACION  DE LOS PAGOS</u>.-</strong> CREA aplicara los pagos conforme al siguiente orden:<br />
  <strong>1.- </strong>INTERESES MORATORIOS E IVA DERIVADO(S)  CORREPSONDIENTE AL MONTO DE LAS MISMAS, SI EN SU CASO APLICA.<br />
  <strong>2.- </strong>INTERESE(S) ORDINARIO(S) E IVA  CORREPONDIENTE AL MONTO DE LOS MISMOS.<br />
  <strong>3.-</strong> CAPITAL<br />
  En caso de que se inicie un procedimiento legal por  incumplimiento en contra del deudor los pagos que se realicen se aplacaran en  primer lugar al pago de gastos y costas del juicio y despu&eacute;s se seguir&aacute; con el  orden estipulado anterior mente.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEPTIMA.- <u>CESION  DE DEUDA O CREDITO</u>.-</strong> El ACREDITADO no podr&aacute; ceder total o parcialmente los derechos que les  otorga el presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA OCTAVA.- <u>FALLECIMIENTO  DEL ACREDITADO</u></strong>.-  En caso de fallecimiento del acreditado, CREA har&aacute; la cancelaci&oacute;n del cr&eacute;dito  individual de este. Para lo anterior los familiares o derechohabientes del  mismo, tendr&aacute;n que presentar el acta de defunci&oacute;n del deudor, dentro de los  primeros 10 d&iacute;as h&aacute;biles posteriores a su muerte as&iacute; como identificaci&oacute;n  oficial del mismo, para que esta cancelaci&oacute;n pueda surtir efectos.&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA NOVENA.- <u>EL  ACREDITADO AUTORIZA A CREA &nbsp;A:</u></strong><br />
  <strong>a).-</strong> Proporcionar o recabar informaci&oacute;n  sobre operaciones crediticias y de otra naturaleza an&aacute;loga que haya celebrado  con CREA,&nbsp; y sociedades de informaci&oacute;n  crediticia autorizadas previamente. <br />
  <strong>b).-</strong> Permitir que el CREA por conducto de  cualquiera de sus representantes o persona que este designe, realice <strong>visitas a su domicilio para verificar el  desarrollo de su actividad empresarial as&iacute; como para efectos de cobranza.</strong><br />
  <strong>c).-</strong> Llamar o enviar mensajes a sus  domicilios o tel&eacute;fonos celulares, o de cualquier otra forma, y en cualquier  lugar para informar sobre los servicios que el CREA ofrece, as&iacute; como para  efectos de cobranza.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA.- <u>SERVICIO,  ATENCION&nbsp; AL CLIENTE Y REGULARIZACION</u></strong>.- Asimismo, de conformidad con lo  previsto por la Ley para la Transparencia y Ordenamiento de los Servicios  Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de  Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto  M&uacute;ltiple No Reguladas (la &ldquo;Ley de Transparencia&rdquo;), la PARTES aceptan los  siguientes t&eacute;rminos respecto del presente: &nbsp;<br />
  <strong>1.-</strong> De conformidad con el art&iacute;culo 23 de  la Ley de Transparencia, EL ACREDITRADO acepta realizar la consulta de su  estado de cuenta de manera electr&oacute;nica, mediante la p&aacute;gina  www.financieracrece.com; con el usuario __________________  y clave de acceso ____________misma que podr&aacute; cambiar si EL ACREDITADO  lo considera conveniente. Tambi&eacute;n acepta EL ACREDITADO que dicho estado de  cuenta puede tardar hasta cinco d&iacute;as h&aacute;biles en mostrar la informaci&oacute;n  referente a un pago &uacute; operaci&oacute;n. <br />
  <strong>2.-</strong>&nbsp;&nbsp;CREA manifiesta a EL ACREDITADO  que no requiere de autorizaci&oacute;n de la Secretar&iacute;a de Hacienda y Cr&eacute;dito P&uacute;blico  para la realizaci&oacute;n de las operaciones convenidas en &eacute;ste Contrato, y tampoco  se encuentra sujeta a la supervisi&oacute;n de la Comisi&oacute;n Nacional de Bancaria y de  Valores. &nbsp; <br />
  <strong>3.-</strong>&nbsp; CREA pone a disposici&oacute;n del  ACREDITADO una Unidad Especializada de Atenci&oacute;n al Cliente, para atender  cualquier duda &oacute; queja en el siguiente No. telef&oacute;nico 1993 3278, &oacute; para  atenci&oacute;n personalizada en el domicilio ubicado en Av. Revoluci&oacute;n&nbsp; 1909  Piso 9 Col. San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n, D.F. &nbsp; <br />
  <strong>4-.</strong>&nbsp;&nbsp;Asimismo, CREA hace del  conocimiento del ACREDITADO el n&uacute;mero telef&oacute;nico de atenci&oacute;n a usuarios de la  Comisi&oacute;n Nacional para la Protecci&oacute;n y&nbsp; Defensa de los Usuarios de  Servicios Financieros, para realizar cualquier queja: 5340 0999 &oacute; LADA sin  costo 01800 999 8080, mediante la p&aacute;gina www.condusef.gob.mx &oacute; mediante el  correo electr&oacute;nico opinion@condusef.gob.mx.</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA PRIMERA.- <u>TITULO  EJECUTIVO</u>.-</strong> el  presente contrato conjunto a la certificaci&oacute;n del contador de MICROFINANCIERA  CRECE S.A. DE C.V. SOFOM ENR., respecto del estado de cuenta del ACREDITADO,  ser&aacute; titulo ejecutivo, de conformidad con el articulo 68 de la ley de  instituciones de cr&eacute;dito, por lo que CREA quedara facultado en caso de  incumplimiento o vencimiento anticipado a demandar en la v&iacute;a ejecutiva  mercantil o por la v&iacute;a judicial que mas convenga. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA SEGUNDA:- <u>MODIFICACIONES.-</u></strong> Ninguna  modificaci&oacute;n de t&eacute;rmino o condici&oacute;n alguna del presente Contrato, y ning&uacute;n  consentimiento, renuncia o dispensa en relaci&oacute;n con cualquiera de dichos  t&eacute;rminos o condiciones, tendr&aacute; efecto en caso alguno a menos que conste por  escrito y est&eacute; suscrito por las partes y a&uacute;n entonces dicha modificaci&oacute;n,  renuncia, dispensa o consentimiento s&oacute;lo tendr&aacute; efecto para el caso y fin  espec&iacute;ficos para el cual fue otorgado. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA TERCERA:- <u>LEGISLACI&Oacute;N  APLICABLE Y JURISDICCI&Oacute;N.-</u></strong>Para todo  lo relativo a la interpretaci&oacute;n, ejecuci&oacute;n y cumplimiento de este Convenio, las  Partes se someten a la legislaci&oacute;n aplicable y a la jurisdicci&oacute;n de los  Tribunales competentes &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;_____________________,  renunciando expresamente a cualquier otro fuero que pueda corresponderles por  raz&oacute;n de sus domicilios presentes o futuros o por cualquier otra &iacute;ndole. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.- <u>PAGARE</u>.- </strong>EL ACREDITADO  suscribir&aacute; a la orden de MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR., un  pagare de forma incompleta para que en caso de que el CREDITADO no cumple con  sus obligaciones descritas en el presente contrato, CREA lo complete conforme  al saldo deudor al momento del cr&eacute;dito o conforme al los rembolsos pendientes y  no pagados de la l&iacute;nea de cr&eacute;dito del deudor y conforme a los intereses  ordinarios pactados previamente por las PARTES, as&iacute; como los intereses  moratorios devengados desde que se incurri&oacute; en mora hasta que se pague el total  del adeudo, por lo que una vez liquidado el total del cr&eacute;dito incluyendo dichos  intereses, as&iacute; como accesorios devengados durante el mismo, CREA har&aacute; la  cancelaci&oacute;n del pagare y lo entregara a su respectivo signatario o aval .&nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>ENTERADOS DEL CONTENIDO Y AL ALCANCE JURIDICO DE LAS  OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES CON LA CELEBRACION DE ESTE  CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN, MANIFESTANDO QUE TIENEN  CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE ADQUIEREN  SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI COMO LOS  CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR SU MOTIVO  DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O GASTOS  DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA  CIUDAD&nbsp; DE ___________________________ EL  DIA _______ DE_____________</p></td>
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


<tr>
<td>&nbsp;</td>
<td width="398" align="center"><span>_____________________________________</span></td>
<td width="398" align="center"><span>_____________________________________</span></td>
<td>&nbsp;</td>
</tr>
<tr>
<td height="25">&nbsp;</td>
<td width="398" align="center"><strong>JAVIER  ARTURO FONCERRADA SANCHEZ</strong></td>
<td width="398" align="center">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="398" align="center"><strong>REPRESENTANTE  LEGAL </strong></td>
<td width="398" align="center"><strong>Aval</strong></td>
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

<?php
function LoadData2(){
	return true;
	}
?>