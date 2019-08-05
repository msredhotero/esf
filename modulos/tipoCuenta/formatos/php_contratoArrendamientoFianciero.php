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
<td colspan="2" ><p align="center"><strong>CONTRATO DE ARRENDAMIENTO FINANCIERO</strong></p></td>
<td width="10" >&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>CONTRATO DE  ARRENDAMIENTO FINANCIERO QUE CELEBRAN POR UNA PARTE <strong>MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR.</strong>, A QUIEN EN LO  SUCECIVO SE LE DENOMINARA <strong>“CREA”</strong> Y  POR OTRA PARTE, EL C.__________________ Y QUE EN LO SUCESIVO SE LE DENOMINARA  COMO EL <strong>“ARRENDATARIO”</strong> QUE EN SU  CONJUNTO SE LES DENOMINARA COMO <strong>“LAS  PARTES”</strong>, QUIENES DE CONFORMIDAD SE&nbsp;  HAN MANIFESTADO SU PLENA VOLUNTAD EN SUJETARSE AL TENOR DE LAS  SIGUIENTES:</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>D E C L A R A C I O N E S:</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>I. Declaran ambas partes estar de  acuerdo en que los siguientes t&eacute;rminos tengan el significado que a continuaci&oacute;n  se consigna.</p>
  <p><strong>a)</strong> CREA: Ser una SOCIEDAD ANONIMA DE CAPITAL VARIABLE  SOCIEDAD FINANCIERA DE OBJETO MULTIPLE ENTIDAD NO REGULADA, con domicilio  convencional en: Av. Revoluci&oacute;n, No. 1909, piso 2, Colonia San &Aacute;ngel en M&eacute;xico  Distrito Federal.</p>
  <p><strong>b)</strong> ARRENDATARIO: _____________________, con domicilio  convencional en____________________________________________________. </p>
  <p><strong>c)</strong> BIENES OBJETO DEL ARRENDAMIENTO: _______________________________________ </p>
  <p><strong>d)</strong> PLAZO FORZOSO: ______________________________________________________________________.</p>
  <p><strong>e)</strong> RENTA BASE: Por un monto de $______________ (___________________________  00/100 M.N.) &nbsp;mensual (incluye el  Impuesto al Valor Agregado diferido).</p>
  <p><strong>f)</strong> PAGOS PARCIALES: _____________ pagos parciales siendo  todos y cada uno de ellos por la cantidad de ___________________________.</p>
  <p><strong>g)</strong> OPCION DE COMPRA: Por un valor residual de $_______________________(____________________________  m/n.) m&aacute;s el Impuesto al Valor Agregado correspondiente a dicha opci&oacute;n de  compra, a liquidarse a m&aacute;s tardar el d&iacute;a __________ de _____________del  _____________.</p>
  <p><strong>h)</strong> FECHAS DE PAGO:&nbsp; Los  d&iacute;as ____________ de cada _____________, o el d&iacute;a h&aacute;bil inmediato anterior si  el d&iacute;a de pago fuera inh&aacute;bil bancario.</p>
  <p><strong>i) </strong>LUGAR DE USO: En la Rep&uacute;blica Mexicana.</p>
  <p><strong>j)</strong> PAGARES: Los t&iacute;tulos de cr&eacute;dito que  suscribe el ARRENDATARIO en los t&eacute;rminos de la cl&aacute;usula IV de este contrato.</p>
  <p><strong>k)</strong> El Valor del Bien Objeto del Arrendamiento: $ _________________(_________________________  00/100 M.N.) m&aacute;s el Impuesto al Valor Agregado correspondiente.</p>
  <p><strong>II: Declara CREA a trav&eacute;s de su representante legal</strong>:</p>
  <p><strong>1.-</strong> Ser una persona moral de nacionalidad Mexicana, legalmente constituida conforme  a las leyes de la Republica Mexicana en los t&eacute;rminos de la escritura p&uacute;blica  No. 122,166, de fecha 19 de abril de 2007, basada ante la fe del Notario  P&uacute;blico No. 152 del Distrito Federal,&nbsp; el  Lic. Cecilio Gonzales M&aacute;rquez.</p>
  <p><strong>2.-</strong> Que su representante legal, el C. Javier Arturo Foncerrada S&aacute;nchez est&aacute;  debidamente facultado para celebrar el presente contrato, seg&uacute;n consta en la  escritura p&uacute;blica&nbsp; No. 122,166, de fecha  19 de abril de 2007, basada ante la fe del Notario P&uacute;blico No. 152 del Distrito  Federal,&nbsp; el Lic. Cecilio Gonzales  M&aacute;rquez., manifestando bajo protesta de decir verdad que hasta la fecha no le  han sido revocados, ni limitadas sus facultades en forma alguna.</p>
  <p><strong>3.-</strong> Estar constituida en el domicilio de Av. Revoluci&oacute;n No. 1909 piso 2, colonia  San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n en M&eacute;xico Distrito Federal. <br />
    <strong>4.-</strong> Que considerando las declaraciones del ARRENDATARIO es de su voluntad de  realizar un contrato de arrendamiento financiero.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>III. Declara el ARRENDATARIO:</strong></p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.-</strong>Ser  persona f&iacute;sica mayor de edad, con nacionalidad Mexicana, con domicilio  en_____________________________________ y que cuenta con la capacidad legal  para la celebraci&oacute;n del presente contrato.</p>
  <p><strong>2.- </strong> Que es su voluntad celebrar con <strong>CREA </strong>el presente Contrato de arrendamiento financiero.</p>
  <p><strong>3.-</strong> Que presta servicios personales independientes consistentes en: &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;________________  situaci&oacute;n que acredita con los documentos que se agregan al (y forman parte  del) presente contrato como <strong>anexo1. </strong></p>
  <p><strong>4.- </strong> Que el objeto del presente  contrato es para la adquisición de bienes de inversión, o bien para la  adquisición de las materias primas y materiales y en el pago de los jornales,  salarios y gastos directos de explotación indispensables para los fines de su  empresa, con opción de compra. </p>
  <p>En consecuencia de lo anterior las partes otorgan las  siguientes:</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>C L A U S U L A S</strong>&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>PRIMERA.- <u>ARRENDAMIENTO</u></strong>.-. Por virtud de este contrato CREA se  obliga a adquirir los BIENES OBJETO DEL ARRENDAMIENTO y a conceder su uso o  goce temporal al ARRENDATARIO a un plazo forzoso.</p>
  <p>A tal efecto CREA entrega al  ARRENDATARIO, al tiempo de la firma de este contrato, una ORDEN DE COMPRA O en  su caso dirigida al proveedor o fabricante designado por el ARRENDATARIO  autoriz&aacute;ndola para que &eacute;sta reciba directamente los BIENES OBJETO DEL  ARRENDAMIENTO.</p>
  <p>El ARRENDATARIO reconoce recibir en  este acto la ORDEN DE COMPRA de referencia, y se compromete a entregar a CREA  constancia del recibo de los BIENES OBJETO DEL ARRENDAMIENTO, tan pronto como  los tenga en su posesi&oacute;n.</p>
  <p>Manifiesta el ARRENDATARIO su absoluta  conformidad con los t&eacute;rminos, condiciones, descripciones y especificaciones  consignadas en la ORDEN DE COMPRA, as&iacute; como con la selecci&oacute;n del proveedor,  reconoce, en consecuencia, que CREA &nbsp;no  ser&aacute; responsable de error y omisi&oacute;n en la descripci&oacute;n de los BIENES OBJETO DEL  ARRENDAMIENTO ni con la selecci&oacute;n del proveedor, o con las especificaciones,  t&eacute;rminos o condiciones que se contienen en la orden de compra.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEGUNDA.-</strong> <strong><u>LUGAR Y FORMA DE PAGO</u>.-</strong> el ARRENDATARIO se obliga a pagar:<br />
    <strong>a.-</strong> La cantidad se&ntilde;alada en el inciso K, en un plazo de ____________ contando a  partir de la firma del presente contrato, se dividir&aacute; entre el numero citado de  ______________ y cada renta se deber&aacute; de pagar dentro del primer d&iacute;a h&aacute;bil  siguiente a la _________________ vencida liquid&aacute;ndose el importe  correspondiente.</p>
  <p><strong>b.-</strong> Los pagos de capital y sus accesorios deber&aacute;n realizarse por el ARRENDATARIO en  la tarjeta de cr&eacute;dito No.&nbsp;  ___________________ emitida por Banco Inbursa S.A. Instituci&oacute;n de Banca  M&uacute;ltiple, Grupo Financiero Inbursa.</p>
  <p><strong>c.-</strong> El impuesto al valor agregado (IVA) o a cualquier otro impuesto que se  encuentre a la fecha como vigente que en su caso se genero por causaci&oacute;n de  inter&eacute;s o gastos. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>TERCERA.-</strong> <strong><u>INTERES ORDINARIO</u>.- </strong> las partes acuerdan que la cantidad del inciso  k se le aplicara por concepto de interés ordinario una tasa del _______ (________)  lo cual es equivalente a una tasa _____________(________) anual.</p>
  <p>Los accesorios que se  generen con motivo del presente contrato, deber&aacute;n ser pagados por el ARRENDATARIO  dentro del d&iacute;a h&aacute;bil inmediato siguiente a la semana transcurrida, y por la que  se genero dicho accesorio, mientras el saldo del capital se&ntilde;alado en la  cl&aacute;usula primera se encuentre insoluto. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>QUINTA.-</strong> <strong><u>INTERES MORATORIO</u>.-</strong> El  incumplimiento del ARRENDATARIO a cualesquiera de las obligaciones pactadas en  el presente contrato, traer&aacute; como consecuencia, adem&aacute;s del vencimiento  anticipado del arrendamiento, el pago de un inter&eacute;s moratorio correspondiente a  la cantidad de _______________(______) ______ sobre cada una de las rentas que  presenten atrasos, calculado desde la fecha en que debi&oacute; realizarse el pago y  hasta la fecha de su pago total, los cuales deber&aacute;n ser cubiertos por el ARRENDATARIO  dentro del d&iacute;a natural siguiente al que se generen. As&iacute; mismo el incumplimiento  del ARRENDATARIO a cuales quiera de las obligaciones pactadas en el presente  contrato, dar&aacute; lugar a la&nbsp; rescisi&oacute;n del  contrato y a la entrega del bien arrendado en cuesti&oacute;n.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEXTA.- <u>PAGOS ANTICIPADOS y DISPOSICI&Oacute;N DEL BIEN</u>.-</strong> El ARRENDATARIO podr&aacute; realizar el  pago de rentas anticipadas que se aplicaran a intereses cubiertos, al capital  materia del presente contrato, sin penalizaci&oacute;n alguna, asimismo, las partes  acuerdan que, en caso de que el ARRENDATARIO realice el pago puntual en todas  sus amortizaciones bajo el presente, por cada pago por anticipado (con _____  d&iacute;as naturales de anticipaci&oacute;n) de la cantidad correspondiente a una  amortizaci&oacute;n del monto total de la renta, el ARRENDATARIO tendr&aacute;n derecho a un descuento igual a______________ que se  hubiera generado de dicha amortizaci&oacute;n. </p>
  <p>En caso que El  ARRENDATARIO no utilice el bien objeto del presente contrato al que se hace  referencia en las declaraciones, dentro del termino fijado, no lo excluye, ni  lo libera de la obligaci&oacute;n de pagar las obligaciones que se han pactado como  fijas e irrenunciables.</p>
  <p>El bien se entiende por  otorgado a la vista de la firma que calza al final de dicho contrato, por lo  que El ARRENDATARIO, no podr&aacute; alegar o reclamar para no disponer del bien y  deber&aacute; de cumplir con todas y cada una de las obligaciones pactadas en el  presente contrato es decir el pago total del arrendamiento, as&iacute; como los  intereses previamente establecidos.</p>
  <p>A tal efecto el ARRENDATARIO se compromete a cubrir a CREA,  las RENTAS y accesorios antes definidos, en cada una de las FECHAS DE PAGO con  forme a la siguiente tabla. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p>TABLA:::</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEPTIMA.- <u>PAGARE</u>.- </strong>En los t&eacute;rminos del art&iacute;culo 26 de la Ley General de  Organizaciones y Actividades Auxiliares del Cr&eacute;dito, EL ACREDITADO suscribir&aacute; a la orden de MICROFINANCIERA CRECE  S.A. DE C.V. SOFOM ENR., un pagare por la cantidad de  $____________________(____), suma total del arrendamiento otorgado, mas un  inter&eacute;s ordinario correspondiente a ___________________, asi como un inter&eacute;s  moratorio correspondiente a______________ diarios sobre cada uno de las rentas  que presen ten atraso calculando desde la fecha en que se debi&oacute; de realizar el  pago y hasta que se liquide el total del mismo., los cuales ser&aacute;n pagaderos a  la vista por lo que una vez liquidado el total del cr&eacute;dito incluyendo intereses  ordinarios, moratorios, as&iacute; como accesorios devengados durante el mismo, el  CREA har&aacute; la cancelaci&oacute;n de este y lo entregara a su respectivo signatario o  aval.&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>OCTAVA</strong><strong>.-<u> CAUSALES DE RESCICION Y/O VENCIMIENTO.-</u></strong> ambas partes aceptan que el presente  contrato se dar&aacute; por rescindido y/o por vencido anticipadamente y el bien  objeto del presente contrato ser&aacute; exigible o en su caso el pago de la renta en  su totalidad, mas sus accesorios as&iacute; como los legales que correspondan en caso  de los siguientes supuestos:</p>
  <p><strong>a).-</strong> Cuando El ARRENDATARIO no pague en forma oportuna una o varias de las rentas o  intereses establecidos en el presente contrato.</p>
  <p><strong>b).-</strong> Si se comprobara la existencia de falsedad en la informaci&oacute;n o documentaci&oacute;n  proporcionada por el ARRENDATARIO.</p>
  <p><strong>c).-</strong> Si se comprobara que el ARRENDATARIO destinara el bien para un fin distinto a  lo solicitado, y/o alg&uacute;n fin il&iacute;cito.</p>
  <p><strong>d).-</strong> si ocurre alguna circunstancia extraordinaria que a Juicio de CREA, haga  improbable que el ARRENDATARIO no pueda cumplir con las obligaciones del  presente contrato.</p>
  <p><strong>a).- Si CREA opta por la rescisi&oacute;n, el ARRENDATARIO:</strong></p>
  <p>(1) deber&aacute; hacer inmediata devoluci&oacute;n  de los BIENES OBJETO DEL ARRENDAMIENTO,</p>
  <p>(2) pagar&aacute; a&nbsp; CREA una pena equivalente a dos veces el  importe del &uacute;ltimo pago parcial devengado, y</p>
  <p>(3) en su caso, cubrir&aacute; a CREA  cualquiera de los PAGOS PARCIALES vencidos o cualquier otro concepto adeudado,  con sus intereses moratorios respectivos, y</p>
  <p>(4) perder&aacute; de pleno derecho, sin  necesidad de declaraci&oacute;n judicial, su derecho a la OPCION DE COMPRA consignada  en la cl&aacute;usula ____ de este contrato. En este caso de rescisi&oacute;n, si por  cualquier motivo, voluntario o involuntario, no hiciere la ARRENDATARIO  devoluci&oacute;n inmediata de los BIENES OBJETO DEL ARRENDAMIENTO a CREA, deber&aacute;  cubrir a &eacute;sta como compensaci&oacute;n por cada d&iacute;a de mora en la entrega, una  cantidad equivalente al &uacute;ltimo PAGO PARCIAL DEVENGADO, multiplicado por ____ y  dividido entre ______. La recepci&oacute;n de estas cantidades por parte de CREA no  podr&aacute; considerarse como consentimiento para la no devoluci&oacute;n.</p>
  <p><strong>b) Si la CREA, optase por exigir el cumplimiento  anticipado de este contrato, se dar&aacute; por vencido de pleno derecho, sin  necesidad de declaraci&oacute;n judicial, el PLAZO FORZOSO, y en consecuencia el  ARRENDATARIO:</strong></p>
  <p>(1) deber&aacute; cubrir de inmediato a la  CREA el saldo insoluto de la RENTA TOTAL,</p>
  <p>(2) deber&aacute; cubrir de inmediato la  OPCION DE COMPRA y, en su caso,</p>
  <p>(3) cubrir&aacute; a la CREA de inmediato  cualquiera de los PAGOS PARCIALES vencidos o cualquier otro concepto adeudado,  con sus intereses moratorios respectivos. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>NOVENA.- <u>CESI&Oacute;N</u></strong>.-. El ARRENDATARIO no podr&aacute; ceder, ni  traspasar, subarrendar, vender, gravar o transmitir los BIENES OBJETO DEL  ARRENDAMIENTO, ni la posesi&oacute;n parcial o total de los mismos.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA.- <u>OPCI&Oacute;N TERMINAL</u></strong>.- En los t&eacute;rminos del art&iacute;culo 27 de  la Ley General de Organizaciones y Actividades Auxiliares de Cr&eacute;dito al  finalizar el plazo del presente contrato y siempre y cuando el arrendatario  haya liquidado la totalidad de las rentas y dem&aacute;s contraprestaciones pactadas  en el mismo, a satisfacci&oacute;n de CREA, &eacute;ste podr&aacute; ejercer cualquiera de las  siguientes opciones:</p>
  <p>a) Que CREA transfiera al arrendatario  la propiedad del bien (es) se&ntilde;alado en la declaraci&oacute;n No. I, Inciso c) del  presente contrato en el valor y fecha estipulado en el inciso D y K de la  Declaraci&oacute;n I del presente contrato.</p>
  <p>b) Que se proceda a la venta de los  bienes mencionados a un tercero en el precio que indique el arrendatario a CREA,  en cuyo caso CREA se aplicar&aacute; la cantidad establecida en el inciso a) anterior  m&aacute;s todos los gastos de conservaci&oacute;n y venta de los bienes arrendados,  entregando el saldo a la arrendataria. En ning&uacute;n caso el valor indicado por la  arrendataria podr&aacute; ser inferior al valor establecido en el inciso a) anterior.</p>
  <p>c) Que el arrendador prorrogue el  presente contrato por un plazo m&aacute;ximo de 6 meses posteriores al vencimiento del  plazo inicial pactado, con una renta mensual que deber&aacute; ser inferior a la que  se fij&oacute; durante el plazo inicial del contrato y la cual ser&aacute; determinada de com&uacute;n  acuerdo entre CREA y&nbsp; EL ARRENDATARIO. </p>
  <p>Al t&eacute;rmino de la pr&oacute;rroga, EL  ARRENDATARIO podr&aacute; ejercer cualquiera de las opciones se&ntilde;aladas en los incisos  a) y b) anteriores. El ARRENDATARIO deber&aacute; manifestar por escrito a la  arrendadora la opci&oacute;n que va a ejercer por lo menos con un mes de anticipaci&oacute;n  a la terminaci&oacute;n del contrato. Si el arrendatario no comunica oportunamente su  elecci&oacute;n CREA, se entender&aacute; que ejercer&aacute; la opci&oacute;n mencionada en el inciso a)  anterior.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA PRIMERA.- <u>USO Y MANTENIMIENTO</u></strong>.- EL ARRENDATARIO queda obligado a  conservar los BIENES OBJETO DEL ARRENDAMIENTO en el estado que permita el uso  normal que les corresponde, a dar el mantenimiento necesario para ese prop&oacute;sito  y, consecuentemente, a hacer por su cuenta las reparaciones que se requieran y  a adquirir las refacciones e implementos que sean necesarios para el citado  uso.</p>
  <p>Dichas refacciones, implementos y  bienes que se adicionen a los BIENES OBJETO DEL ARRENDAMIENTO, se considerar&aacute;n  incorporados a &eacute;stos y consecuentemente, sujetos a los t&eacute;rminos del presente  contrato.</p>
  <p>EL ARRENDATARIO debe servirse de los  BIENES OBJETO DEL ARRENDAMIENTO solamente conforme a su naturaleza y destino,  siendo responsable de los da&ntilde;os que los mismos sufran y ocasionen por darles  otro uso, o por su culpa o negligencia, o la de sus empleados o terceros.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEGUNDA.- &nbsp;<u>RIESGOS  A CARGO DEL ARRENDATARIO</u></strong>.- Son a riesgo del ARRENDATARIO: </p>
  <p>a) Los vicios o defectos ocultos de los  BIENES OBJETO DEL ARRENDAMIENTO que impidan su uso parcial o total, en este  caso, a petici&oacute;n escrita del ARRENDATARIO, CREA la legitimar&aacute; a fin de que en  su representaci&oacute;n ejercite sus derechos como comprador,</p>
  <p> b) La pérdida parcial o total de los BIENES  OBJETO DEL ARRENDAMIENTO, aunque ésta se realice por causa de fuerza mayor o  caso fortuito, y </p>
  <p>c) En general, todos los riesgos,  p&eacute;rdidas, robos, destrucci&oacute;n o da&ntilde;os que sufrieren u ocasionaren los BIENES  OBJETO DEL ARRENDAMIENTO.</p>
  <p><strong>Frente a las eventualidades se&ntilde;aladas, El ARRENDATARIO no  quedar&aacute; liberado de ninguna de sus obligaciones, por lo que deber&aacute; seguir  pagando la RENTA TOTAL mediante los PAGOS PARCIALES.</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA TERCERA.- <u>FALLECIMIENTO DEL ACREDITADO</u></strong>.- En caso de fallecimiento del  acreditado, CREA har&aacute; la cancelaci&oacute;n del arrendamiento de este. Para lo  anterior los familiares o derechohabientes del mismo, tendr&aacute;n que presentar el  acta de defunci&oacute;n del deudor, dentro de los primeros 10 d&iacute;as h&aacute;biles  posteriores a su muerte as&iacute; como identificaci&oacute;n oficial del mismo, para que  esta cancelaci&oacute;n pueda surtir efectos.</p>
  <p>Y a los familiares se  les otorgara las opciones correspondientes en la clausula No. -____&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.- <u>PROCEDIMIENTO  CONVENCIONAL</u></strong>.- En los t&eacute;rminos de los art&iacute;culos 1051 y 1052 del C&oacute;digo de Comercio,  convienen las partes en que, para el cobro de cualquier suma a cargo del ARRENDATARIO  conforme a este contrato, CREA podr&aacute; promover demanda en la v&iacute;a ejecutiva  mercantil, de acuerdo a las regulaciones contenidas en el propio C&oacute;digo de  Comercio.</p>
  <p>Conviene tambi&eacute;n las partes, que el estado  de cuenta del ARRENDATARIO, certificado por el contador de&nbsp; CREA, har&aacute; f&eacute;, salvo prueba en contrario, en  el juicio respectivo, para la fijaci&oacute;n del saldo resultante a cargo del ARRENDATARIO.<br />
    El presente contrato, junto con la  certificaci&oacute;n del contador a que se refiere el p&aacute;rrafo anterior, ser&aacute; t&iacute;tulo  ejecutivo, sin necesidad de reconocimiento de firma ni de otro requisito  alguno.</p>
  <p>Convienen asimismo las partes, como una  modalidad a la pactada acci&oacute;n ejecutiva mercantil, que en caso de que CREA haya  decretado la rescisi&oacute;n del presente contrato en los t&eacute;rminos de su cl&aacute;usula OCTAVA, CREA podr&aacute; pedir  judicialmente la posesi&oacute;n de los BIENES OBJETO DEL ARRENDAMIENTO. El juez  estar&aacute; autorizado para decretar de plano la posesi&oacute;n cuando le sea pedida por  la CREA en la demanda o en escrito por separado.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA QUINTA.- <u>PERMISOS</u></strong>.- Ser&aacute;n por cuenta y responsabilidad  del ARRENDATARIO la obtenci&oacute;n y el pago de los permisos, licencias, derechos de  uso de patente o marca y dem&aacute;s que sean necesarios para la operaci&oacute;n de los  BIENES <br />
OBJETO DEL ARRENDAMIENTO.</p>
  <p>En caso de incumplimiento del  ARRENDATARIO de lo previsto en esta cl&aacute;usula, CREA podr&aacute; obtener directamente  dichos permisos, licencias y derechos. Los importes pagados por el  ARRENDATARIO, causar&aacute;n intereses moratorios previstos en la cl&aacute;usula QUINTA de este contrato, a partir de la fecha de  su erogaci&oacute;n y hasta que sean reembolsados por el ARRENDATARIO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>. <strong>DECIMA SEXTA.- <u>MULTAS</u></strong>.- Ser&aacute;n a cargo del ARRENDATARIO todas  las multas y sanciones impuestos por las autoridades, derivadas del uso de los  BIENES OBJETO DEL ARRENDAMIENTO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEPTIMA.- <u>TR&Aacute;MITES</u></strong>.- El ARRENDATARIO se obliga a realizar  todos los tr&aacute;mites que sean necesarios para obtener permisos, licencias o  registros que se requieran para el buen funcionamiento de los BIENES OBJETO DEL  ARRENDAMIENTO, en caso de que as&iacute; se requiera.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA OCTAVA.- <u>IMPUESTOS</u></strong>.- El ARRENDATARIO queda obligada a  pagar todos los impuestos, derechos y gastos que cause o llegare a causar el  presente contrato, los BIENES OBJETO DEL ARRENDAMIENTO o su tenencia, as&iacute; como,  en su caso los gastos de inscripci&oacute;n del contrato en el Registro P&uacute;blico de la  Propiedad. Los grav&aacute;menes fiscales o cualquier variaci&oacute;n de los mismos causados  por la percepci&oacute;n de la renta y la opci&oacute;n estipulada, ser&aacute;n por cuenta y cargo  del ARRENDATARIO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA NOVENA.- <u>PRELACION DE LOS PAGOS</u>.-</strong> CREA aplicara los pagos&nbsp; de renta conforme al siguiente orden:</p>
  <p><strong>1.- </strong>INTERESES  MORATORIOS E IVA DERIVADO(S) CORRESPONDIENTE AL MONTO DE LAS MISMAS, SI EN SU  CASO APLICA.</p>
  <p><strong>2.- </strong>INTERESE(S)  ORDINARIO(S) E IVA CORREPONDIENTE AL MONTO DE LOS MISMOS.</p>
  <p><strong>3.-</strong> CAPITAL</p>
  <p>En caso de que se  inicie un procedimiento legal por incumplimiento en contra del deudor los pagos  que se realicen se aplacaran en primer lugar al pago de gastos y costas del  juicio y despu&eacute;s se seguir&aacute; con el orden estipulado anterior mente.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA.- <u>CESION DE DEUDA O CREDITO</u>.-</strong> El ARRENDATARIO no podr&aacute; ceder total  o parcialmente los derechos que les otorga el presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA PRIMERA.- <u>EL ARRENDATARIO AUTORIZA A CREA&nbsp; A:</u></strong></p>
  <p><strong>a).-</strong> Proporcionar o recabar informaci&oacute;n sobre operaciones crediticias y de otra  naturaleza an&aacute;loga que haya celebrado con CREA,&nbsp;  y sociedades de informaci&oacute;n crediticia autorizadas previamente. </p>
  <p><strong>b).-</strong> Permitir que el CREA por conducto de cualquiera de sus representantes o persona  que este designe, realice <strong>visitas a su  domicilio para verificar el desarrollo de su actividad empresarial as&iacute; como  para efectos de cobranza.</strong></p>
  <p><strong>c).-</strong> Llamar o enviar mensajes a sus domicilios o tel&eacute;fonos celulares, o de cualquier  otra forma, y en cualquier lugar para informar sobre los servicios que el CREA  ofrece, as&iacute; como para efectos de cobranza.</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA TERCERA.- <u>SEGURO</u>.-</strong> EL ARRENDATARIO autoriza a CREA para  que contrate a su nombre un seguro individual de vida con METROPOLITANA  COMPA&Ntilde;IA DE SEGUROS S.A., la cual, en caso de fallecimiento del ARRENDATARIO&nbsp; &oacute; un familiar directo; esposa &oacute; hijos, pagar&aacute;  el monto total adeudado por el ARRENDATARIO, m&aacute;s una suma asegurada de  $10,000.00 (diez mil pesos 00/100 M.N.), que ser&aacute; entregable al beneficiario  indicado por dicho acreditado. </p>
  <p>EL Seguro de vida no  tiene costo alguno para el ARRENDATARIO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA CUARTA.- <u>SERVICIO, ATENCION&nbsp; AL CLIENTE Y REGULARIZACION</u></strong>.- Asimismo, de conformidad con lo  previsto por la Ley para la Transparencia y Ordenamiento de los Servicios  Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de  Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto  M&uacute;ltiple No Reguladas (la &ldquo;Ley de Transparencia&rdquo;), la PARTES aceptan los  siguientes t&eacute;rminos respecto del presente: &nbsp;</p>
  <p><strong>1.-</strong> De conformidad con el art&iacute;culo 23 de la Ley de Transparencia, EL ARRENDATARIO  acepta realizar la consulta de su estado de cuenta de manera electr&oacute;nica,  mediante la p&aacute;gina www.financieracrece.com; con el usuario __________________ y clave de acceso  ____________misma que podr&aacute; cambiar si EL ARRENDATARIO lo considera conveniente.  Tambi&eacute;n acepta EL ARRENDATARIO que dicho estado de cuenta puede tardar hasta  cinco d&iacute;as h&aacute;biles en mostrar la informaci&oacute;n referente a un pago &uacute; operaci&oacute;n. </p>
  <p><strong>2.-</strong>&nbsp;&nbsp;CREA  manifiesta a EL ARRENDATARIO que no requiere de autorizaci&oacute;n de la Secretar&iacute;a  de Hacienda y Cr&eacute;dito P&uacute;blico para la realizaci&oacute;n de las operaciones convenidas  en &eacute;ste Contrato, y tampoco se encuentra sujeta a la supervisi&oacute;n de la Comisi&oacute;n  Nacional de Bancaria y de Valores. &nbsp; </p>
  <p><strong>3.-</strong>&nbsp;  CREA pone a disposici&oacute;n del ARRENDATARIO una Unidad Especializada de Atenci&oacute;n  al Cliente, para atender cualquier duda &oacute; queja en el siguiente No. telef&oacute;nico  1993 3278, &oacute; para atenci&oacute;n personalizada en el domicilio ubicado en Av.  Revoluci&oacute;n&nbsp; 1909 Piso 9 Col. San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n, D.F.  &nbsp; </p>
  <p><strong>4-.</strong>&nbsp;&nbsp;Asimismo,  CREA hace del conocimiento del ARRENDATARIO el n&uacute;mero telef&oacute;nico de atenci&oacute;n a  usuarios de la Comisi&oacute;n Nacional para la Protecci&oacute;n y&nbsp; Defensa de los  Usuarios de Servicios Financieros, para realizar cualquier queja: 5340 0999 &oacute;  LADA sin costo 01800 999 8080, mediante la p&aacute;gina www.condusef.gob.mx &oacute;  mediante el correo electr&oacute;nico opinion@condusef.gob.mx.</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA QUINTA.- <u>MODIFICACIONES.-</u></strong> Ninguna  modificaci&oacute;n de t&eacute;rmino o condici&oacute;n alguna del presente Contrato, y ning&uacute;n  consentimiento, renuncia o dispensa en relaci&oacute;n con cualquiera de dichos  t&eacute;rminos o condiciones, tendr&aacute; efecto en caso alguno a menos que conste por  escrito y est&eacute; suscrito por las partes y a&uacute;n entonces dicha modificaci&oacute;n,  renuncia, dispensa o consentimiento s&oacute;lo tendr&aacute; efecto para el caso y fin  espec&iacute;ficos para el cual fue otorgado. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>VIGESIMA SEXTA.- <u>LEGISLACI&Oacute;N  APLICABLE Y JURISDICCI&Oacute;N.-</u></strong>Para todo  lo relativo a la interpretaci&oacute;n, ejecuci&oacute;n y cumplimiento de este Contrato, las  Partes se someten a la legislaci&oacute;n aplicable y a la jurisdicci&oacute;n de los  Tribunales competentes &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;_____________________,  renunciando expresamente a cualquier otro fuero que pueda corresponderles por  raz&oacute;n de sus domicilios presentes o futuros o por cualquier otra &iacute;ndole. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>ENTERADOS DEL CONTENIDO  Y AL ALCANCE JURIDICO DE LAS OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES  CON LA CELEBRACION DE ESTE CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN,  MANIFESTANDO QUE TIENEN CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE  ADQUIEREN SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI  COMO LOS CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR  SU MOTIVO DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O  GASTOS DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA  CIUDAD&nbsp; DE ___________________________ EL  DIA _______ DE_____________</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp; &nbsp; &nbsp; </p>
  <div align="center"></div></td>
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

<?php
function LoadData2(){
	return true;
	}
?>