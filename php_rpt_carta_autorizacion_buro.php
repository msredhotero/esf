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


if (@$_SESSION["php_project_esf_status"] <> "login") {
    header("Location:  login.php");
    exit();
}


$x_solicitud_id = @$_GET["solicitud_id"];

if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
    ob_end_clean(); 
    echo "NO se localizaron los datos.";
    exit();
}

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

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
        $x_apellido_paterno     = $row2["apellido_paterno"];
        $x_apellido_materno     = $row2["apellido_materno"];

phpmkr_free_result($rs2);

$x_cliente = strtoupper(htmlentities($x_nombre_completo))." ".strtoupper(htmlentities($x_apellido_paterno))." ".strtoupper(htmlentities($x_apellido_materno));

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
            <td colspan="3"><p align="right" style="font-size: 12px;"><b>RECA: 1735-450-031981/01-03620-0819</b></p></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
            <p align="center"><span style="font-size: x-small;"><span style="font-weight: bold; font-family: Arial;">Carta autorizaci&oacute;n de bur&oacute; de cr&eacute;dito</span></span></p>
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
            <td>&nbsp;</td>
            <td valign="top">
            <p align="justify"><span style="font-size: x-small;"><span style="font-family: Arial;"><b>EL ACREDITADO</b> firma la presente carta autorización de buró de crédito, manifestando al efecto que:</span></span></p>
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
            <td valign="top">
            <p align="justify"><span style="font-size: x-small;"><span style="font-family: Arial;">Autorizo a Microfinanciera Crece S.A. de C.V., SOFOM E.N.R. para que lleve a cabo las consultas que considere necesarias sobre mi comportamiento e historial crediticio, así como cualquier otra información de naturaleza análoga con cualquier Sociedad de Información Crediticia autorizada, en el entendido que en este acto manifiesto que tengo pleno conocimiento (i) de la naturaleza y alcance de la información que la Sociedad de Información Crediticia de que se trate proporcionará a Microfinanciera Crece S.A. de C.V., SOFOM E.N.R., (ii) del uso que Microfinanciera Crece S.A. de C.V., SOFOM E.N.R. harán de dicha información, y (iii) de que Microfinanciera Crece S.A. de C.V., SOFOM E.N.R. podrá realizar consultas periódicas cuantas veces considere necesarias durante todo el tiempo en que mantengamos una relación jurídica. La presente autorización tendrá una vigencia durante el tiempo en que perdure la relación jurídica entre el suscrito y Microfinanciera Crece S.A. de C.V., SOFOM E.N.R., pero nunca será menor a tres años contados a partir de la fecha en que se expide.</span></span></p>
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
                        <td style="text-align: center;"><span style="font-size: x-small;"><b>EL ACREDITADO</b></span></td>
                        <td>&nbsp;</td>
                    </tr>
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
                                    <b><?php echo $x_cliente; ?></b>
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