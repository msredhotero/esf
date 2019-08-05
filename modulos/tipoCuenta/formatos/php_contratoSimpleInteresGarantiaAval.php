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
<td width="4">&nbsp;</td>
<td colspan="2" ><p>CONTRATO DE CREDITO SIMPLE CON INTERES QUE CELEBRAN POR UNA  PARTE <strong>MICROFINANCIERA CRECE S.A. DE C.V.  SOFOM ENR.</strong>, A QUIEN EN LO SUCECIVO SE LE DENOMINARA <strong>“CREA”</strong> Y POR OTRA PARTE, CON EL C.__________________ Y EL  C_________________ EN SU CALIDAD DE <strong>AVAL</strong> QUE EN LO SUCESIVO SE LES DENOMINARA COMO EL <strong>“ACREDITADO”</strong> QUE EN SU CONJUNTO SE LES DENOMINARA COMO <strong>“LAS PARTES”</strong>, QUIENES DE CONFORMIDAD SE   HAN MANIFESTADO SU PLENA VOLUNTAD EN  SUJETARSE AL TENOR DE LAS SIGUIENTES:</p></td>
<td width="10" >&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>DECLARACIONES</strong>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>A).-EL ACREDITADO DECLARA LO SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.- </strong>Ser personas físicas mayor de edad,  con nacionalidad Mexicana, con domicilio  en_____________________________________ y que cuenta con la capacidad legal  para la celebración del presente contrato.<br />
    <strong>2.-</strong><strong> </strong> Que es su  voluntad celebrar con <strong>CREA </strong>el presente Contrato, a efecto obtener en  concepto de préstamo con interés la cantidad señalada en la cláusula Primera y  de garantizar el puntual pago de la cantidad adeudada, así como de los  intereses y demás accesorios legales que se llegaran a generar a favor de <strong>CREA </strong>y a su cargo. <br />
&nbsp;<strong>3.-</strong> Que presta servicios personales independientes consistentes en: ­­­­­­­­­­­­­­­­­­________________  situación que acredita con los documentos que se agregan al (y forman parte  del) presente contrato como <strong>anexo1. </strong> <br />
<strong>4.- </strong> Que destinará el préstamo a que se refiere  este contrato a la adquisición de bienes de inversión, o bien para la  adquisición de las materias primas y materiales y en el pago de los jornales,  salarios y gastos directos de explotación indispensables para los fines de su  empresa.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;<strong>B).-</strong> <strong>DECLARA EL AVALISTA</strong> <strong>LO SIGUIENTE</strong>: &nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.-</strong> Es persona física, con plena capacidad jurídica, de nacionalidad  mexicana, con domicilio, para todos los efectos del presente Contrato, el  ubicado en ­­­­­­­­­­­­­­­­­­­­­­________________________ &nbsp;</p>
  <p> <strong>2.-</strong> Que es su voluntad celebrar el  presente Contrato, a efecto de actuar en su carácter de avalista del ACREEDOR y  de cumplir con las obligaciones previstas en el presente Contrato. &nbsp; <br />
    <strong>3.- </strong> Que conoce personalmente a el ACREDITADO y  sabe y le consta que presta servicios personales independientes consistentes  en:­­­­_________________________ y que éste destinará los recursos obtenidos  por virtud de este contrato para los fines a que se refiere la declaración 4.<br />
    <strong>4.-</strong> Con la firma de este contrato se obligan  a realizar todas las gestiones necesarias para mantener su actividad económica,  que provean la subsistencia tanto personal como familiar,  para así mantener su capacidad de pago e  historial crediticio. <br />
    <strong>5.-</strong> Declaran bajo protesta de decir  verdad que la información y toda clase de documentos presentados para la firma  del presente contrato son de carácter lícito, y no carecen de validez alguna.</p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>C).- CREA DECLARA LO  SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.-</strong> Ser una persona moral de nacionalidad  Mexicana, legalmente constituida conforme a las leyes de la Republica Mexicana  en los términos de la escritura pública No. 122,166, de fecha 19 de abril de  2007, basada ante la fe del Notario Público No. 152 del Distrito Federal,  el Lic. Cecilio Gonzales Márquez.</p>
  <p><strong>2.-</strong> Que su representante legal,  el C. Javier Arturo Foncerrada Sánchez está debidamente facultado para celebrar  el presente contrato, según consta en la escritura pública  No. 122,166, de fecha 19 de abril de 2007,  basada ante la fe del Notario Público No. 152 del Distrito Federal,  el Lic. Cecilio Gonzales Márquez.,  manifestando bajo protesta de decir verdad que hasta la fecha no le han sido  revocados, ni limitadas sus facultades en forma alguna.<br />
    <strong>3.-</strong> Estar constituida en el domicilio de  Av. Revolución No. 1909 piso 2, colonia San Ángel, Delegación Álvaro Obregón en  México Distrito Federal. <br />
    <strong>4.-</strong> Que considerando las declaraciones  del ACREDITADO es de su voluntad otorgar un crédito simple con interés. <br />
    Expuestas las anteriores declaraciones, las partes que  suscriben el presente contrato manifiestan su voluntad de otorgar y sujetarse  al tenor de las siguientes clausulas.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>CLAUSULAS</strong>&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>&nbsp;</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>PRIMERA.-</strong> <strong><u>OBJETO  DEL CONTRATO</u>.-</strong> En este acto CREA otorga al ACREDITADO y el ACREDITADO  recibe, un crédito simple con interés por la cantidad de _________________(____________________),   los cuales son recibidos mediante el  cheque No.________________ emitido por_____________________.,  el ACREDITADO en este acto se da por recibido  la cantidad objeto del préstamo con interés objeto del presente contrato, y  salvo buen cobro el cheque correspondiente, otorga a CREA el recibo mas amplio  que en derecho proceda respecto de dicha cantidad. <br />
Así mismo el ACREDITADO está de acuerdo con el monto señalado  y se obligan a cumplir conforme a los términos y condiciones del presente  contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEGUNDA.-</strong> el ACREDITADO se compromete a  destinar el importe del crédito solicitado para la actividad económica  establecida por cada uno en la lista de integrantes que forma parte del  presente contrato. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>TERCERA.-</strong> <strong><u>LUGAR  Y FORMA DE PAGO</u>.-</strong> el ACREDITADO se obliga a pagar:</p>
  <p><strong>a.-</strong> La cantidad señalada en la clausula  primera, en un plazo de ____________ contando a partir de la firma del presente  contrato, se dividirá entre el numero citado de ______________ y cada  parcialidad se deberá de pagar dentro del primer día hábil siguiente a la  _________________ vencida liquidándose el importe correspondiente.<br />
    <strong>b.-</strong> Los pagos de capital y sus accesorios  deberán realizarse por el ACREDITADO en la tarjeta de crédito No.  ___________________ emitida por Banco Inbursa  S.A. Institución de Banca Múltiple, Grupo Financiero Inbursa.<br />
    <strong>c.-</strong> El impuesto al valor agregado (IVA) o  a cualquier otro impuesto que se encuentre a la fecha como vigente que en su  caso se genero por causación de interés o gastos. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>CUARTA.-</strong> <strong><u>INTERES  ORDINARIO</u>.- </strong> las partes acuerdan  que la cantidad de la clausula primera se le aplicara por concepto de interés ordinario  una tasa del _______(________) lo cual es equivalente a una tasa  _____________(________) anual.<br />
  Los intereses que se generen con motivo del presente  contrato, deberán ser pagados por el ACREDITADO dentro del dia hábil inmediato  siguiente a la semana transcurrida, y por la que se genero dicho interés,  mientras el saldo del capital señalado en la cláusula primera se encuentre  insoluto. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>QUINTA.- <strong><u>INTERES  MORATORIO</u>.-</strong> el incumplimiento del ACREDITADO a cualesquiera de las obligaciones  pactadas en el presente contrato, traerá como consecuencia, además del  vencimiento anticipado del crédito, el pago de un interés moratorio  correspondiente a la cantidad de _______________(______) ______ sobre cada uno  de los vencimientos que presenten atrasos, calculado desde la fecha en que  debió realizarse el pago y hasta la fecha de su pago total, los cuales deberán  ser cubiertos por el ACREDITADO dentro del día natural siguiente al que se  generen. Así mismo el incumplimiento del DEUDOR a cualesquiera de las  obligaciones pactadas en el presente contrato, dará lugar  a la   ejecución de la garantía.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Lo anterior  expuesto conforme a la siguiente tabla: ___________________________________________________</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEXTA.- <u>COMISIONES</u>.-</strong> se le informa al ACREDITADO que no se  cobrara ningún tipo de comisión, siendo únicamente los pagos descritos en la  tabla anterior los que se compromete a pagar mediante la firma del presente  contrato, con excepción de los cobros que se puedan efectuar por motivo de  cualesquiera retrasos en el pago de los vencimientos descritos en la clausula  quinta.  </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEPTIMA.- <u>PAGOS  ANTICIPADOS y DISPOSICIÓN DEL CREDITO</u>.-</strong> El ACREDITADO podrá realizar pagos anticipados que se  aplicaran a intereses cubiertos, al capital materia del presente contrato, sin  penalización alguna, asimismo, las partes acuerdan que, en caso de que el  ACREDITADO realicen el pago puntual en todas sus amortizaciones bajo el  presente, por cada pago por anticipado (con _____ días naturales de  anticipación) de la cantidad correspondiente a una amortización del monto de la  deuda, el ACREDITADO tendrán derecho a un descuento igual a______________ que  se hubiera generado de dicha amortización. <br />
  En caso de que El ACREDITADO no cobre el titulo de crédito al  que se  hace referencia en la clausula  primera, dentro del termino fijado, no los excluye, ni los libera de la  obligación de pagar las obligaciones que se han pactado como fijas e  irrenunciables<br />
  El crédito se entiende por otorgado a la vista de la firma  que calza al final de dicho contrato, por lo que El ACREDITADO, no podrá alegar  o reclamar para no disponer del crédito y deberán de cumplir con todas y cada  una de las obligaciones pactadas en el presente contrato es decir el pago total  del crédito, así como los intereses previamente establecidos.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>OCTAVA.- <u>PRELACION DE  LOS PAGOS</u>.-</strong> CREA  aplicara los pagos conforme al siguiente orden:<br />
    <strong>1.- </strong>PENALIZACIONE(S) E IVA DERIVADO(S)  CORREPSONDIENTE AL MONTO DE LAS MISMAS, SI EN SU CASO APLICA.<br />
    <strong>2.- </strong>INTERESE(S) ORDINARIO(S) E IVA  CORREPONDIENTE AL MONTO DE LOS MISMOS.<br />
    <strong>3.-</strong> CAPITAL<br />
En caso de que se inicie un procedimiento legal por  incumplimiento en contra del deudor los pagos que se realicen se aplacaran en  primer lugar al pago de gastos y costas del juicio y después se seguirá con el  orden estipulado anterior mente.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>NOVENA.- <u>CESION DE  DEUDA O CREDITO</u>.-</strong> El ACREDITADO no podrá ceder total o parcialmente los derechos que les otorga  el presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong><u>DECIMA.- CAUSALES DE  RESCICION Y/O VENCIMIENTO.-</u></strong> ambas partes aceptan que el presente contrato se dará por  rescindido y/o por vencido anticipadamente y la cantidad entregada en préstamo  será exigible en su totalidad mas sus accesorios pactados en este contrato, mas  los legales que correspondan en caso de los siguientes supuestos:<br />
    <strong>a).-</strong> Cuando El ACREDITADO no pague en  forma oportuna una o varias de las amortizaciones de principal o intereses  establecidas en el presente contrato.<br />
    <strong>b).-</strong> Si se comprobara la existencia de  falsedad en la información o documentación proporcionada por el ACREDITADO.<br />
    <strong>c).-</strong> Si se comprobara que el ACREDITADO  destinara el monto proporcionado a un fin distinto a lo solicitado, y/o algún  fin ilícito.</p>
  <strong>d).-</strong> si ocurre alguna  circunstancia extraordinaria que a Juicio de CREA, haga improbable que el ACREDITADO  no pueda cumplir con las obligaciones del presente contrato</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>:- <u>GARANTÍA.</u></strong>De conformidad con lo  previsto por la Ley General de Títulos y Operaciones de Crédito, con el objeto  de garantizar el pago total y oportuno en la fecha de vencimiento (ya sea en la  fecha de vencimiento acordada, en una fecha de vencimiento anticipado o por  cualquier otra razón) del principal e intereses del Crédito otorgados por  Acreedor al ACREDITADO, así como de cualquier otra cantidad de tiempo en tiempo  adeudada por el ACREDITADO a CREA bajo el Crédito, en este acto, El ACREDITADO  otorga a favor de CREA una prenda sin transmisión de posesión, en primer lugar  y grado de prelación, debidamente perfeccionada, sobre [, la cual se describe a  continuación: _______________________________________ De conformidad con lo  anterior, el ACREDITADO de manera simultánea a la celebración del presente  contrato, endosa en garantía a favor de CREA, el original de la factura que  acredita su propiedad sobre los bienes otorgados en prenda conforme al  presente.<br />
&nbsp; <br />
Asimismo, de  conformidad con lo previsto por los artículos 355 y 356 de la Ley General de  Títulos y Operaciones de Crédito, dichos bienes quedarán en posesión del  ACREDITADO con el carácter de depositario a titulo gratuito, nombramiento que  mediante la firma del presente Contrato acepta y protesta su leal y fiel  cumplimiento, el cual no podrá delegar durante la vigencia del crédito, ni ser  revocado en tanto se encuentre al corriente del cumplimiento de las  obligaciones consignadas en este instrumento, y a quien se considerará para los  fines de responsabilidad civil y penal, como depositario judicial de los bienes  correspondientes. Correrán por cuenta del ACREDITADO los gastos necesarios para  la debida conservación, reparación y uso del bien. Para efectos del depósito de  la garantía prendaria, las partes convienen que el lugar en donde se ubicarán  los bienes será el domicilio del ACREDITADO señalado en este documento. Las  partes acuerdan que el CREA tendrá el derecho de exigir al ACREDITADO el pago  de la totalidad de la deuda objeto del presente de manera anticipada al plazo  convenido, en caso que los bienes objeto de la prenda se deterioren o se  pierdan.<br />
Asimismo, las partes  acuerdan que el incumplimiento a cualquiera de las estipulaciones convenidas,  será causa de vencimiento anticipado del plazo del crédito y será exigible el  pago total del mismo y si CREA tuviese que instaurar algún procedimiento  judicial para exigir el cumplimiento de las obligaciones consignadas en este  Contrato, determinará el ejercicio de las acciones, la vía y forma para ello,  incluyendo sin limitar el procedimiento extrajudicial de ejecución previsto en  el presente Contrato.<br />
&nbsp; En caso de  incumplimiento del ACREDITADO en el pago de dos o mas pagos semanales de  conformidad con la tabla de amortización, CREA y el ACREDITADO convienen en que  a elección del CREA se ejecutará la garantía prendaria conforme a lo dispuesto  por el Titulo Tercero BIS, Capitulo 1 del Código de Comercio, para obtener el  pago del crédito vencido y la posesión de los bienes otorgados en prenda. Para  efectos de lo anterior, el CREA y el ACREDITADO convienen en que el valor de  los bienes para efectos de la ejecución de los mismos, será el valor promedio  comercial que para los mismos determine el   CREA, considerando el valor de mercado de bienes similares. &nbsp; <br />
Conforme el artículo  1414 bis 1 del Código de Comercio, el procedimiento se inicia con el  requerimiento formal de entrega de la posesión de los bienes, que formule  CREA  al ACREDITADO. Una vez recibidos  los bienes, CREA se reserva el derecho de que en caso de mora se cobren al  ACREDITADO los gastos de cobranza administrativa, extrajudicial o judicial de  los mismos.</p>
&nbsp; Asimismo, para efectos de la ejecución de los bienes, y en  términos del artículo 1414 bis 17 del Código de Comercio, se estará a lo  siguiente: I. Cuando el valor de los bienes sea igual al monto del adeudo, se  transmitirá la propiedad de los mismos al CREA o a tercetos que paguen dicho  valor, con el cual quedará liquidado totalmente el crédito respectivo; II.  Cuando el valor de los bienes sea menor al monto del adeudo se transmitirá la  propiedad del mismo a CREA o a terceros que paguen dicho valor y CREA se  reservará las acciones que le correspondan contra el ACREDITADO por la  diferencia no cubierta, y III. Cuando el valor de los bienes sea mayor al monto  del adeudo, se transmitirá la propiedad del mismo al CREA o a terceros que  paguen dicho valor y una vez deducido el crédito, los intereses, los gastos  generados y demás accesorios que correspondan, y se entregará al ACREDITADO el  remanente que resulte. &nbsp; La venta se notificará al ACREDITADO en su  domicilio señalado en este documento, mediante escrito con acuse de recibo o  bien por correo certificado, a elección del CREA: Se publicará en un periódico  de la localidad con 5 (cinco) días hábiles de antelación, un aviso de venta de  los bienes, en el que se señale el lugar, día y hora en que se pretende  realizar la venta, así como el precio de la venta, determinado  conforme a lo convenido entre las partes, también se indicarán las fechas en  que se realizarán, en su caso, las ofertas sucesivas de venta de los bienes.  Cada semana en la que no haya sido posible realizar la venta de los bienes, el  valor mínimo de venta de los mismos, se reducirá en un 10% (diez por ciento),  pudiendo el ACREDITADO, a su elección, obtener la propiedad plena de los mismos  cuando el precio de los bienes esté en alguno de los supuestos indicados bajo  los incisos I y III de esta cláusula. CREA se obliga a restituir&nbsp; AL  ACREDITADO la posesión jurídica de los bienes objeto de la garantía, una vez  que EL ACREDITADO haya cumplido cabalmente con todas y cada una de las  obligaciones que adquiere con la firma del presente contrato.</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>. <strong>DECIMA SEGUNDA.- <u>FALLECIMIENTO  DEL ACREDITADO</u></strong>.-  En caso de fallecimiento del acreditado, CREA hará la cancelación del crédito  individual de este. Para lo anterior los familiares o derechohabientes del  mismo, tendrán que presentar el acta de defunción del deudor, dentro de los  primeros 10 días hábiles posteriores a su muerte así como identificación  oficial del mismo, para que esta cancelación pueda surtir efectos.  </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA TERCERA.- <u>EL  ACREDITADO AUTORIZA A CREA  A:</u></strong></p>
  <p><strong>a).-</strong> Proporcionar o recabar información  sobre operaciones crediticias y de otra naturaleza análoga que haya celebrado  con CREA,  y sociedades de información  crediticia autorizadas previamente. <br />
    <strong>b).-</strong> Permitir que el CREA por conducto de  cualquiera de sus representantes o persona que este designe, realice <strong>visitas a su domicilio para verificar el  desarrollo de su actividad empresarial así como para efectos de cobranza.</strong><br />
    <strong>c).-</strong> Llamar o enviar mensajes a sus  domicilios o teléfonos celulares, o de cualquier otra forma, y en cualquier  lugar para informar sobre los servicios que el CREA ofrece, así como para  efectos de cobranza.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.- <u>SEGURO</u>.-</strong> EL ACREDITADO autoriza a CREA para  que contrate a su nombre un seguro individual de vida con METROPOLITANA  COMPAÑIA DE SEGUROS S.A., la cual, en caso de fallecimiento del ACREDITADO  ó un familiar directo; esposa ó hijos, pagará  el monto total adeudado por el ACREDITADO, más una suma asegurada de $10,000.00  (diez mil pesos 00/100 M.N.), que será entregable al beneficiario indicado por  dicho acreditado. </p>
  <p>EL Seguro de vida no tiene costo alguno para el  ACREDITADO</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA QUINTA.- <u>SERVICIO,  ATENCION  AL CLIENTE Y REGULARIZACION</u></strong>.- Asimismo, de conformidad con lo  previsto por la Ley para la Transparencia y Ordenamiento de los Servicios  Financieros Aplicables a los Contratos de Adhesión, Publicidad, Estados de  Cuenta y Comprobantes de Operación de las Sociedades Financieras de Objeto  Múltiple No Reguladas (la “Ley de Transparencia”), la PARTES aceptan los  siguientes términos respecto del presente: &nbsp;<br />
  <strong>1.-</strong> De conformidad con el artículo 23 de  la Ley de Transparencia, EL ACREDITRADO acepta realizar la consulta de su  estado de cuenta de manera electrónica, mediante la página  www.financieracrece.com; con el usuario __________________  y clave de acceso ____________misma que podrá cambiar si EL ACREDITADO  lo considera conveniente. También acepta EL ACREDITADO que dicho estado de  cuenta puede tardar hasta cinco días hábiles en mostrar la información  referente a un pago ú operación. <br />
  <strong>2.-</strong>&nbsp;&nbsp;CREA manifiesta a EL  ACREDITADO que no requiere de autorización de la Secretaría de Hacienda y  Crédito Público para la realización de las operaciones convenidas en éste  Contrato, y tampoco se encuentra sujeta a la supervisión de la Comisión  Nacional de Bancaria y de Valores. &nbsp; <br />
  <strong>3.-</strong>&nbsp; CREA pone a disposición del  ACREDITADO una Unidad Especializada de Atención al Cliente, para atender  cualquier duda ó queja en el siguiente No. telefónico 1993 3278, ó para  atención personalizada en el domicilio ubicado en Av. Revolución&nbsp; 1909  Piso 9 Col. San Ángel, Delegación Álvaro Obregón, D.F. &nbsp; </p>
  <p><strong>4-.</strong>&nbsp;&nbsp;Asimismo, CREA hace del  conocimiento del ACREDITADO el número telefónico de atención a usuarios de la  Comisión Nacional para la Protección y&nbsp; Defensa de los Usuarios de  Servicios Financieros, para realizar cualquier queja: 5340 0999 ó LADA sin  costo 01800 999 8080, mediante la página www.condusef.gob.mx ó mediante el  correo electrónico opinion@condusef.gob.mx.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEXTA.- <u>TITULO  EJECUTIVO</u>.-</strong> el  presente contrato conjunto a la certificación del contador de MICROFINANCIERA  CRECE S.A. DE C.V. SOFOM ENR., respecto del estado de cuenta del ACREDITADO,  será titulo ejecutivo, de conformidad con el articulo 68 de la ley de  instituciones de crédito, por lo que CREA quedara facultado en caso de  incumplimiento o vencimiento anticipado a demandar en la vía ejecutiva  mercantil o por la vía judicial que mas convenga. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DÉCIMA SEPTIMA:- <u>MODIFICACIONES.-</u></strong> Ninguna  modificación de término o condición alguna del presente Contrato, y ningún  consentimiento, renuncia o dispensa en relación con cualquiera de dichos  términos o condiciones, tendrá efecto en caso alguno a menos que conste por  escrito y esté suscrito por las partes y aún entonces dicha modificación,  renuncia, dispensa o consentimiento sólo tendrá efecto para el caso y fin  específicos para el cual fue otorgado. &nbsp; </p><p align="center">&nbsp;</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DÉCIMA OCTAVA:- <u>LEGISLACIÓN  APLICABLE Y JURISDICCIÓN.-</u></strong>Para todo  lo relativo a la interpretación, ejecución y cumplimiento de este Convenio, las  Partes se someten a la legislación aplicable y a la jurisdicción de los  Tribunales competentes ­­­­­­­­­­­­­­­­­­­­­­­­­_____________________,  renunciando expresamente a cualquier otro fuero que pueda corresponderles por  razón de sus domicilios presentes o futuros o por cualquier otra índole. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA NOVENA.- <u>PAGARE</u>.- </strong>EL ACREDITADO y su  AVAL suscribirán a la orden de MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR.,  un pagare por la cantidad de $____________________(____), suma total del  crédito otorgado, mas un interés ordinario correspondiente a  ___________________, asi como un interés moratorio correspondiente  a______________ diarios sobre cada uno de los vencimientos que presen ten  atraso calculando desde la fecha en que se debió de realizar el pago y hasta  que se liquide el total del mismo., los cuales serán pagaderos a la vista por  lo que una vez liquidado el total del crédito incluyendo intereses ordinarios,  moratorios, así como accesorios devengados durante el mismo, el CREA hará la  cancelación de este y lo entregara a su respectivo signatario o aval .  </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
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
  POR  VALOR RECIBIDO, el Suscriptor <strong>___________________</strong>, por el presente  Pagaré promete pagar incondicionalmente a la orden del Beneficiario  MICROFINANCIERA CRECE S.A. DE C.V. SOFOM E.N.R. (&quot;CRECE&quot;), la suma  principal de <strong>$_____________________ (-_____________________ PESOS 00/100  M.N.-) </strong>, más intereses ordinarios a razón de una tasa de _______________%(-____________________________-)  semanal a partir de su fecha de suscripción, mismos que se pagarán con el  principal, en fondos inmediatamente disponibles, mediante ____(-  ___________________-) amortizaciones de principal, e intereses ordinarios  iguales y consecutivas, todas ellas por la cantidad de $_________________ (-______________________________  PESOS 00/100 M.N.-) , mismas que vencerán y serán pagaderas en las fechas que  se indica en la siguiente tabla, en el entendido de que si alguna de dichas  fechas no fuere un día hábil, dicho pago se hará el día hábil inmediato  anterior (cada una referida como una &quot;Fecha de pago&quot;). </p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Los  pagos de capital y sus accesorios deberán de ser abonados por el suscriptor a  la tarjeta de crédito No. <strong>_________________________</strong> emitida por BANCO INBURSA, S.A., INSTITUCION  DE BANCA MULTIPLE, GRUPO FINANCIERO INBURSA.<br />
  Para el caso en que los  pagos de capital o intereses ordinarios no sean cubiertos en la fecha de  vencimiento, éste pagaré causará un Intereses moratorio correspondiente a la  cantidad de <strong>$10.00 (- DIEZ PESOS 00/100 M.N.-) </strong>diarios sobre cada uno de  los vencimientos que presenten atraso, calculado desde la fecha en la que debió  de realizarse el pago y hasta la fecha de su pago total.</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>CRECE  también podrá dar por vencido de manera anticipada el pago de todo el crédito y  tendrá derecho a que se le pague la totalidad del capital adeudado más los  intereses moratorios que se hubiesen generado a la fecha que se de por vencido  y hasta la fecha que se realice el pago total del adeudo.<br />
  Todas las cantidades  debidas conforme al presente Pagaré se harán libres y sin deducción ó retención  alguna por razón de cualquier impuesto, derechos, recargos ó cargos de  cualquier naturaleza que se establezcan, impongan o cobren las autoridades en  México.</p></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="2"><p>El  Suscriptor renuncia a toda diligencia, protesto, presentación o aviso de  intención, de anticipación, de Fecha de pago, de no pago o incumplimiento, o  cualquier aviso de cualquier otro tipo respecto a este Pagaré. La falta de  ejercicio por parte de CRECE de cualquiera de los derechos bajo este Pagaré en  cualquier caso concreto no constituirá renuncia al mismo en dicho caso o en  cualquier caso subsecuente.<br />
    <br />
El Suscriptor conviene  en extender el plazo de presentación del presente Pagaré para efectos del  Artículo 128 de la Ley General de Títulos y operaciones de Crédito por un plazo  de 365 (trescientos sesenta y cinco) días naturales contados a partir de la  última fecha de pago.<br />
<br />
Para la interpretación, ejecución y cumplimiento de este Pagaré, así como para  cualquier otro requerimiento judicial de pago bajo el mismo, el Suscriptor se  somete expresamente a la jurisdicción y competencia de los tribunales de ______________________________  , y renuncia expresamente a cualquier otro fuero que por razón de su domicilio  presente o futuro ú otra causa pudiera corresponderle.<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;_________________ de __________________ del  año _________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>
  <div align="center">
    <table border="0" cellspacing="0" cellpadding="0" width="272">
      <tr>
        <td><br />
          EL    SUSCRIPTOR </td>
        <td><p>&nbsp;</p></td>
        <td><p align="center">POR AVAL </p></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p></td>
        <td width="50"><p>&nbsp;</p></td>
        <td><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td><p>_______________________________</p></td>
        <td><p>&nbsp;</p></td>
        <td><p>______________________________</p></td>
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
<td colspan="2"><p>ENTERADOS DEL CONTENIDO Y AL ALCANCE JURIDICO DE LAS  OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES CON LA CELEBRACION DE ESTE  CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN, MANIFESTANDO QUE TIENEN  CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE ADQUIEREN  SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI COMO LOS  CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR SU MOTIVO  DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O GASTOS  DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA CIUDAD  DE ___________________________ EL DIA _______  DE_____________</p></td>
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