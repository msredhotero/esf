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
<?php

// Initialize common variables
$x_recibo_id = Null; 
$ox_recibo_id = Null;
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_importe = Null; 
$ox_importe = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_recibo_id = @$_GET["recibo_id"];
if (($x_recibo_id == "") || ((is_null($x_recibo_id)))) {
	$x_recibo_id = $_POST["x_recibo_id"];
	if (($x_recibo_id == "") || ((is_null($x_recibo_id)))) {
		ob_end_clean(); 
		header("Location: php_recibolist.php"); 
		exit();
	}
}

//$x_recibo_id = (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;
// Get action

$sAction = @$_POST["x_action"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibolist.php");
			exit();
		}
		break;
	case "C":
	
		//ACTUALIZA VENCIMMENTOS
		$sSql = "select vencimiento_id from recibo_vencimiento where recibo_id = $x_recibo_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	
		while ($rowwrk = phpmkr_fetch_array($rswrk)){
			$x_vencimiento_id = $rowwrk["vencimiento_id"];

			$sSql = "update vencimiento set vencimiento_status_id = 1
			where vencimiento_id =  ".$x_vencimiento_id;
			phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		}
		
		phpmkr_free_result($rowwrk);
		
		$sSql = "update recibo set recibo_status_id = 2 where recibo_id = $x_recibo_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


		//VALIDA STATUS DEL CREDITO
		$sSqlWrk = "SELECT credito.credito_id, credito.credito_status_id FROM credito join vencimiento on vencimiento.credito_id = credito.credito_id join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where recibo.recibo_id = $x_recibo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_credito_id = $rowwrk["credito_id"];
		$x_credito_status_id = $rowwrk["credito_status_id"];		
		@phpmkr_free_result($rswrk);
		

		if($x_credito_status_id == 3){
			$sSql = "update credito set credito_status_id = 1
			where credito_id =  ".$x_credito_id;
			phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		}

		phpmkr_db_close($conn);
		ob_end_clean();
		header("Location: php_recibolist.php");
		exit();
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript">
<!--
function cancela(){
	x = confirm("Esta seguro que desea CANCELAR este pago.");
	if(x == true){
		document.frmcancela.x_action.value = "C";
		document.frmcancela.submit();
	}
}
//-->
</script>
<p><span class="phpmaker">Detalle de pago<br><br>
<a href="php_recibolist.php">Regresar a la lista</a>&nbsp;
</span></p>
<p>
<form name="frmcancela" method="post" action="php_recibo_cancelar.php">
<input type="hidden" name="x_recibo_id" value="<?php echo $x_recibo_id; ?>" />
<input type="hidden" name="x_action" value="0"  />
<table class="ewTable_small">
	<tr>
	  <td class="ewTableHeaderThin">Cr&eacute;dito No.</td>
	  <td class="ewTableAltRow">
<?php
$sSqlWrk = "SELECT credito.credito_num, credito.cliente_num FROM recibo join recibo_vencimiento 
on recibo.recibo_id = recibo_vencimiento.recibo_id join vencimiento 
on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id join credito
on credito.credito_id = vencimiento.credito_id
where recibo.recibo_id = $x_recibo_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_credito_num = $rowwrk["credito_num"];
	$x_cliente_num = $rowwrk["cliente_num"];	
}
@phpmkr_free_result($rswrk);

echo $x_credito_num; 
?>      </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Cliente No.</td>
	  <td class="ewTableAltRow">
      <?php
	  echo $x_cliente_num;
	  ?>      </td>
	  </tr>
	<tr>
		<td width="145" class="ewTableHeaderThin"><span>Recibo No</span></td>
		<td width="643" class="ewTableAltRow"><span>
<?php echo $x_recibo_id; ?>
</span></td>
	</tr>
	
	<tr>
	  <td class="ewTableHeaderThin">Banco - Cta.</td>
	  <td class="ewTableAltRow"><?php
if ((!is_null($x_banco_id)) && ($x_banco_id <> "")) {
	$sSqlWrk = "SELECT nombre, cuenta FROM banco";
	$sTmp = $x_banco_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE banco_id = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		echo $rowwrk["nombre"] . " - " . $rowwrk["cuenta"];
	}
	@phpmkr_free_result($rswrk);
} 
?></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Medio de pago</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `medio_pago`";
	$sTmp = $x_medio_pago_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `medio_pago_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_medio_pago_id = $x_medio_pago_id; // Backup Original Value
$x_medio_pago_id = $sTmp;
?>
<?php echo $x_medio_pago_id; ?>
<?php $x_medio_pago_id = $ox_medio_pago_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Referencia de pago</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_referencia_pago; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?>
</span></td>
	</tr>
	<tr>
	  <td colspan="2" class="ewTableHeaderThin">
      
	  <input type="button" name="x_cancelar" id="x_cancelar" value="Cancelar Pago" onclick="cancela()" />	  </td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableSelectRow" style="font-size: x-small; font-weight: bold">IMPORTANTE: <span style="font-style: italic">Al cancelar el pago, los vencimientos que este cubre, ser&aacute;n dejados como pendientes, y es posible que se vayan a cartera vencida. De igual manera si el cr&eacute;dito ya ha sido finiquitado lo volvera a Activar.</span></td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableHeaderThin"><div align="center">Vencimientos</div></td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableRow">
      
      
      
      
      
      
      <table id="ewlistmain" class="ewTable" align="center">
        <!-- Table header -->
        <tr class="ewTableHeader">
          <td valign="top"><span>
            No
          </span></td>
          <td valign="top"><span>
            Status
          </span></td>
          <td valign="top"><span>
            Fecha
          </span></td>
          <td valign="top"><span>
            Importe
          </span></td>
          <td valign="top"><span>
            Inter&eacute;s
          </span></td>
          <td valign="top"><span>
            Moratorio
          </span></td>
          <td valign="top"><span> Total </span></td>
        </tr>
        <?php


$sSqlWrk = "SELECT vencimiento.* FROM vencimiento join recibo_vencimiento
on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id 
where recibo_vencimiento.recibo_id = $x_recibo_id";
$rswrkm = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$x_total = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

while ($row = @phpmkr_fetch_array($rswrkm)) {

		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
	
?>
        <!-- Table body -->
        <tr>
          <!-- vencimiento_id -->
          <td align="right"><span> <?php echo $x_vencimiento_num; ?> </span></td>
          <!-- credito_id -->
          <!-- vencimiento_status_id -->
          <td><span>
            <?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
            <?php echo $x_vencimiento_status_id; ?>
            <?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>
          </span></td>
          <!-- fecha_vencimiento -->
          <td align="center"><span> <?php echo FormatDateTime($x_fecha_vencimiento,7); ?> </span></td>
          <!-- importe -->
          <td align="right"><span> <?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?> </span></td>
          <!-- interes -->
          <td align="right"><span> <?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?> </span></td>
          <!-- interes_moratorio -->
          <td align="right"><span> <?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?> </span></td>
          <td align="right"><span> <?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?> </span></td>
        </tr>
        <?php
	}

?>
        <tr>
          <td align="right"><span> </span></td>
          <td><span> </span></td>
          <td align="center"><span> </span></td>
          <td align="right"><span> <b> <?php echo FormatNumber($x_total_pagos,2,0,0,1); ?> </b> </span></td>
          <td align="right"><span> <b> <?php echo FormatNumber($x_total_interes,2,0,0,1); ?> </b> </span></td>
          <td align="right"><span> <b> <?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?> </b> </span></td>
          <td align="right"><span> <b> <?php echo FormatNumber($x_total_total,2,0,0,1); ?> </b> </span></td>
        </tr>
      </table>      </td>
	  </tr>
</table>
</form>
<p>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;
	$sWhere .= "(`recibo_id` = " . addslashes($sTmp) . ")";
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

		// Get the field contents
		$GLOBALS["x_recibo_id"] = $row["recibo_id"];
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];
		$GLOBALS["x_banco_id"] = $row["banco_id"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];		
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_importe"] = $row["importe"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
