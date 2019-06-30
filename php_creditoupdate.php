<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

// Initialize common variables
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_status_id = Null; 
$ox_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_num_pagos = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

	
// Get action
$sAction = @$_POST["a_add"];
if ($sAction != "") {
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
	$sSql = "SELECT credito_id FROM credito";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while($row = phpmkr_fetch_array($rs)){
		$x_credito_id = $row["credito_id"];

		$sSql = "SELECT count(*) as venc_no FROM vencimiento where credito_id = $x_credito_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$x_num_pagos = $row2["venc_no"];
		phpmkr_free_result($rs2);				
		
		$sSql = "update credito set num_pagos = $x_num_pagos where credito_id = $x_credito_id";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	}
	$_SESSION["ewmsg"] = "Fin de proceso";
	phpmkr_free_result($rs);		
	phpmkr_db_close($conn);
	
}



?>
<html>
<head>
<title>Actualiza DB</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function solicitud_data(){
	EW_this = document.creditoadd;
	EW_this.a_add.value = "S";
	EW_this.submit();
}

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<form name="creditoadd" id="creditoadd" action="php_creditoupdate.php" method="post">
  <input type="hidden" name="a_add" value="A">
  
<p><span class="phpmaker">ACTUALIZA CREDITOS<br>
</span>

<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
</p>
  <p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<table class="ewTable">
<input type="submit" name="Actualiza" value="Actualiza" >
</form>
</body>
</html>
