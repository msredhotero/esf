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
<td colspan="2" ><p>CONTRATO DE CREDITO SIMPLE CON INTERES QUE CELEBRAN POR UNA  PARTE <strong>MICROFINANCIERA CRECE S.A. DE C.V.  SOFOM ENR.</strong>, A QUIEN EN LO SUCECIVO SE LE DENOMINARA <strong>�CREA�</strong> Y POR OTRA PARTE, CON <strong>�<u>� LA DEPENDIENDO SEXO EN SOLICITUD</u></strong> C.__________________ QUE EN LO SUCESIVO SE LE DENOMINARA COMO EL <strong>�ACREDITADO�</strong> QUE EN SU CONJUNTO SE LES  DENOMINARA COMO <strong>�LAS PARTES�</strong>,  QUIENES DE CONFORMIDAD SE &nbsp;HAN  MANIFESTADO SU PLENA VOLUNTAD EN SUJETARSE AL TENOR DE LAS SIGUIENTES:</p></td>
<td width="10" >&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2" align="center"><strong>DECLARACIONES</strong>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><strong>A).-EL  ACREDITADO DECLARA LO SIGUIENTE</strong></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.- </strong>Ser persona f&iacute;sica mayor de edad, con  nacionalidad Mexicana, con domicilio en_____________________________________ y  que cuenta con la capacidad legal para la celebraci&oacute;n del presente contrato.<br />
    <strong>2.-</strong><strong> </strong>�Que es su  voluntad celebrar con <strong>CREA </strong>el presente Contrato, a efecto obtener en  concepto de pr&eacute;stamo con inter&eacute;s la cantidad se&ntilde;alada en la cl&aacute;usula Primera y  de garantizar el puntual pago de la cantidad adeudada, as&iacute; como de los  intereses y dem&aacute;s accesorios legales que se llegaran a generar a favor de <strong>CREA </strong>y a su cargo. <br />
&nbsp;<strong>3.-</strong> Que presta servicios personales independientes consistentes en: &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;________________  situaci&oacute;n que acredita con los documentos que se agregan al (y forman parte  del) presente contrato como <strong>anexo1. </strong> <br />
<strong>4.- </strong>�Que destinar� el pr�stamo a que se refiere  este contrato a la adquisici�n de bienes de inversi�n, o bien para la  adquisici�n de las materias primas y materiales y en el pago de los jornales,  salarios y gastos directos de explotaci�n indispensables para los fines de su  negocio.</p></td>
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
  <strong>2.-</strong> Que su representante legal, el C.  Javier Arturo Foncerrada S&aacute;nchez est&aacute; debidamente facultado para celebrar el  presente contrato, seg&uacute;n consta en la escritura p&uacute;blica &nbsp;No. 122,166, de fecha 19 de abril de 2007,  basada ante la fe del Notario P&uacute;blico No. 152 del Distrito Federal,&nbsp; el Lic. Cecilio Gonzales M&aacute;rquez.,  manifestando bajo protesta de decir verdad que hasta la fecha no le han sido  revocados, ni limitadas sus facultades en forma alguna.<br />
  <strong>3.-</strong> Estar constituida en el domicilio de  Av. Revoluci&oacute;n No. 1909 piso 2, colonia San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n en  M&eacute;xico Distrito Federal. <br />
  <strong>4.-</strong> Que considerando las declaraciones  del ACREDITADO es de su voluntad otorgar un cr&eacute;dito simple con inter&eacute;s. <br />
  Expuestas las anteriores declaraciones, las partes que  suscriben el presente contrato manifiestan su voluntad de otorgar y sujetarse  al tenor de las siguientes clausulas.</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>CLAUSULAS</strong>&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>PRIMERA.-</strong> <strong><u>OBJETO  DEL CONTRATO</u>.-</strong> En este acto CREA otorga al ACREDITADO y el ACREDITADO  recibe, un cr&eacute;dito simple con inter&eacute;s por la cantidad de _________________(____________________),  &nbsp;los cuales son recibidos mediante el  cheque No.________________ emitido por_____________________., &nbsp;el ACREDITADO en este acto se da por recibido  la cantidad objeto del pr&eacute;stamo con inter&eacute;s objeto del presente contrato, y  salvo buen cobro el cheque correspondiente, otorga a CREA el recibo mas amplio  que en derecho proceda respecto de dicha cantidad. </p>
  <p>As&iacute; mismo el ACREDITADO est&aacute; de acuerdo con el monto se&ntilde;alado  y se obligan a cumplir conforme a los t&eacute;rminos y condiciones del presente  contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEGUNDA.-</strong> el ACREDITADO se compromete a  destinar el importe del cr&eacute;dito solicitado para la actividad econ&oacute;mica  establecida por cada uno en la lista de integrantes que forma parte del  presente contrato. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>TERCERA.-</strong> <strong><u>LUGAR  Y FORMA DE PAGO</u>.-</strong> el ACREDITADO se obliga a pagar:</p>
  <p><strong>a.-</strong> La cantidad se&ntilde;alada en la clausula  primera, en un plazo de ______(_<strong>NO. DE  PAGOS Y PERIODICIDAD Y NO </strong>_EN <strong>MESES,  EJ; 3 SEMANAS &Oacute; 10 MESES</strong>_)___ contando a partir de la firma del presente  contrato, se dividir&aacute; entre el numero citado de ______________ y cada  parcialidad se deber&aacute; de pagar dentro del primer d&iacute;a h&aacute;bil siguiente a la  _________________ vencida liquid&aacute;ndose el importe correspondiente.<br />
    <strong>b.-</strong> Los pagos de capital y sus accesorios  deber&aacute;n realizarse por el ACREDITADO en la tarjeta de cr&eacute;dito No.&nbsp; ___________________ emitida por Banco Inbursa  S.A. Instituci&oacute;n de Banca M&uacute;ltiple, Grupo Financiero Inbursa.<br />
    <strong>c.-</strong> El impuesto al valor agregado (IVA) o  a cualquier otro impuesto que se encuentre a la fecha como vigente que en su  caso se genero por causaci&oacute;n de inter&eacute;s o gastos. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>CUARTA.-</strong> <strong><u>INTERES  ORDINARIO</u>.- </strong>�las partes acuerdan  que la cantidad de la clausula primera se le aplicara por concepto de inter�s ordinario  una tasa del _______(________) lo cual es equivalente a una tasa  _____________(________) anual.<br />
Los intereses que se generen con motivo del presente  contrato, deber&aacute;n ser pagados por el ACREDITADO dentro del dia h&aacute;bil inmediato  siguiente a la semana transcurrida, y por la que se genero dicho inter&eacute;s,  mientras el saldo del capital se&ntilde;alado en la cl&aacute;usula primera se encuentre  insoluto. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>QUINTA.-</strong> <strong><u>INTERES  MORATORIO</u>.-</strong> el incumplimiento del ACREDITADO a cualesquiera de las obligaciones  pactadas en el presente contrato, traer&aacute; como consecuencia, adem&aacute;s del  vencimiento anticipado del cr&eacute;dito, el pago de un inter&eacute;s moratorio  correspondiente a la cantidad de _______________(______) ______ sobre cada uno  de los vencimientos que presenten atrasos, calculado desde la fecha en que  debi&oacute; realizarse el pago y hasta la fecha de su pago total, los cuales deber&aacute;n  ser cubiertos por el ACREDITADO dentro del d&iacute;a natural siguiente al que se  generen. As&iacute; mismo el incumplimiento del ACREDITADO a cualesquiera de las  obligaciones pactadas en el presente contrato, dar&aacute; lugar&nbsp; a la&nbsp;  ejecuci&oacute;n de la garant&iacute;a.</p>
  <p>Lo anterior  expuesto conforme a la siguiente tabla:  ___________________________________________________</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEXTA.- <u>COMISIONES</u>.-</strong> se le informa al ACREDITADO que no se  cobrara ning&uacute;n tipo de comisi&oacute;n, siendo &uacute;nicamente los pagos descritos en la  tabla anterior los que se compromete a pagar mediante la firma del presente  contrato, con excepci&oacute;n de los cobros que se puedan efectuar por motivo de  cualesquiera retrasos en el pago de los vencimientos descritos en la clausula  quinta.&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEPTIMA.- <u>PAGOS  ANTICIPADOS y DISPOSICI&Oacute;N DEL CREDITO</u>.-</strong> El ACREDITADO podr&aacute; realizar pagos anticipados que se  aplicaran a intereses cubiertos, al capital materia del presente contrato, sin  penalizaci&oacute;n alguna, asimismo, las partes acuerdan que, en caso de que el  ACREDITADO realicen el pago puntual en todas sus amortizaciones bajo el  presente, por cada pago por anticipado (con _____ d&iacute;as naturales de  anticipaci&oacute;n) de la cantidad correspondiente a una amortizaci&oacute;n del monto de la  deuda, el ACREDITADO tendr&aacute;n derecho a un descuento igual a______________ que  se hubiera generado de dicha amortizaci&oacute;n. <br />
  En caso de que El ACREDITADO no cobre el titulo de cr&eacute;dito al  que se&nbsp; hace referencia en la clausula  primera, dentro del termino fijado, no los excluye, ni los libera de la  obligaci&oacute;n de pagar las obligaciones que se han pactado como fijas e  irrenunciables.</p>
  <p>El cr&eacute;dito se entiende por otorgado a la vista de la firma  que calza al final de dicho contrato, por lo que El ACREDITADO, no podr&aacute; alegar  o reclamar para no disponer del cr&eacute;dito y deber&aacute;n de cumplir con todas y cada  una de las obligaciones pactadas en el presente contrato es decir el pago total  del cr&eacute;dito, as&iacute; como los intereses previamente establecidos.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>OCTAVA.- <u>PRELACION DE  LOS PAGOS</u>.-</strong> CREA  aplicara los pagos conforme al siguiente orden:<br />
  <strong>1.- </strong>PENALIZACIONE(S) E IVA DERIVADO(S)  CORREPSONDIENTE AL MONTO DE LAS MISMAS, SI EN SU CASO APLICA.<br />
  <strong>2.- </strong>INTERESE(S) ORDINARIO(S) E IVA  CORREPONDIENTE AL MONTO DE LOS MISMOS.<br />
  <strong>3.-</strong> CAPITAL<br />
  En caso de que se inicie un procedimiento legal por  incumplimiento en contra del deudor los pagos que se realicen se aplacaran en  primer lugar al pago de gastos y costas del juicio y despu&eacute;s se seguir&aacute; con el  orden estipulado anterior mente.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>NOVENA.- <u>CESION DE  DEUDA O CREDITO</u>.-</strong> El ACREDITADO no podr&aacute; ceder total o parcialmente los derechos que les otorga  el presente contrato.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA.-<u> CAUSALES DE  RESCICION Y/O VENCIMIENTO.-</u></strong> ambas partes aceptan que el presente contrato se dar&aacute; por  rescindido y/o por vencido anticipadamente y la cantidad entregada en pr&eacute;stamo  ser&aacute; exigible en su totalidad mas sus accesorios pactados en este contrato, mas  los legales que correspondan en caso de los siguientes supuestos:<br />
  <strong>a).-</strong> Cuando El ACREDITADO no pague en  forma oportuna una o varias de las amortizaciones de principal o intereses  establecidas en el presente contrato.<br />
  <strong>b).-</strong> Si se comprobara la existencia de  falsedad en la informaci&oacute;n o documentaci&oacute;n proporcionada por el ACREDITADO.<br />
  <strong>c).-</strong> Si se comprobara que el ACREDITADO  destinara el monto proporcionado a un fin distinto a lo solicitado, y/o alg&uacute;n  fin il&iacute;cito.<br />
  <strong>d).-</strong> si ocurre alguna circunstancia extraordinaria  que a Juicio de CREA, haga improbable que el ACREDITADO no pueda cumplir con  las obligaciones del presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA  PRIMERA:- <u>GARANT&Iacute;A.</u></strong>De conformidad con lo previsto  por la Ley General de T&iacute;tulos y Operaciones de Cr&eacute;dito, con el objeto de  garantizar el pago total y oportuno en la fecha de vencimiento (ya sea en la  fecha de vencimiento acordada, en una fecha de vencimiento anticipado o por  cualquier otra raz&oacute;n) del principal e intereses del Cr&eacute;dito otorgados por  Acreedor al ACREDITADO, as&iacute; como de cualquier otra cantidad de tiempo en tiempo  adeudada por el ACREDITADO a CREA bajo el Cr&eacute;dito, en este acto, El ACREDITADO  otorga a favor de CREA una prenda sin transmisi&oacute;n de posesi&oacute;n, en primer lugar  y grado de prelaci&oacute;n, debidamente perfeccionada, sobre [, la cual se describe a  continuaci&oacute;n: _______________________________________ De conformidad con lo  anterior, el ACREDITADO de manera simult&aacute;nea a la celebraci&oacute;n del presente  contrato, endosa en garant&iacute;a a favor de CREA, el original de la factura que  acredita su propiedad sobre los bienes otorgados en prenda conforme al  presente.</p>
  <p>Asimismo, de conformidad con lo previsto por los  art&iacute;culos 355 y 356 de la Ley General de T&iacute;tulos y Operaciones de Cr&eacute;dito,  dichos bienes quedar&aacute;n en posesi&oacute;n del ACREDITADO con el car&aacute;cter de  depositario a titulo gratuito, nombramiento que mediante la firma del presente  Contrato acepta y protesta su leal y fiel cumplimiento, el cual no podr&aacute;  delegar durante la vigencia del cr&eacute;dito, ni ser revocado en tanto se encuentre  al corriente del cumplimiento de las obligaciones consignadas en este  instrumento, y a quien se considerar&aacute; para los fines de responsabilidad civil y  penal, como depositario judicial de los bienes correspondientes. Correr&aacute;n por  cuenta del ACREDITADO los gastos necesarios para la debida conservaci&oacute;n,  reparaci&oacute;n y uso del bien. Para efectos del dep&oacute;sito de la garant&iacute;a prendaria,  las partes convienen que el lugar en donde se ubicar&aacute;n los bienes ser&aacute; el  domicilio del ACREDITADO se&ntilde;alado en este documento. Las partes acuerdan que el  CREA tendr&aacute; el derecho de exigir al ACREDITADO el pago de la totalidad de la  deuda objeto del presente de manera anticipada  al plazo convenido, en caso que los bienes objeto de la prenda se deterioren o  se pierdan.<br />
    Asimismo, las partes acuerdan que  el incumplimiento a cualquiera de las estipulaciones convenidas, ser&aacute; causa de  vencimiento anticipado del plazo del cr&eacute;dito y ser&aacute; exigible el pago total del  mismo y si CREA tuviese que instaurar alg&uacute;n procedimiento judicial para exigir  el cumplimiento de las obligaciones consignadas en este Contrato, determinar&aacute;  el ejercicio de las acciones, la v&iacute;a y forma para ello, incluyendo sin limitar  el procedimiento extrajudicial de ejecuci&oacute;n previsto en el presente Contrato.<br />
  &nbsp; En caso de incumplimiento  del ACREDITADO en el pago de dos o mas pagos semanales de conformidad con la tabla  de amortizaci&oacute;n, CREA y el ACREDITADO convienen en que a elecci&oacute;n del CREA se  ejecutar&aacute; la garant&iacute;a prendaria conforme a lo dispuesto por el Titulo Tercero  BIS, Capitulo 1 del C&oacute;digo de Comercio, para obtener el pago del cr&eacute;dito  vencido y la posesi&oacute;n de los bienes otorgados en prenda. Para efectos de lo  anterior, el CREA y el ACREDITADO convienen en que el valor de los bienes para  efectos de la ejecuci&oacute;n de los mismos, ser&aacute; el valor promedio comercial que  para los mismos determine el&nbsp; CREA,  considerando el valor de mercado de bienes similares. &nbsp; <br />
    Conforme el art&iacute;culo 1414 bis 1  del C&oacute;digo de Comercio, el procedimiento se inicia con el requerimiento formal  de entrega de la posesi&oacute;n de los bienes, que formule CREA&nbsp; al ACREDITADO. Una vez recibidos los bienes,  CREA se reserva el derecho de que en caso de mora se cobren al ACREDITADO los  gastos de cobranza administrativa, extrajudicial o judicial de los mismos.<br />
  &nbsp; Asimismo, para efectos de la ejecuci&oacute;n de los bienes, y en  t&eacute;rminos del art&iacute;culo 1414 bis 17 del C&oacute;digo de Comercio, se estar&aacute; a lo  siguiente: I. Cuando el valor de los bienes sea igual al monto del adeudo, se  transmitir&aacute; la propiedad de los mismos al CREA o a tercetos que paguen dicho  valor, con el cual quedar&aacute; liquidado totalmente el cr&eacute;dito respectivo; II. Cuando  el valor de los bienes sea menor al monto del adeudo se transmitir&aacute; la  propiedad del mismo a CREA o a terceros que paguen dicho valor y CREA se  reservar&aacute; las acciones que le correspondan contra el ACREDITADO por la  diferencia no cubierta, y III. Cuando el valor de los bienes sea mayor al monto  del adeudo, se transmitir&aacute; la propiedad del mismo al CREA o a terceros que  paguen dicho valor y una vez deducido el cr&eacute;dito, los intereses, los gastos  generados y dem&aacute;s accesorios que correspondan, y se entregar&aacute; al ACREDITADO el  remanente que resulte. &nbsp; La venta se notificar&aacute; al ACREDITADO en su  domicilio se&ntilde;alado en este documento, mediante escrito con acuse de recibo o  bien por correo certificado, a elecci&oacute;n del CREA: Se publicar&aacute; en un peri&oacute;dico  de la localidad con 5 (cinco) d&iacute;as h&aacute;biles de antelaci&oacute;n, un aviso de venta de  los bienes, en el que se se&ntilde;ale el lugar, d&iacute;a y hora en que se pretende  realizar la venta, as&iacute; como el precio de la venta, determinado conforme a lo  convenido entre las partes, tambi&eacute;n se indicar&aacute;n las fechas en que se  realizar&aacute;n, en su caso, las ofertas sucesivas de venta de los bienes. Cada  semana en la que no haya sido posible realizar la venta de los bienes, el valor  m&iacute;nimo de venta de los mismos, se reducir&aacute; en un 10% (diez por ciento), pudiendo  el ACREDITADO, a su elecci&oacute;n, obtener la propiedad plena de los mismos cuando  el precio de los bienes est&eacute; en alguno de los supuestos indicados bajo los  incisos I y III de esta cl&aacute;usula. CREA se obliga a restituir&nbsp; AL  ACREDITADO la posesi&oacute;n jur&iacute;dica de los bienes objeto de la garant&iacute;a, una vez  que EL ACREDITADO haya cumplido cabalmente con todas y cada una de las  obligaciones que adquiere con la firma del presente contrato.</p>
<p>&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEGUNDA.- <u>FALLECIMIENTO  DEL ACREDITADO</u></strong>.-  En caso de fallecimiento del acreditado, CREA har&aacute; la cancelaci&oacute;n del cr&eacute;dito  individual de este. Para lo anterior los familiares o derechohabientes del  mismo, tendr&aacute;n que presentar el acta de defunci&oacute;n del deudor, dentro de los  primeros 10 d&iacute;as h&aacute;biles posteriores a su muerte as&iacute; como identificaci&oacute;n  oficial del mismo, para que esta cancelaci&oacute;n pueda surtir efectos.&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA TERCERA.- <u>EL  ACREDITADO AUTORIZA A CREA &nbsp;A:</u></strong><br />
    <strong>a).-</strong> Proporcionar o recabar informaci&oacute;n  sobre operaciones crediticias y de otra naturaleza an&aacute;loga que haya celebrado  con CREA,&nbsp; y sociedades de informaci&oacute;n  crediticia autorizadas previamente. <br />
    <strong>b).-</strong> Permitir que el CREA por conducto de  cualquiera de sus representantes o persona que este designe, realice <strong>visitas a su domicilio para verificar el  desarrollo de su actividad empresarial as&iacute; como para efectos de cobranza.</strong></p>
  <p><strong>c).-</strong> Llamar o enviar mensajes a sus  domicilios o tel&eacute;fonos celulares, o de cualquier otra forma, y en cualquier  lugar para informar sobre los servicios que el CREA ofrece, as&iacute; como para  efectos de cobranza.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.- <u>SEGURO</u>.-</strong> EL ACREDITADO autoriza a CREA para  que contrate a su nombre un seguro individual de vida con METROPOLITANA  COMPA&Ntilde;IA DE SEGUROS S.A., la cual, en caso de fallecimiento del ACREDITADO&nbsp; &oacute; un familiar directo; esposa &oacute; hijos, pagar&aacute;  el monto total adeudado por el ACREDITADO, m&aacute;s una suma asegurada de $10,000.00  (diez mil pesos 00/100 M.N.), que ser&aacute; entregable al beneficiario indicado por  dicho acreditado. </p>
  <p>EL Seguro de vida no tiene costo alguno para el ACREDITADO.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>. <strong>DECIMA QUINTA.- <u>SERVICIO,  ATENCION&nbsp; AL CLIENTE Y REGULARIZACION</u></strong>.- Asimismo, de conformidad con lo  previsto por la Ley para la Transparencia y Ordenamiento de los Servicios  Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de  Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto  M&uacute;ltiple No Reguladas (la &ldquo;Ley de Transparencia&rdquo;), la PARTES aceptan los  siguientes t&eacute;rminos respecto del presente: &nbsp;<br />
    <strong>1.-</strong> De conformidad con el art&iacute;culo 23 de  la Ley de Transparencia, EL ACREDITRADO acepta realizar la consulta de su  estado de cuenta de manera electr&oacute;nica, mediante la p&aacute;gina  www.financieracrece.com; con el usuario __________________  y clave de acceso ____________misma que podr&aacute; cambiar si EL ACREDITADO  lo considera conveniente. Tambi&eacute;n acepta EL ACREDITADO que dicho estado de  cuenta puede tardar hasta cinco d&iacute;as h&aacute;biles en mostrar la informaci&oacute;n  referente a un pago &uacute; operaci&oacute;n. <br />
    <strong>2.-</strong>&nbsp;&nbsp;CREA manifiesta a EL  ACREDITADO que no requiere de autorizaci&oacute;n de la Secretar&iacute;a de Hacienda y  Cr&eacute;dito P&uacute;blico para la realizaci&oacute;n de las operaciones convenidas en &eacute;ste  Contrato, y tampoco se encuentra sujeta a la supervisi&oacute;n de la Comisi&oacute;n  Nacional de Bancaria y de Valores. &nbsp; <br />
    <strong>3.-</strong>&nbsp; CREA pone a disposici&oacute;n del  ACREDITADO una Unidad Especializada de Atenci&oacute;n al Cliente, para atender  cualquier duda &oacute; queja en el siguiente No. telef&oacute;nico 1993 3278, &oacute; para  atenci&oacute;n personalizada en el domicilio ubicado en Av. Revoluci&oacute;n&nbsp; 1909  Piso 9 Col. San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n, D.F. &nbsp; <br />
    <strong>4-.</strong>&nbsp;&nbsp;Asimismo, CREA hace del  conocimiento del ACREDITADO el n&uacute;mero telef&oacute;nico de atenci&oacute;n a usuarios de la  Comisi&oacute;n Nacional para la Protecci&oacute;n y&nbsp; Defensa de los Usuarios de  Servicios Financieros, para realizar cualquier queja: 5340 0999 &oacute; LADA sin  costo 01800 999 8080, mediante la p&aacute;gina www.condusef.gob.mx &oacute; mediante el  correo electr&oacute;nico opinion@condusef.gob.mx.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEXTA.- <u>TITULO  EJECUTIVO</u>.-</strong> el  presente contrato conjunto a la certificaci&oacute;n del contador de MICROFINANCIERA  CRECE S.A. DE C.V. SOFOM ENR., respecto del estado de cuenta del ACREDITADO,  ser&aacute; titulo ejecutivo, de conformidad con el articulo 68 de la ley de  instituciones de cr&eacute;dito, por lo que CREA quedara facultado en caso de  incumplimiento o vencimiento anticipado a demandar en la v&iacute;a ejecutiva  mercantil o por la v&iacute;a judicial que mas convenga. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>D&Eacute;CIMA SEPTIMA:- <u>MODIFICACIONES.-</u></strong> Ninguna  modificaci&oacute;n de t&eacute;rmino o condici&oacute;n alguna del presente Contrato, y ning&uacute;n  consentimiento, renuncia o dispensa en relaci&oacute;n con cualquiera de dichos  t&eacute;rminos o condiciones, tendr&aacute; efecto en caso alguno a menos que conste por  escrito y est&eacute; suscrito por las partes y a&uacute;n entonces dicha modificaci&oacute;n,  renuncia, dispensa o consentimiento s&oacute;lo tendr&aacute; efecto para el caso y fin  espec&iacute;ficos para el cual fue otorgado. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>D&Eacute;CIMA OCTAVA:- <u>LEGISLACI&Oacute;N  APLICABLE Y JURISDICCI&Oacute;N.-</u></strong>Para todo  lo relativo a la interpretaci&oacute;n, ejecuci&oacute;n y cumplimiento de este Convenio, las  Partes se someten a la legislaci&oacute;n aplicable y a la jurisdicci&oacute;n de los  Tribunales competentes &shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;&shy;_____________________,  renunciando expresamente a cualquier otro fuero que pueda corresponderles por  raz&oacute;n de sus domicilios presentes o futuros o por cualquier otra &iacute;ndole. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA NOVENA.- <u>PAGARE</u>.- </strong>EL ACREDITADO suscribir&aacute;  a la orden de MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR., un pagare por la  cantidad de $____________________(____), suma total del cr&eacute;dito otorgado, mas  un inter&eacute;s ordinario correspondiente a ___________________, as&iacute; como un inter&eacute;s  moratorio correspondiente a______________ diarios sobre cada uno de los  vencimientos que presen ten atraso calculando desde la fecha en que se debi&oacute; de  realizar el pago y hasta que se liquide el total del mismo., los cuales ser&aacute;n  pagaderos a la vista por lo que una vez liquidado el total del cr&eacute;dito  incluyendo intereses ordinarios, moratorios, as&iacute; como accesorios devengados  durante el mismo, el CREA har&aacute; la cancelaci&oacute;n de este y lo entregara a su  respectivo signatario o aval .&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
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
  POR  VALOR RECIBIDO, el Suscriptor <strong>___________________</strong>, por el presente  Pagar&eacute; promete pagar incondicionalmente a la orden del Beneficiario  MICROFINANCIERA CRECE S.A. DE C.V. SOFOM E.N.R. (&quot;CRECE&quot;), la suma  principal de <strong>$_____________________ (-_____________________ PESOS 00/100  M.N.-) </strong>, m&aacute;s intereses ordinarios a raz&oacute;n de una tasa de _______________%(-____________________________-)  semanal a partir de su fecha de suscripci&oacute;n, mismos que se pagar&aacute;n con el  principal, en fondos inmediatamente disponibles, mediante ____(- ___________________-)  amortizaciones de principal, e intereses ordinarios iguales y consecutivas,  todas ellas por la cantidad de $_________________ (-______________________________  PESOS 00/100 M.N.-) , mismas que vencer&aacute;n y ser&aacute;n pagaderas en las fechas que  se indica en la siguiente tabla, en el entendido de que si alguna de dichas  fechas no fuere un d&iacute;a h&aacute;bil, dicho pago se har&aacute; el d&iacute;a h&aacute;bil inmediato  anterior (cada una referida como una &quot;Fecha de pago&quot;). </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>TABLA:::&nbsp; </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Los  pagos de capital y sus accesorios deber&aacute;n de ser abonados por el suscriptor a  la tarjeta de cr&eacute;dito No. <strong>_________________________</strong> emitida por BANCO INBURSA, S.A., INSTITUCION  DE BANCA MULTIPLE, GRUPO FINANCIERO INBURSA.<br />
  Para el caso en que los  pagos de capital o intereses ordinarios no sean cubiertos en la fecha de  vencimiento, &eacute;ste pagar&eacute; causar&aacute; un Intereses moratorio correspondiente a la  cantidad de <strong>$10.00 (- DIEZ PESOS 00/100 M.N.-) </strong>diarios sobre cada uno de  los vencimientos que presenten atraso, calculado desde la fecha en la que debi&oacute;  de realizarse el pago y hasta la fecha de su pago total.</p>
  <p>CREA  tambi&eacute;n podr&aacute; dar por vencido de manera anticipada el pago de todo el cr&eacute;dito y  tendr&aacute; derecho a que se le pague la totalidad del capital adeudado m&aacute;s los  intereses moratorios que se hubiesen generado a la fecha que se de por vencido  y hasta la fecha que se realice el pago total del adeudo.<br />
Todas las cantidades  debidas conforme al presente Pagar&eacute; se har&aacute;n libres y sin deducci&oacute;n &oacute; retenci&oacute;n  alguna por raz&oacute;n de cualquier impuesto, derechos, recargos &oacute; cargos de  cualquier naturaleza que se establezcan, impongan o cobren las autoridades en  M&eacute;xico.<br />
<br />
El Suscriptor renuncia a  toda diligencia, protesto, presentaci&oacute;n o aviso de intenci&oacute;n, de anticipaci&oacute;n,  de Fecha de pago, de no pago o incumplimiento, o cualquier aviso de cualquier  otro tipo respecto a este Pagar&eacute;. La falta de ejercicio por parte de CREA de  cualquiera de los derechos bajo este Pagar&eacute; en cualquier caso concreto no  constituir&aacute; renuncia al mismo en dicho caso o en cualquier caso subsecuente.<br />
<br />
El Suscriptor conviene  en extender el plazo de presentaci&oacute;n del presente Pagar&eacute; para efectos del  Art&iacute;culo 128 de la Ley General de T&iacute;tulos y operaciones de Cr&eacute;dito por un plazo  de 365 (trescientos sesenta y cinco) d&iacute;as naturales contados a partir de la &uacute;ltima  fecha de pago.</p>
  <p>Para la interpretaci&oacute;n, ejecuci&oacute;n  y cumplimiento de este Pagar&eacute;, as&iacute; como para cualquier otro requerimiento  judicial de pago bajo el mismo, el Suscriptor se somete expresamente a la  jurisdicci&oacute;n y competencia de los tribunales de ______________________________  , y renuncia expresamente a cualquier otro fuero que por raz&oacute;n de su domicilio  presente o futuro &uacute; otra causa pudiera corresponderle.<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;_________________ de __________________ del  a&ntilde;o _________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>
  <div align="center">
    <table border="0" cellspacing="0" cellpadding="0" width="373">
      <tr>
        <td><p align="center">&nbsp;</p>
          <p align="center">EL    SUSCRIPTOR</p></td>
        <td><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p></td>
        <td width="5"><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td><p>���������������������������������� _______________________________</p></td>
        <td><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>
  <p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>ENTERADOS DEL CONTENIDO Y AL ALCANCE JURIDICO DE LAS  OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES CON LA CELEBRACION DE ESTE  CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN, MANIFESTANDO QUE TIENEN  CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE ADQUIEREN  SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI COMO LOS  CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR SU MOTIVO  DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O GASTOS  DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA  CIUDAD&nbsp; DE ___________________________ EL  DIA _______ DE_____________</p></td>
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