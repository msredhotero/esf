<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php

// IP compartido
$msg = "COMPARTIDO=>" .$_SERVER['HTTP_CLIENT_IP'] ." PROXY ==>".$_SERVER['HTTP_X_FORWARDED_FOR']."===>"." ACCESO".$_SERVER['REMOTE_ADDR'];

$today = date("Y-m-d");
$from = "test@financieracrea.com";
    $to = "zuoran_17@hotmail.com";
    $subject = "Checking PHP mail";
    $message = "PHP mail works just fine ".$today."<BR>".$msg;
    $headers = "From:" . $from;
    mail($to,$subject,$message, $headers);

###################################################################
###################################################################
####################  CIERRA PERIODOS MENSUALES   #################
###################################################################
###################################################################


// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);


$x_cierra_periodo_mensual = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));


$sqlPeriodo = "INSERT INTO `cierre_periodo` (`cierre_periodo_id`, `fecha`) VALUES (NULL, \"$x_cierra_periodo_mensual\")";
$rsP = phpmkr_query($sqlPeriodo,$conn) or die ("Error al inserta cierre");