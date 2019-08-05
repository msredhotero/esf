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

<?php require("conversor.php")?>
<?php include("../../../db.php");?>
<?php include("../../../phpmkrfn.php");?>


<?php
include("../../../datefunc.php");

// Get key
$x_credito_id = @$_GET["credito_id"];
$x_solicitud_id = @$_GET["solicitud_id"];
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
/*if(!empty($x_credito_id)){
	$sqlSol = "SELECT  solicitud_id FROM credito WHERE credito_id =  $x_credito_id ";
	$resposeSol = phpmkr_query($sqlSol, $conn) or die("error en credito".phpmkr_error()."sql:".$sqlSol);
	$rowSol = phpmkr_fetch_array($responseSol);
	$x_solicitud_id = $rowSol["solicitud_id"];
	phpmkr_free_result($rowSol);
	
	}else{
		$x_solicitud_id = 0;
		}*/
if (($x_credito_id == "") || ((is_null($x_credito_id)))) {
	//ob_end_clean();
	//echo "No se localizo el credito";
	//exit();
}





$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData2($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			echo "No se localizo la solicitudvvv";
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
<td colspan="2" >CONTRATO DE CREDITO SIMPLE CON INTERES QUE CELEBRAN POR UNA PARTE MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR., A QUIEN EN LO SUCECIVO SE LE DENOMINARA &ldquo;CREA&rdquo; Y POR OTRA PARTE, LA EMPRESA DENOMINADA   &quot; <?php echo htmlspecialchars(@$x_giro_negocio) ?> &quot;, A QUIEN AHORA Y EN LO SUCESIVO SE LES DENOMINARA COMO EL &ldquo;ACREDITADO&rdquo; QUE EN SU CONJUNTO SE LES DENOMINARA COMO &ldquo;LAS PARTES&rdquo;, QUIENES DE CONFORMIDAD SE  HAN MANIFESTADO SU PLENA VOLUNTAD EN SUJETARSE AL TENOR DE LAS SIGUIENTES:</td>
<td width="10" >&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>DECLARACIONES</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>A).-EL ACREDITADO DECLARA LO  SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.- </strong>Ser una persona moral de nacionalidad  mexicana, legalmente constituida conforme a las leyes de _______________________  en los t&eacute;rminos de la escritura p&uacute;blica _________________, de fecha ___ de _______  de ________, basada ante la fe del ____________ P&uacute;blico No. ________________,&nbsp; el Lic. ___________________.<br />
  <strong>2.-</strong> Que su representante legal, <?php echo $x_nombre_completo." ".$x_apellido_paterno." ".$x_apellido_materno ?> est&aacute; debidamente facultado para celebrar el presente contrato, seg&uacute;n consta en  la escritura p&uacute;blica&nbsp; No. _____________  de fecha _____ de ________ de ________, basada ante la fe del __________  P&uacute;blico No. _________ del _____________,&nbsp;  el Lic. ______________________., manifestando bajo protesta de decir  verdad que hasta la fecha no le han sido revocados, ni limitadas sus facultades  en forma alguna.<br />
  <strong>3.-</strong> Estar constituida en el domicilio de <?php echo  " calle ".htmlspecialchars(@$x_calle_negocio).", colonia ". htmlspecialchars(@$x_colonia_negocio)." ,delegacion/municipio ". htmlentities($x_delegacion_id2).", ". htmlentities($x_entidad_domicilio) ?>. <br />
  <strong>4.- </strong>Que son reales los datos asentados en  la solicitud de cr&eacute;dito, que forma parte de este contrato, en el que se  manifiesta el deseo de obtener el cr&eacute;dito objeto del presente contrato,  conforme a los t&eacute;rminos y condiciones estipuladas.<br />
  <strong>5.-</strong> Con la firma de este contrato se  obliga a realizar todas las gestiones necesarias para mantener su actividad  econ&oacute;mica, que provean la subsistencia tanto personal como familiar,&nbsp; para as&iacute; mantener su capacidad de pago e  historial crediticio. <br />
  <strong>5.-</strong> Declara bajo protesta de decir verdad  que la informaci&oacute;n y toda clase de documentos presentados para la firma del  presente contrato son de car&aacute;cter l&iacute;cito, y no carecen de validez alguna.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>B).- DECLARA EL ACREEDOR  LO SIGUIENTE:</strong></p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>1.-</strong> Ser una persona moral de nacionalidad  Mexicana, legalmente constituida conforme a las leyes de la Republica Mexicana  en los t&eacute;rminos de la escritura p&uacute;blica No. 122,166, de fecha 19 de abril de  2007, basada ante la fe del Notario P&uacute;blico No. 152 del Distrito Federal,&nbsp; el Lic. Cecilio Gonzales M&aacute;rquez.<br />
  <strong>2.-</strong> Que su representante legal, el C.  Javier Arturo Foncerrada S&aacute;nchez est&aacute; debidamente facultado para celebrar el  presente contrato, seg&uacute;n consta en la escritura p&uacute;blica&nbsp; No. 122,166, de fecha 19 de abril de 2007,  basada ante la fe del Notario P&uacute;blico No. 152 del Distrito Federal,&nbsp; el Lic. Cecilio Gonzales M&aacute;rquez.,  manifestando bajo protesta de decir verdad que hasta la fecha no le han sido  revocados, ni limitadas sus facultades en forma alguna.<br />
  <strong>3.-</strong> Estar constituida en el domicilio de  Av. Revoluci&oacute;n No. 1909 piso 2, colonia San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n en  M&eacute;xico Distrito Federal. <br />
  <strong>4.-</strong> Que considerando las declaraciones  del ACREDITADO, es de su voluntad otorgar un cr&eacute;dito simple solidario. <br />
  Expuestas las anteriores declaraciones, las partes que  suscriben el presente contrato manifiestan su voluntad de otorgar y sujetarse  al tenor de las siguientes clausulas.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p align="center"><strong>CLAUSULAS</strong></p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>PRIMERA.-</strong> <strong><u>OBJETO  DEL CONTRATO</u>.-</strong> En este acto CREA otorga al ACREDITADO y el ACREDITADO  recibe, un cr&eacute;dito simple con inter&eacute;s por la cantidad de <?php echo number_format($x_importe,2,',','');?>( <?php $texto = convertir($x_importe); echo $texto ?> ),  &nbsp;los cuales son recibidos mediante el  cheque No.________________ emitido por_____________________., &nbsp;el ACREDITADO en este acto se da por recibido  la cantidad objeto del pr&eacute;stamo con inter&eacute;s objeto del presente contrato, y  salvo buen cobro el cheque correspondiente, otorga a CREA el recibo mas amplio  que en derecho proceda respecto de dicha cantidad. <br />
  As&iacute; mismo, el ACREDITADO est&aacute; de acuerdo con el monto  se&ntilde;alado y se obligan a cumplir conforme a los t&eacute;rminos y condiciones del  presente contrato.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEGUNDA.-</strong> el ACREDITADO se compromete a  destinar el importe del cr&eacute;dito solicitado para la actividad econ&oacute;mica correspondiente. </p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>TERCERA.-</strong> <strong><u>LUGAR  Y FORMA DE PAGO</u>.-</strong> El ACREDITADO por conducto de su representante se  obliga a pagar:</p>
  <p><strong>a.-</strong> La cantidad se&ntilde;alada en la clausula  primera, en un plazo de <?php echo $x_plazo_id;?> pagos/<?php echo $x_forma_pago_id;?> contando a partir de la firma del  presente contrato, cada parcialidad se deber&aacute; de pagar dentro del primer d&iacute;a  h&aacute;bil siguiente a la __________________ vencida liquid&aacute;ndose el importe  correspondiente.<br />
    <strong>b.-</strong> Los pagos de capital y sus accesorios  deber&aacute;n realizarse por EL ACREDITADO en la tarjeta de cr&eacute;dito No.&nbsp; <?php echo $x_tarjeta_num;?> emitida por Banco Inbursa  S.A. Instituci&oacute;n de Banca M&uacute;ltiple, Grupo Financiero Inbursa.<br />
    <strong>c.-</strong> El impuesto al valor agregado (IVA) o  a cualquier otro impuesto que se encuentre a la fecha como vigente que en su  caso se genero por causaci&oacute;n de inter&eacute;s o gastos. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>CUARTA.-</strong> <strong><u>INTERES  ORDINARIO</u>.- </strong> las partes acuerdan  que la cantidad de la clausula primera se le aplicara por concepto de interés ordinario  una tasa del <?php echo $x_tasa; ?>(________) lo cual es equivalente a una tasa <?php echo "poner la tasa";?> _____________(________) anual.</p>
Los  intereses que se generen con motivo del presente contrato, deber&aacute;n ser pagados  por el ACREDITADO dentro del dia h&aacute;bil inmediato siguiente a la semana  transcurrida, y por la que se genero dicho inter&eacute;s, mientras el saldo del  capital se&ntilde;alado en la cl&aacute;usula primera se encuentre insoluto. </td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>QUINTA.-</strong> <strong><u>INTERES  MORATORIO</u>.-</strong> el incumplimiento del ACREDITADO a cualesquiera de las  obligaciones pactadas en el presente contrato, traer&aacute; como consecuencia, adem&aacute;s  del vencimiento anticipado del cr&eacute;dito, el pago de un inter&eacute;s moratorio  correspondiente a la cantidad de _______________(______) ______ sobre cada uno  de los vencimientos que presenten atrasos, calculado desde la fecha en que  debi&oacute; realizarse el pago y hasta la fecha de su pago total, los cuales deber&aacute;n  ser cubiertos por el ACREDITADO dentro del d&iacute;a natural siguiente al que se  generen. As&iacute; mismo el incumplimiento del ACREDITADO a cualesquiera de las  obligaciones pactadas en el presente contrato, dar&aacute; lugar&nbsp; a la&nbsp;  ejecuci&oacute;n de la garant&iacute;a.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Lo anterior  expuesto conforme a la siguiente tabla:</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEXTA.- <u>COMISIONES</u>.-</strong> se le informa al ACREDITADO que no se  cobrara ning&uacute;n tipo de comisi&oacute;n, siendo &uacute;nicamente los pagos descritos en la  tabla anterior los que se compromete a pagar mediante la firma del presente  contrato, con excepci&oacute;n de los cobros que se puedan efectuar por motivo de  cualesquiera retrasos en el pago de los vencimientos descritos en la clausula  quinta.&nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>SEPTIMA.- <u>PAGOS  ANTICIPADOS y DISPOSICI&Oacute;N DEL CREDITO</u>.-</strong> El ACREDITADO podr&aacute; realizar pagos anticipados que se  aplicaran a intereses cubiertos, al capital materia del presente contrato, sin  penalizaci&oacute;n alguna, asimismo, las partes acuerdan que, en caso de que el  ACREDITADO realicen el pago puntual en todas sus amortizaciones bajo el  presente, por cada pago por anticipado (con _____ d&iacute;as naturales de  anticipaci&oacute;n) de la cantidad correspondiente a una amortizaci&oacute;n del monto de la  deuda, el ACREDITADO tendr&aacute;n derecho a un descuento igual a______________ que  se hubiera generado de dicha amortizaci&oacute;n. </p>
  <p>En caso de que el ACREDITADO no cobren el titulo de cr&eacute;dito  al que se&nbsp; hace referencia en la clausula  primera, dentro del termino fijado, no lo excluye, ni lo libera de la  obligaci&oacute;n de pagar las obligaciones que se han pactado como fijas e  irrenunciables.<br />
    El cr&eacute;dito se entiende por otorgado a la vista de la firma  que calza al final de dicho contrato, por lo que EL ACREDITADO, no podr&aacute;n  alegar o reclamar para no disponer del cr&eacute;dito y deber&aacute;n de cumplir con todas y  cada una de las obligaciones pactadas en el presente contrato es decir el pago  total del cr&eacute;dito, as&iacute; como los intereses previamente establecidos.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>OCTAVA.- <u>PRELACION DE  LOS PAGOS</u>.-</strong> El  ACREEDOR aplicara los pagos conforme al siguiente orden:<br />
  <strong>1.- </strong>INTERESES MORATORIOS E IVA DERIVADO(S)  CORREPSONDIENTE AL MONTO DE LAS MISMAS, SI EN SU CASO APLICA.<br />
  <strong>2.- </strong>INTERESE(S) ORDINARIO(S) E IVA  CORREPONDIENTE AL MONTO DE LOS MISMOS.<br />
  <strong>3.-</strong> CAPITAL<br />
  En caso de que se inicie un procedimiento legal por  incumplimiento en contra de los deudores, los pagos que se realicen se aplicar&aacute;n  en primer lugar, al pago de gastos y costas del juicio y despu&eacute;s se seguir&aacute; con  el orden estipulado anterior mente.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>NOVENA.- <u>CESION DE  DEUDA O CREDITO</u>.-</strong> el ACREDITADO no podr&aacute; ceder total o parcialmente los derechos que les otorga  el presente contrato.</p></td>
<td>&nbsp;</td>
</tr><tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong><u>DECIMA.- CAUSALES DE  RESCICION Y/O VENCIMIENTO.-</u></strong> ambas partes aceptan que el presente contrato se dar&aacute; por  rescindido y/o por vencido anticipadamente y la cantidad entregada en pr&eacute;stamo  ser&aacute; exigible en su totalidad mas sus accesorios pactados en este contrato, mas  los legales que correspondan en caso de los siguientes supuestos:<br />
  <strong>a).-</strong> Cuando el ACREDITADO por conducto de su  representante legal no pague en forma oportuna una o varias de las  amortizaciones de principal o intereses establecidas en el presente contrato.<br />
  <strong>b).-</strong> Si se comprobara la existencia de  falsedad en la informaci&oacute;n o documentaci&oacute;n proporcionada por EL ACREDITADO.<br />
  <strong>c).-</strong> Si se comprobara que EL ACREDITADO destinara  el monto proporcionado a un fin distinto a lo solicitado, y/o alg&uacute;n fin il&iacute;cito.<br />
  <strong>d).- En caso de quiebra  o concurso mercantil.</strong><br />
  <strong>e).-</strong> si ocurre alguna circunstancia  extraordinaria que a Juicio de CREA, haga improbable que los clientes puedan  cumplir con las obligaciones del presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA  PRIMERA.- <u>GARANT&Iacute;A.</u></strong>De conformidad con lo previsto  por la Ley General de T&iacute;tulos y Operaciones de Cr&eacute;dito, con el objeto de  garantizar el pago total y oportuno en la fecha de vencimiento (ya sea en la  fecha de vencimiento acordada, en una fecha de vencimiento anticipado o por  cualquier otra raz&oacute;n) del principal e intereses del Cr&eacute;dito otorgados por  Acreedor al ACREDITADO, as&iacute; como de cualquier otra cantidad de tiempo en tiempo  adeudada por el ACREDITADO a CREA bajo el Cr&eacute;dito, en este acto, El ACREDITADO  otorga a favor de CREA una prenda sin transmisi&oacute;n de posesi&oacute;n, en primer lugar  y grado de prelaci&oacute;n, debidamente perfeccionada, sobre [, la cual se describe a  continuaci&oacute;n: <?php echo htmlentities($x_garantia_desc); ?> De conformidad con lo  anterior, el ACREDITADO de manera simult&aacute;nea a la celebraci&oacute;n del presente  contrato, endosa en garant&iacute;a a favor de CREA, el original de la factura que  acredita su propiedad sobre los bienes otorgados en prenda conforme al  presente.</p>
  <p>Asimismo, de conformidad con lo previsto por los  art&iacute;culos 355 y 356 de la Ley General de T&iacute;tulos y Operaciones de Cr&eacute;dito,  dichos bienes quedar&aacute;n en posesi&oacute;n del ACREDITADO con el car&aacute;cter de  depositario a titulo gratuito, nombramiento que mediante la firma del presente  Contrato acepta y protesta su leal y fiel cumplimiento, el cual no podr&aacute; delegar durante la vigencia  del cr&eacute;dito, ni ser revocado en tanto se encuentre al corriente del  cumplimiento de las obligaciones consignadas en este instrumento, y a quien se  considerar&aacute; para los fines de responsabilidad civil y penal, como depositario  judicial de los bienes correspondientes. Correr&aacute;n por cuenta del ACREDITADO los  gastos necesarios para la debida conservaci&oacute;n, reparaci&oacute;n y uso del bien. Para  efectos del dep&oacute;sito de la garant&iacute;a prendaria, las partes convienen que el  lugar en donde se ubicar&aacute;n los bienes ser&aacute; el domicilio del ACREDITADO se&ntilde;alado  en este documento. Las partes acuerdan que el CREA tendr&aacute; el derecho de exigir  al ACREDITADO el pago de la totalidad de la deuda objeto del presente de manera  anticipada al plazo convenido, en caso que los bienes objeto de la prenda se  deterioren o se pierdan.<br />
Asimismo, las partes acuerdan que  el incumplimiento a cualquiera de las estipulaciones convenidas, ser&aacute; causa de  vencimiento anticipado del plazo del cr&eacute;dito y ser&aacute; exigible el pago total del  mismo y si CREA tuviese que instaurar alg&uacute;n procedimiento judicial para exigir  el cumplimiento de las obligaciones consignadas en este Contrato, determinar&aacute;  el ejercicio de las acciones, la v&iacute;a y forma para ello, incluyendo sin limitar  el procedimiento extrajudicial de ejecuci&oacute;n previsto en el presente Contrato.<br />
&nbsp; En caso de incumplimiento  del ACREDITADO en el pago de dos o mas pagos semanales de conformidad con la  tabla de amortizaci&oacute;n, CREA y el ACREDITADO convienen en que a elecci&oacute;n del  CREA se ejecutar&aacute; la garant&iacute;a prendaria conforme a lo dispuesto por el Titulo  Tercero BIS, Capitulo 1 del C&oacute;digo de Comercio, para obtener el pago del  cr&eacute;dito vencido y la posesi&oacute;n de los bienes otorgados en prenda. Para efectos  de lo anterior, el CREA y el ACREDITADO convienen en que el valor de los bienes  para efectos de la ejecuci&oacute;n de los mismos, ser&aacute; el valor promedio comercial  que para los mismos determine el&nbsp; CREA,  considerando el valor de mercado de bienes similares. &nbsp; <br />
Conforme el art&iacute;culo 1414 bis 1  del C&oacute;digo de Comercio, el procedimiento se inicia con el requerimiento formal  de entrega de la posesi&oacute;n de los bienes, que formule CREA&nbsp; al ACREDITADO. Una vez recibidos los bienes,  CREA se reserva el derecho de que en caso de mora se cobren al ACREDITADO los  gastos de cobranza administrativa, extrajudicial o judicial de los mismos.<br />
&nbsp; Asimismo, para efectos de la ejecuci&oacute;n de los bienes, y en  t&eacute;rminos del art&iacute;culo 1414 bis 17 del C&oacute;digo de Comercio, se estar&aacute; a lo  siguiente: I. Cuando el valor de los bienes sea igual al monto del adeudo, se  transmitir&aacute; la propiedad de los mismos al CREA o a tercetos que paguen dicho  valor, con el cual quedar&aacute; liquidado totalmente el cr&eacute;dito respectivo; II.  Cuando el valor de los bienes sea menor al monto del adeudo se transmitir&aacute; la  propiedad del mismo a CREA o a terceros que paguen dicho valor y CREA se  reservar&aacute; las acciones que le correspondan contra el ACREDITADO por la  diferencia no cubierta, y III. Cuando el valor de los bienes sea mayor al monto  del adeudo, se transmitir&aacute; la propiedad del mismo al CREA o a terceros que  paguen dicho valor y una vez deducido el cr&eacute;dito, los intereses, los gastos  generados y dem&aacute;s accesorios que correspondan, y se entregar&aacute; al ACREDITADO el  remanente que resulte. &nbsp; La venta se notificar&aacute; al ACREDITADO en su  domicilio se&ntilde;alado en este documento, mediante escrito con acuse de recibo o  bien por correo certificado, a elecci&oacute;n del CREA: Se publicar&aacute; en un peri&oacute;dico  de la localidad con 5 (cinco) d&iacute;as h&aacute;biles de antelaci&oacute;n, un aviso de venta de  los bienes, en el que se se&ntilde;ale el lugar, d&iacute;a y hora en que se pretende  realizar la venta, as&iacute; como el precio de la venta, determinado conforme a lo  convenido entre las partes, tambi&eacute;n se indicar&aacute;n las fechas en que se  realizar&aacute;n, en su caso, las ofertas sucesivas de venta de los bienes. Cada  semana en la que no haya sido posible realizar la venta de los bienes, el valor  m&iacute;nimo de venta de los mismos, se reducir&aacute; en un 10% (diez por ciento),  pudiendo el ACREDITADO, a su elecci&oacute;n, obtener la propiedad plena de los mismos  cuando el precio de los bienes est&eacute; en alguno de los supuestos indicados bajo  los incisos I y III de esta cl&aacute;usula. CREA se obliga a restituir&nbsp; AL  ACREDITADO la posesi&oacute;n jur&iacute;dica de los bienes objeto de la garant&iacute;a, una vez  que EL ACREDITADO haya cumplido cabalmente con todas y cada una de las  obligaciones que adquiere con la firma del presente contrato.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA SEGUNDA.- <u>EL  CLIENTE AUTORIZA A CREA A:</u></strong><br />
  <strong>a).-</strong> Proporcionar o recabar informaci&oacute;n  sobre operaciones crediticias y de otra naturaleza an&aacute;loga que haya celebrado  con CREA,&nbsp; y sociedades de informaci&oacute;n  crediticia autorizadas previamente. <br />
  <strong>b).-</strong> Permitir que el CREA por conducto de  cualquiera de sus representantes o persona que este designe, realice <strong>visitas a su domicilio para verificar el  desarrollo de su actividad empresarial as&iacute; como para efectos de cobranza.</strong></p>
  <p><strong>c).-</strong> Llamar o enviar mensajes a sus  domicilios o tel&eacute;fonos celulares, o de cualquier otra forma, y en cualquier  lugar para informar sobre los servicios que el CREA ofrece, as&iacute; como para  efectos de cobranza.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA TERCERA.- <u>EN  CASO DE QUIEBRA O CONCURSO MERCANTIL:</u></strong><br />
  <strong>1.- EL ADREDITADO manifiesta  que CREA es un acreditado con garant&iacute;a real conforme a lo estipulado en el  art&iacute;culo 217, p&aacute;rrafo segundo del art&iacute;culo 219, 223, y dem&aacute;s relativos y  aplicables de la ley federal de concursos mercantiles, por lo que una vez  realizadas todas sus obligaciones con sus acreedores, el ACREDITADO dispondr&aacute; a  realizar el pago del saldo adeudado a CREA, dependiendo de los grados de  clasificaci&oacute;n instituidos en la ley. </strong></p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA CUARTA.- <u>SERVICIO,  ATENCION&nbsp; AL CLIENTE Y REGULARIZACION</u></strong>.- Asimismo, de conformidad con lo  previsto por la Ley para la Transparencia y Ordenamiento de los Servicios  Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de  Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto  M&uacute;ltiple No Reguladas (la &ldquo;Ley de Transparencia&rdquo;), LAS PARTES aceptan los  siguientes t&eacute;rminos respecto del presente: &nbsp;<br />
  <strong>1.-</strong> De conformidad con el art&iacute;culo 23 de  la Ley de Transparencia, EL ACREDITADO acepta realizar la consulta de su estado  de cuenta de manera electr&oacute;nica, mediante la p&aacute;gina www.financieracrece.com;  con el usuario <?php echo htmlentities($x_nombre_usuario);?> y  clave de acceso <?php echo htmlentities($x_password_usuario);?> misma que podr&aacute; cambiar si el ACREDITADO lo  consideran conveniente. Tambi&eacute;n acepta el ACREDITADO que dicho estado de cuenta  puede tardar hasta cinco d&iacute;as h&aacute;biles en mostrar la informaci&oacute;n referente a un  pago &uacute; operaci&oacute;n. <br />
  <strong>2.-</strong>&nbsp;&nbsp;CREA manifiesta al  ACREDITADO que no requiere de autorizaci&oacute;n de la Secretar&iacute;a de Hacienda y  Cr&eacute;dito P&uacute;blico para la realizaci&oacute;n de las operaciones convenidas en &eacute;ste  Contrato, y tampoco se encuentra sujeta a la supervisi&oacute;n de la Comisi&oacute;n  Nacional de Bancaria y de Valores. &nbsp; <br />
  <strong>3.-</strong>&nbsp; EL ACREEDOR pone a disposici&oacute;n  del ACREDITADO una Unidad Especializada de Atenci&oacute;n al Cliente, para atender  cualquier duda &oacute; queja en el siguiente No. telef&oacute;nico 1993 3278, &oacute; para  atenci&oacute;n personalizada en el domicilio ubicado en Av. Revoluci&oacute;n&nbsp; 1909  Piso 2 Col. San &Aacute;ngel, Delegaci&oacute;n &Aacute;lvaro Obreg&oacute;n, D.F. &nbsp; <br />
  <strong>4-.</strong>&nbsp;&nbsp;Asimismo, CREA hace del  conocimiento del ACREDITADO el n&uacute;mero telef&oacute;nico de atenci&oacute;n a usuarios de la  Comisi&oacute;n Nacional para la Protecci&oacute;n y&nbsp; Defensa de los Usuarios de  Servicios Financieros, para realizar cualquier queja: 5340 0999 &oacute; LADA sin  costo 01800 999 8080, mediante la p&aacute;gina www.condusef.gob.mx &oacute; mediante el  correo electr&oacute;nico opinion@condusef.gob.mx.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>DECIMA QUINTA.- <u>TITULO  EJECUTIVO</u>.-</strong> el  presente contrato conjunto a la certificaci&oacute;n del contador de MICROFINANCIERA  CRECE S.A. DE C.V. SOFOM ENR., respecto del estado de cuenta del ACREDITADO,  ser&aacute; titulo ejecutivo, de conformidad con el articulo 68 de la ley de  instituciones de cr&eacute;dito, por lo que CREA quedara facultado en caso de  incumplimiento o vencimiento anticipado a demandar en la v&iacute;a ejecutiva  mercantil o por la v&iacute;a judicial que mas le sea conveniente. </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>D&Eacute;CIMA SEXTA:- <u>MODIFICACIONES.-</u></strong> Ninguna  modificaci&oacute;n de t&eacute;rmino o condici&oacute;n alguna del presente Contrato, y ning&uacute;n  consentimiento, renuncia o dispensa en relaci&oacute;n con cualquiera de dichos  t&eacute;rminos o condiciones, tendr&aacute; efecto en caso alguno a menos que conste por  escrito y est&eacute; suscrito por las partes y a&uacute;n entonces dicha modificaci&oacute;n,  renuncia, dispensa o consentimiento s&oacute;lo tendr&aacute; efecto para el caso y fin  espec&iacute;ficos para el cual fue otorgado. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p><strong>D&Eacute;CIMA SEPTIMA:- <u>LEGISLACI&Oacute;N  APLICABLE Y JURISDICCI&Oacute;N.-</u></strong>Para todo  lo relativo a la interpretaci&oacute;n, ejecuci&oacute;n y cumplimiento de este Contrato, las  Partes se someten a la legislaci&oacute;n aplicable y a la jurisdicci&oacute;n de los  Tribunales competentes _________________________________,  renunciando expresamente a cualquier otro fuero que pueda corresponderles por  raz&oacute;n de sus domicilios presentes o futuros o por cualquier otra &iacute;ndole. &nbsp; </p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><strong>DECIMA  OCTAVA.- <u>PAGARE</u>.- </strong>EL ACREDITADO por conducto de su representante  legal suscribir&aacute; a la orden de MICROFINANCIERA CRECE S.A. DE C.V. SOFOM ENR.,  un pagare por la cantidad de $  <?php echo $x_importe;?>( <?php $texto = convertir($x_importe); echo $texto ?> ), suma total del  cr&eacute;dito otorgado, mas un inter&eacute;s ordinario correspondiente a ___________________,  asi como un inter&eacute;s moratorio correspondiente a______________ diarios sobre  cada uno de los vencimientos que presen ten atraso calculando desde la fecha en que se debi&oacute; de realizar el pago  y hasta que se liquide el total del mismo., los cuales ser&aacute;n pagaderos a la  vista por lo que una vez liquidado el total del cr&eacute;dito incluyendo intereses  ordinarios, moratorios, as&iacute; como accesorios devengados durante el mismo, el  CREA har&aacute; la cancelaci&oacute;n de este y lo entregara a su respectivo signatario o  aval .&nbsp; </td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"><p>Una vez le&iacute;do y enteradas las partes de su  contenido y alcance, suscriben el presente contrato de pr&eacute;stamo de conformidad,  el d&iacute;a dos de diciembre del a&ntilde;o 2010.</p></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="2">ENTERADOS DEL CONTENIDO Y AL ALCANCE JURIDICO DE LAS OBLIGACIONES QUE CONTRAEN LAS PARTES CONTRATANTES CON LA CELEBRACION DE ESTE CONTRATO DE ADHESION, LOS DEUDORES LOS SUSCRIBEN, MANIFESTANDO QUE TIENEN CONOCIMIENTO Y COMPRENDEN PLENAMENTE LA OBLIGACION QUE ADQUIEREN SOLIDARIAMENTE, ACEPTANDO EL MONTO DEL CREDITO QUE SE LE OTORGA, ASI COMO LOS CARGOS Y GASTOS QUE SE GENEREN O EN SU CASO SE LLEGARAN A GENERAR POR SU MOTIVO DE SU SUSCRIPCION, ENTENDIENDO TAMBIEN QUE NO SE EFECTUARAN CARGOS O GASTOS DISTINTOS A LOS ESPECIFICADOS POR LO QUE LO FIRMAN DE CONFORMIDAD EN LA CIUDAD  DE ___________________________ EL DIA _______ DE_____________</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<tr>


</table>




</body>
</html>

<?php
function LoadData2($conn){
	
	 global $x_credito_id;
	global $x_solicitud_id ;
	echo "entro a load data con una solitud".$x_solicitud_id."-";

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





		//CREDITO
		
		$sol_id = $GLOBALS["x_solicitud_id"];
		$sqlCredito = "SELECT * FROM credito where solicitud_id = $sol_id";
		$responseCredito = phpmkr_query($sqlCredito,$conn) or die ("Error al ejecutar query credito".phpmkr_error()."sql:". $sqlCredito);
		$rowCre = phpmkr_fetch_array($responseCredito);
		$GLOBALS["x_credito_id"]= $rowCre["credito_id"];
		$GLOBALS["x_credito_tipo_id"]= $rowCre["credito_tipo_id"];
		$GLOBALS["x_credito_status_id"]= $rowCre["credito_status_id"];
		$GLOBALS["x_credito_num"]= $rowCre["credito_num"];
		$GLOBALS["x_fecha_otrogamiento"]= $rowCre["fecha_otrogamiento"];
		$GLOBALS["x_importe"]= $rowCre["importe"];
		$GLOBALS["x_tasa"]= $rowCre["tasa"];
		$GLOBALS["x_plazo_id"]= $rowCre["plazo_id"];
		$GLOBALS["x_fecha_vencimiento"]= $rowCre["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"]= $rowCre["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"]= $rowCre["medio_pago_id"];
		$GLOBALS["x_referencia_pago"]= $rowCre["referencia_pago"];
		$GLOBALS["x_forma_pago_id"]= $rowCre["forma_pago_id"];
		$GLOBALS["x_num_pagos"]= $rowCre["num_pagos"];
		$GLOBALS["x_cliente_num"]= $rowCre["cliente_num"];
		$GLOBALS["x_tarjeta_num"]= $rowCre["tarjeta_num"];
		$GLOBALS["x_credito_id_ant"]= $rowCre["credito_id_ant"];
		$GLOBALS["x_banco_id"]= $rowCre["banco_id"];
		$GLOBALS["x_iva"]= $rowCre["iva"];
	
		
		
		
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
		$x_user_id = $row2["usuario_id"];
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
		
		
		$sqlUsuario = "SELECT * FROM usuario WHERE usuario_id = $x_user_id";
		$responseUser = phpmkr_query($sqlUsuario,$conn) or die("error al consultar usuario ".phpmkr_error()."sql: ". $sqlUsuario);
		$rowUser = phpmkr_fetch_array($responseUser);
		$GLOBALS["x_nombre_usuario"] = $rowUser["usuario"];
		$GLOBALS["x_password_usuario"] = $rowUser["clave"];
		
		
		
		
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
			$GLOBALS["x_renta_mensual"] = $row4["renta_mensual"]; //renta mensula esta...mas bien no esta guardada deberia estar en gastos
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

		//seleccion de gastos
		
		$sSQL = "SELECT * FROM gasto WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
		$rsg = phpmkr_query($sSQL,$conn) or die ("Error en gasto".phpmkr_error()."sql".$sSQL);
		$rowg = phpmkr_fetch_array($rsg);
		$GLOBALS["x_gasto_id"] = $rowg["gasto_id"];
		$GLOBALS["x_renta_mensual"]= $rowg["gastos_renta_negocio"]; //negocio
		$GLOBALS["x_renta_mensula_domicilio"]= $rowg["gastos_renta_casa"]; // casa
		
		
		
		//seleccion de formato PYME

		$sSql = "SELECT * FROM formatopyme WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."" ;
		
		
		//$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row8["otro_telefono_domicilio_2"];
		$GLOBALS["x_giro_negocio"] = $row8["giro_negocio"];
		$GLOBALS["x_propiedad_hipot"]= $row8["prop_hipotec"];
		$GLOBALS["x_antiguedad_ubicacion"]= $row8["antiguedad_ubicacion"];
		
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
	phpmkr_free_result($rowg);
	phpmkr_free_result($rowCre);
	phpmkr_free_result($rowUser);
	return $bLoadData;
	
	
	}
?>