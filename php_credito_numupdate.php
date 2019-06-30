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
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get action
$sAction = @$_POST["a_add"];
if ($sAction != "") {
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

		$sSql = "update credito set cliente_num = credito_num";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


	$sSql = "select solicitud_id, cliente_num from credito";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while($row = phpmkr_fetch_array($rs)){
		$x_solicitud_id = $row["solicitud_id"];
		$x_cliente_num = $row["cliente_num"];		

		$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$x_cliente_id = $row2["cliente_id"];
		phpmkr_free_result($rs2);				
		
		if(!empty($x_cliente_id)){
			$sSql = "update cliente set cliente_num = '".$x_cliente_num."' where cliente_id = $x_cliente_id";
			phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		}
		
	}

	$sSql = "update credito set credito_num = 0";
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	
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
<form name="creditoadd" id="creditoadd" action="php_credito_numupdate.php" method="post">
  <input type="hidden" name="a_add" value="A">
  
<p><span class="phpmaker">ACTUALIZA NUMEROS DE CREDITOS Y CLIENTES<br>
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
