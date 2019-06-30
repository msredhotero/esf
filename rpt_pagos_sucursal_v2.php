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
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// cambiamos el sql por sucursal
$sSql = "SELECT * FROM sucursal  ";
$rsSuc = phpmkr_query($sSql,$conn)or die ("Error al seleccionar las sucursales". phpmkr_error()."sql:".$sSql);
#$sDbWhere = "(vencimiento.vencimiento_status_id in (2,5)) AND (recibo.recibo_status_id = 1) AND ";


if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
$sWhere = "";
if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}

// se agrega  al condicion del jeuridico

$sWhere =  $sWhere .$sDbWhere_juridico;

if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
//SetUpSortOrder();
if ($sOrderBy != "") {
//	$sSql .= " ORDER BY " . $sOrderBy;
	#$sSql .= " ORDER BY recibo.fecha_pago ";
}
//$sSql .= " ORDER BY credito.credito_num+0, vencimiento.vencimiento_num ";
#$sSql .= " ORDER BY recibo.fecha_pago desc";
#echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<p>
  <script type="text/javascript" src="jscalendar/calendar.js"></script>
  <script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
  <script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
  <script type="text/javascript" src="ew.js"></script>
  <?php } ?>
  
  <script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
  <?php if ($sExport == "") { ?>
  <script type="text/javascript" src="utilerias/datefunc.js"></script>
  <?php } ?>
  
  <?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
while($rowSql = phpmkr_fetch_array($rs)){
	// por cada sucursal hacemos el proceso de calculara los pagos generados
	$x_sucursal_id =  $rowSql["sucursal_id"];
	$x_nombre_sucursal = $rowSql["nombre"];
	$x_lista_promotores = "";
	$sqlPromotores =  "SELECT * FROM promotor WHERE sucursal_id = $x_sucursal_id ";
	$rsPromotor =  phpmkr_query($sqlPromotores,$conn)or die ("Error al seleccionar los promotores".phpmkr_error()."sql:".$sqlPromotores);
	while($rowPromotores =  phpmkr_fetch_array($rsPromotor)){
		$x_promotor_id =  $rowPromotores["promotor_id"];
		$x_lista_promotores .= $x_promotor_id.", "; 
		}//while promotores
	$x_lista_promotores = trim($x_lista_promotores,", ");
	
	//seleccionamos los pagos registrados de esos promotores
	// Build SQL
		$sSqlPagos = "SELECT  SUM(vencimiento.importe) as impvenc, SUM(vencimiento.interes) AS interes, SUM(vencimiento.iva) AS iva, SUM(vencimiento.interes_moratorio) AS moratorio, SUM(vencimiento.iva_mor) AS iva_mora, SUM(vencimiento.total_venc) AS total_vencimiento
		FROM vencimiento join credito 
		on credito.credito_id = vencimiento.credito_id join recibo_vencimiento
		on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo
		on recibo.recibo_id = recibo_vencimiento.recibo_id join solicitud on solicitud.solicitud_id = credito.solicitud_id  join promotor on promotor.promotor_id = solicitud.promotor_id WHERE promotor.promotor_id in ($x_lista_promotores) AND (vencimiento.vencimiento_status_id in (2,5)) AND (recibo.recibo_status_id = 1) AND (recibo.fecha_pago >= \"2013-06-01\") AND (recibo.fecha_pago <= \"2013-06-30\")";
		$rsPagos = phpmkr_query($sSqlPagos,$conn) or die ("Error al seleccionar s pagos".phpmkr_error()."sql:".$sSqlPagos);	
		$rowPago = phpmkr_fetch_array($rsPagos);
	#	echo "<BR>".$sSqlPagos."<br>";
		//print_r($rowPago);
		$x_importe =  $rowPago["impvenc"];
		$x_interes = $rowPago["interes"];
		$x_iva = $rowPago["iva"];
		$x_moratorio = $rowPago["moratorio"];
		$x_iva_mora = $rowPago["iva_mora"];
		$x_total_vencimiento = $rowPago["total_vencimiento"];
	#	echo "<BR>IMPORTE ".$x_importe."  INTERES ".$x_interes." IVA ".$x_iva." MORA ".$x_moratorio." IVA_MORA ".$x_iva_mora." TOTAL_VENCIMIENTO ".$x_total_vencimiento."<BR>";
		
	$x_tabla = "<table width=\"50%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
      <td colspan=\"9\" height=\"15px\" style=\"background-image:url(images/images/t2.jpg); background-repeat:repeat-x; color:#FFFFFF\"><h3><center><strong> $x_nombre_sucursal</strong></center></h3></td>
    </tr>
    <tr>
      <td>CAPITAL</td>
      <td>INTERES</td>
      <td>IVA</td>
      <td>MORATORIO</td>
      <td>IVA_MORATORIO</td>
      <td>TOTAL_VENCIMIENTO</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> $".FormatNumber($x_importe,2,0,0,1)."</td>
      <td> $".FormatNumber($x_interes,2,0,0,1)."</td>
      <td> $".FormatNumber($x_iva,2,0,0,1)."</td>
      <td> $".FormatNumber($x_moratorio,2,0,0,1)."</td>
      <td> $".FormatNumber($x_iva_mora,2,0,0,1)."</td>
      <td> $".FormatNumber($x_total_vencimiento,2,0,0,1)."</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table><br><br>";
	echo $x_tabla;
	}
?>
</p>
  <table width="50%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="26" colspan="9">d</td>
    </tr>
    <tr>
      <td>IMPORTE</td>
      <td>INTERES</td>
      <td>IVA</td>
      <td>MORATORIO</td>
      <td>IVA_MORATORIO</td>
      <td>TOTAL_VENCIMIENTO</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  