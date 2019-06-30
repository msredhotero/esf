<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0

// User levels
define("ewAllowadd", 1, true);
define("ewAllowdelete", 2, true);
define("ewAllowedit", 4, true);
define("ewAllowview", 8, true);
define("ewAllowlist", 8, true);
define("ewAllowreport", 8, true);
define("ewAllowsearch", 8, true);
define("ewAllowadmin", 16, true);

if (@$_SESSION["crm_login_status"] <> "logincrm2009") {
	
	if (@$_SESSION["php_project_esf_status"] <> "login") {
		header("Location:  login.php");
		exit();
	}
	
}

$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=fotos.xls');
}
?>
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_cliente_id = $_GET["x_cliente_id"];
if(empty($x_cliente_id)){
	$x_cliente_id = $_POST["x_cliente_id"];
	if(empty($x_cliente_id)){
		echo "No se ha especificado el cliente";
		exit();
	}
}


$sSqlWrk = "SELECT nombre_completo, apellido_paterno, apellido_materno FROM cliente  where cliente_id = $x_cliente_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_cliente = $rowwrk["nombre_completo"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];
}else{
	$x_cliente = "Cliente no Localizado.";
}
@phpmkr_free_result($rswrk);

// Build SQL
$sSql = "SELECT * FROM fotografia WHERE (cliente_id = $x_cliente_id)";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  CREA Technologies</title>
<link href="../crm.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript" src="galeria.js"></script>

<div align="center">
<p><span class="phpmaker">FOTOGRAFIAS DEL CLIENTE:&nbsp;<strong><?php echo $x_cliente; ?></strong><br />
  <br />
</span></p>
<br />
<br />
<p align="center">
<input type="button" name="x_titular" id="x_titular" value="Titular" onclick="galeria(<?php echo $x_cliente_id; ?>,1)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_domicilio" id="x_domicilio" value="Domicilio Particular" onclick="galeria(<?php echo $x_cliente_id; ?>,2)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_negocio" id="x_negocio" value="Negocio" onclick="galeria(<?php echo $x_cliente_id; ?>,3)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_garantia" id="x_garantia" value="Garantia" onclick="galeria(<?php echo $x_cliente_id; ?>,4)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_tit_comp_dom" id="x_tit_comp_dom" value="Titular Comp Domicilio" onclick="galeria(<?php echo $x_cliente_id; ?>,5)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_factura_f" id="x_factura_f" value="Factura frente" onclick="galeria(<?php echo $x_cliente_id; ?>,6)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_factura_t" id="x_factura_t" value="Factura reverso" onclick="galeria(<?php echo $x_cliente_id; ?>,7)"  />
<br /><br />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_aval" id="x_aval" value="Aval" onclick="galeria(<?php echo $x_cliente_id; ?>,8)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_aval_domicilio" id="x_aval_domicilio" value="Aval Domicilio" onclick="galeria(<?php echo $x_cliente_id; ?>,9)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_aval_comp_dom" id="x_aval_comp_dom" value="Aval Comp Domicilio" onclick="galeria(<?php echo $x_cliente_id; ?>,10)"  />
&nbsp;&nbsp;&nbsp;
<input type="button" name="x_aval_negocio" id="x_aval_negocio" value="Aval Negocio" onclick="galeria(<?php echo $x_cliente_id; ?>,11)"  />
</p>
<br />
<div id="marco_galeria">

</div>
</body>
</html>
<?php

