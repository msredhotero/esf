<?php

session_start();
ob_start();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

define("ewAllowadd", 1, true);
define("ewAllowdelete", 2, true);
define("ewAllowedit", 4, true);
define("ewAllowview", 8, true);
define("ewAllowlist", 8, true);
define("ewAllowreport", 8, true);
define("ewAllowsearch", 8, true);                                                                                                                       
define("ewAllowadmin", 16, true);

include ("db.php");
include ("phpmkrfn.php");

function FechaLetras($x_fecha){
    $date_time_array = getdate($x_fecha);
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($day) {
        case 1:
            $x_dia = " primero ";
            break;
        case 2:
            $x_dia = " dos ";
            break;
        case 3:
            $x_dia = " tres ";
            break;
        case 4:
            $x_dia = " cuatro ";
            break;
        case 5:
            $x_dia = " cinco ";
            break;
        case 6:
            $x_dia = " seis ";
            break;
        case 7:
            $x_dia = " siete ";
            break;
        case 8:
            $x_dia = " ocho ";
            break;
        case 9:
            $x_dia = " nueve ";
            break;
        case 10:
            $x_dia = " diez ";
            break;
        case 11:
            $x_dia = " once ";
            break;
        case 12:
            $x_dia = " doce ";
            break;
        case 13:
            $x_dia = " trece ";
            break;
        case 14:
            $x_dia = " catorce ";
            break;
        case 15:
            $x_dia = " quince ";
            break;
        case 16:
            $x_dia = " diecis&eacute;is ";
            break;
        case 17:
            $x_dia = " diecisiete ";
            break;          
        case 18:
            $x_dia = " dieciocho ";
            break;
        case 19:
            $x_dia = " diecinueve ";
            break;
        case 20:
            $x_dia = " veinte ";
            break;
        case 21:
            $x_dia = " veintiuno ";
            break;
        case 22:
            $x_dia = " veintid&oacute;s ";
            break;
        case 23:
            $x_dia = " veintitr&eacute;s ";
            break;
        case 24:
            $x_dia = " veinticuatro ";
            break;
        case 25:
            $x_dia = " veinticinco ";
            break;
        case 26:
            $x_dia = " veintis&eacute;is ";
            break;
        case 27:
            $x_dia = " veintisiete ";
            break;
        case 28:
            $x_dia = " veintiocho ";
            break;
        case 29:
            $x_dia = " veintinueve ";
            break;
        case 30:
            $x_dia = " treinta ";
            break;
        case 31:
            $x_dia = " treintaiuno ";
            break;
    }

    switch ($month) {
        case 1:
            $x_mes = " enero ";
            break;
        case 2:
            $x_mes = " febrero ";
            break;
        case 3:
            $x_mes = " marzo ";
            break;
        case 4:
            $x_mes = " abril ";
            break;
        case 5:
            $x_mes = " mayo ";
            break;
        case 6:
            $x_mes = " junio ";
            break;
        case 7:
            $x_mes = " julio ";
            break;
        case 8:
            $x_mes = " agosto ";
            break;
        case 9:
            $x_mes = " septiembre ";
            break;
        case 10:
            $x_mes = " octubre ";
            break;
        case 11:
            $x_mes = " noviembre ";
            break;
        case 12:
            $x_mes = " diciembre ";
            break;
    }

    $fecha_letras = $x_dia." de ".$x_mes." del a&ntilde;o ".$year;
    
    return $fecha_letras;
}

function covertirNumLetrasSimple($number)
{


//number = number_format (number, 2);
    $number1=$number;


    //settype (number, "integer");
    $cent = strpos($number1,".");

    if($cent > 0){
        $centavos = substr($number1,$cent+1,2);
    }else{

        $centavos = "00";
    }

}

function covertirNumLetras($number)
{


    //number = number_format (number, 2);
    $number1=$number;


    //settype (number, "integer");
    $cent = strpos($number1,".");

    if($cent > 0){

        $centavos = substr($number1,$cent+1,2);

    }else{

        $centavos = "00";

    }
}


if (@$_SESSION["php_project_esf_status"] <> "login") {
    header("Location:  login.php");
    exit();
}

$nStartRec = 0;
$nRecActual = 0;
$credito_respaldo_id = 0;
$dias = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$sListTrJs = 0;
$x_filas_tabla_2 = 0;



$x_solicitud_id = @$_GET["solicitud_id"];

if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
    ob_end_clean(); 
    echo "NO se localizaron los datos.";
    exit();
}

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$sSql = "SELECT 
            * 
        FROM solicitud 
        where solicitud_id = ".$x_solicitud_id;

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);

$x_solicitud_id = $row["solicitud_id"];
$x_credito_tipo_id = $row["credito_tipo_id"];
$x_solicitud_status_id = $row["solicitud_status_id"];
$x_folio = $row["folio"];
$x_fecha_registro = $row["fecha_registro"];
$x_promotor_id = $row["promotor_id"];
$x_importe_solicitado = $row["importe_solicitado"];
$x_plazo_id = $row["plazo_id"];
$x_forma_pago_id = $row["forma_pago_id"];
$x_contrato = $row["contrato"];
$x_pagare = $row["pagare"];

phpmkr_free_result($rs);

$sSql = "SELECT 
            cliente.*
        FROM
            cliente
                JOIN
            solicitud_cliente ON solicitud_cliente.cliente_id = cliente.cliente_id
        WHERE
            solicitud_cliente.solicitud_id = ".$x_solicitud_id;

        $rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
        $row2 = phpmkr_fetch_array($rs2);
        
        $x_nombre_completo      = $row2["nombre_completo"];
        $x_apellido_paterno     = $row2["apellido_materno"];
        $x_apellido_materno     = $row2["apellido_materno"];

$x_cliente = strtoupper(htmlentities($x_nombre_completo))." ".strtoupper(htmlentities($x_apellido_paterno))." ".strtoupper(htmlentities($x_apellido_materno));

phpmkr_free_result($rs2);

$sSql = "SELECT 
            c.credito_num,
            c.fecha_otrogamiento,
            c.credito_id
        FROM
            credito c
        WHERE
            c.solicitud_id = ".$x_solicitud_id;

        $rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
        $row = phpmkr_fetch_array($rs);

        $x_credito_num          =   $row["credito_num"];
        $x_fecha_otorgamiento   =   $row["fecha_otrogamiento"];
        $x_credito_id           =   $row["credito_id"];

phpmkr_free_result($rs);


$sSql = "SELECT 
            *
        FROM
            vencimiento
        WHERE
            vencimiento.credito_id = ".$x_credito_id." 
        ORDER BY vencimiento.vencimiento_num";
//echo "sql vencimeinto".$sSql."<br>";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$x_tabla = "
<table class='ewTable' align='center'>
<tr>
<td valign='top'><span>
<b>Número de pago o vencimiento</b>
</span></td>
<td valign='top'><span>
<b>Fecha de Pago</b>
</span></td>       
<td valign='top'><span>
<b>Saldo Insoluto de Capital</b>
</span></td>                
<td valign='top'><span>
<b>Capital pagado en cada vencimiento</b>
</span></td>                        
<td valign='top'><span>
<b>Interés ordinario pagado en cada vencimiento</b>
</span></td>                        
<td valign='top'><span>
<b>IVA pagado en cada vencimiento</b>
</span></td>                        
<td valign='top'><span>
<b>Pago Total de cada vencimiento</b>
</span></td>                        
</tr>";

$x_saldo = $x_importe_solicitado;
$nRecCount = 0;
$x_cont_fetc_v = 1;//variable para la tabla 3 integrentes de grupo
while ($row = @phpmkr_fetch_array($rs)) {
    
    
    $nRecCount = $nRecCount + 1;
    $GLOBALS["x_total_numero_vencimeintos"]= $nRecCount;
    if ($nRecCount >= $nStartRec) {
        $nRecActual++;

        // Set row color
        $sItemRowClass = " class=\"ewTableRow\"";

        // Display alternate color for rows
        if ($nRecCount % 2 <> 1) {
            $sItemRowClass = " class=\"ewTableAltRow\"";
        }
    
        //selccion de los valores de tabla de vencimientos
        $x_vencimiento_id = $row["vencimiento_id"];
        $x_vencimiento_num = $row["vencimiento_num"];       
        //$x_credito_id = $row["credito_respaldo_id"];
        $x_vencimiento_status_id = $row["vencimiento_status_id"];


        
        $x_fecha_vencimiento_tab = $row["fecha_vencimiento"];
        $GLOBALS["x_fecha_vencimeinto_$nRecCount"] = $row["fecha_vencimiento"];
        $x_importe_tab = $row["importe"];
        $x_interes_tab = $row["interes"];
        $x_iva_tab = $row["iva"];   
        
        // varible para la tabla 3 integrantes del grupo
        //$x_cont_fetc_v = 1;
        
        //valores para la tabla 3
        
        $x_fecha_vto= $x_fecha_vencimiento_tab; // tu sabrás como la obtienes, solo asegurate que tenga este formato
        //$dias= 2; // los días a restar
        $x_fecha_vto = ConvertDateToMysqlFormat($x_fecha_vencimiento_tab);
        
        $sqlDIAMENOS = "SELECT DATE_SUB('$x_fecha_vto', INTERVAL 1 DAY )as fecha";
        $resposDM = phpmkr_query($sqlDIAMENOS,$conn) or die("error en dia menos".phpmkr_error()."sql:".$sqlDIAMENOS);
        
        $rowDM = phpmkr_fetch_array($resposDM);
        $x_fecha_new_1_day =  $rowDM["fecha"];
        //echo  $x_fecha_new_1_day;
         date("Y-m-d", strtotime("$x_fecha_vto -$dias day")); 
        $GLOBALS["x_fech_venci_$x_cont_fetc_v"] = $x_fecha_new_1_day;
        $x_cont_fetc_v++;
        ///fin varible tabla 3
        
        if(empty($x_iva_tab)){
            $x_iva_tab = 0;
        }
        $x_interes_moratorio_tab = $row["interes_moratorio"];
        
        $x_total = $x_importe_tab + $x_interes_tab + $x_interes_moratorio_tab + $x_iva_tab;
        $GLOBALS["x_monto_total_pago"]= $x_total;
        //echo"el pago es de:".$x_total;
        $x_total_pagos = $x_total_pagos + $x_importe_tab;
        $x_total_interes = $x_total_interes + $x_interes_tab;
        $x_total_iva = $x_total_iva + $x_iva_tab;       
        $x_total_moratorios = $x_total_moratorios + $x_interes_moratorio_tab;
        $x_total_total = $x_total_total + $x_total;
        
        if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
        
            $sSqlWrk = "SELECT fecha_pago FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
            $rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
            $rowwrk = phpmkr_fetch_array($rswrk);
            $x_fecha_pago = $rowwrk["fecha_pago"];

            @phpmkr_free_result($rswrk);

        }else{
            $x_fecha_pago  = "";

            $x_total_pagos_d = $x_total_pagos_d + $x_importe;
            $x_total_interes_d = $x_total_interes_d + $x_interes;
            $x_total_interes_d = $x_total_interes_d + $x_iva;           
            $x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
            $x_total_total_d = $x_total_total_d + $x_total;
            
        }

$x_tabla .= "
<tr $sItemRowClass $sListTrJs>
<td align='right'><span>
$x_vencimiento_num
</span></td>
<td align='center'><span>".FormatDateTime($x_fecha_vencimiento_tab,7)."
</span></td>
<td align='right'><span>".FormatNumber($x_saldo,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_importe_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_interes_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_iva_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_total,2,0,0,1)."
</span></td>
</tr>";



$x_saldo = $x_saldo - $x_importe_tab;



//genero las filas de la tabla numero dos
$x_filas_tabla_2 .= "<tr>
    <td> $x_fecha_vencimiento_tab </td>";
    if(!empty($x_nombre_integrante_1)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_2)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_3)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_4)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_5)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_6)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_7)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_8)){ $x_filas_tabla_2 .= "  <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_9)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_10)){ $x_filas_tabla_2 .= "  <td>&nbsp;</td>";}
   $x_filas_tabla_2 .=" <td> $x_total </td>
  </tr> ";
  $x_int_g = 1;
    while($x_int_g<=10){




    $x_int_g++;
    }//fin while integrantes
     
    
}
}


$x_tabla .= "
<tr>
<td>&nbsp;</td>
<td align='center'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
<b>".FormatNumber($x_total_total,2,0,0,1)."</b></span></td></tr></table>";
phpmkr_free_result($rs);

$x_numpagos = $nRecCount;
$x_numpagos = $x_numpagos."(-".covertirNumLetrasSimple($x_numpagos)."-)";
$x_importevenc = $x_total;
$x_importevenc_letras = covertirNumLetras($x_importevenc);
$x_importevenc = "$".FormatNumber($x_importevenc,2,0,0,1)." (-$x_importevenc_letras-) ";





?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<p>
<font size="2">

<p>
<table style="border-collapse: collapse;" align="center" border="0" cellpadding="0" cellspacing="0" width="800">
    <tbody>
        <tr>
            <td>&nbsp;</td>
            <td>
            <p align="center"><span style="font-size: 14px;"><span style="font-weight: bold; font-family: Arial;">TABLA DE AMORTIZACION</span></span></p>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="font-size: x-small;">
                    <p align="justify">
                        <span style="font-family: Arial;">
                            Nombre del cliente: <?php echo $x_cliente; ?>
                        </span>
                    </p>
                </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="font-size: x-small;">
                    <p align="justify">
                        <span style="font-family: Arial;">
                            Número de Crédito: <?php echo $x_credito_num; ?>
                        </span>
                    </p>
                </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="font-size: x-small;">
                    <p align="justify">
                        <span style="font-family: Arial;">
                            Fecha de Elaboración: <?php echo $x_fecha_otorgamiento; ?>
                        </span>
                    </p>
                </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
             <td> </td>
             <td valign="top"><span style="font-size: x-small;"><?php echo $x_tabla; ?></span></td>
             <td> </td>
         </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td valign="top">
            <p align="justify"><span style="font-size: x-small;"><span style="font-family: Arial;">Esta tabla de amortización detalla el calendario de pagos así como el desglose por concepto de pago en cada uno de los vencimientos. La presente tabla forma parte del contrato de crédito simple con interés vía descuento de nómina elaborada el <?php echo FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_otorgamiento))); ?>, entre el cliente <?php echo $x_cliente; ?> y Microfinanciera Crece, SA de CV SOFOM ENR.</span></span></p>
            </td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td>&nbsp;</td>
            <td>
            <table style="width: 300px; border-collapse: collapse;" align="center">
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>_______________________________</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align: center;">
                            <span style="font-size: x-small;">
                                <span style="font-family: Arial;">
                                    FIRMA DEL ACREDITADO
                                </span>
                            </span>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            </td>
            <td>&nbsp;</td>
        </tr>
       
    </tbody>
</table>
</p>	  
</font>
</p>

</body>
</html>