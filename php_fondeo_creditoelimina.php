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
$x_fondeo_credito_id = Null; 
$ox_fondeo_credito_id = Null;
$x_fondeo_credito_tipo_id = Null; 
$ox_fondeo_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fondeo_credito_status_id = Null; 
$ox_fondeo_credito_status_id = Null;
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
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_fondeo_credito_id = @$_GET["credito_id"];
if (($x_fondeo_credito_id == "") || ((is_null($x_fondeo_credito_id)))) {
	$x_fondeo_credito_id = @$_POST["x_fondeo_credito_id"];
	if (($x_fondeo_credito_id == "") || ((is_null($x_fondeo_credito_id)))) {	
		ob_end_clean(); 
		header("Location: php_fondeo_creditolist.php"); 
		exit();
	}
}

//$x_fondeo_credito_id = (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

if($_POST["x_acc"] == "1"){
	//Elimina credito
	$sSql = "delete from fondeo_credito where fondeo_credito_id = $x_fondeo_credito_id";
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);	

	$_SESSION["ewmsg"] = "El credito ha sido eliminado.";
	phpmkr_db_close($conn);
	ob_end_clean();
	header("Location: php_fondeo_creditolist.php");
	exit();
}

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
function elimina_fondeo_credito(){
	var mensaje = confirm("Esta seguro de ELIMINAR este Credito?");
	if (mensaje == true){
		document.eliminacredito.submit();
	}

}
//-->
</script>


<p><span class="phpmaker">FONDOS - CREDITOS<br>
  <br>
<a href="php_fondeo_creditolist.php">Regresar a la lista</a></span></p>
<p>
<table width="700" cellpadding="1" cellspacing="2" border="0" class="phpmaker" >
	<tr>
	  <td width="80" class="ewTableHeaderThin"><span>No</span></td>
	  <td width="98" class="ewTableAltRow"><span>
  <?php echo $x_fondeo_credito_id; ?>
  </span></td>
	  <td width="10" class="ewTableAltRow">&nbsp;</td>
	  <td width="100" class="ewTableHeaderThin">Status</td>
	  <td width="220" class="ewTableAltRow"><span>
	    <?php
if ((!is_null($x_fondeo_credito_status_id)) && ($x_fondeo_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_fondeo_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_fondeo_credito_status_id = $x_fondeo_credito_status_id; // Backup Original Value
$x_fondeo_credito_status_id = $sTmp;
?>
	    <?php echo $x_fondeo_credito_status_id; ?>
	    <?php $x_fondeo_credito_status_id = $ox_fondeo_credito_status_id; // Restore Original Value ?>
	    </span></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Empresa</td>
	  <td class="ewTableAltRow"><?php
if ((!is_null($x_fondeo_empresa_id)) && ($x_fondeo_empresa_id <> "")) {
	$sSqlWrk = "SELECT `nombre` FROM `fondeo_empresa`";
	$sTmp = $x_fondeo_empresa_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `fondeo_empresa_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_fondeo_credito_status_id = $x_fondeo_credito_status_id; // Backup Original Value
$x_fondeo_credito_status_id = $sTmp;
?>
        <?php echo $x_fondeo_credito_status_id; ?>
        <?php $x_fondeo_credito_status_id = $ox_fondeo_credito_status_id; // Restore Original Value ?></td>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableHeaderThin">Vencimiento</td>
	  <td class="ewTableAltRow"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Otrogamiento</span></td>
	  <td class="ewTableAltRow"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableHeaderThin">Tasa</td>
	  <td class="ewTableAltRow"><span><?php echo (is_numeric($x_tasa)) ? FormatPercent(($x_tasa / 100),2,0,0,0) : ($x_tasa / 100); ?></span></td>
	  </tr>
	
	<tr>
	  <td class="ewTableHeaderThin">Importe</td>
	  <td class="ewTableAltRow"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?></td>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableHeaderThin">Tasa moratoria</td>
	  <td class="ewTableAltRow"><span><?php echo (is_numeric($x_tasa_moratoria)) ? FormatPercent(($x_tasa_moratoria / 100),2,0,0,0) : ($x_tasa_moratoria / 100); ?></span></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Plazo</span></td>
		<td class="ewTableAltRow"><span>
<?php 
		$sSqlWrk = "SELECT descripcion FROM plazo where plazo_id = $x_plazo";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
?>

</span></td>
	    <td class="ewTableAltRow">&nbsp;</td>
	    <td class="ewTableHeaderThin">Forma de Pago </td>
	    <td class="ewTableAltRow"><?php 
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?></td>
	</tr>
	
	<tr>
	  <td class="ewTableHeaderThin">&nbsp;</td>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableHeaderThin">Referencia</td>
	  <td class="ewTableAltRow"><span><?php echo $x_referencia_pago; ?></span></td>
	  </tr>
	</table>
<p align="left" class="ewmsg" style="font-weight: bold">&nbsp;</p>
<form name="eliminacredito" action="php_fondeo_creditoelimina.php" method="post">
<input type="hidden" name="x_fondeo_credito_id" value="<?php echo $x_fondeo_credito_id; ?>" />
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>" />
<input type="hidden" name="x_acc" value="1" />
<input type="button" value="Eliminar Cr&eacute;dito" onclick="elimina_fondeo_credito()" />
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `fondeo_credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;
	$sWhere .= "(`fondeo_credito_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_fondeo_credito_id"] = $row["fondeo_credito_id"];
		$GLOBALS["x_fondeo_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_fondeo_empresa_id"] = $row["fondeo_empresa_id"];
		$GLOBALS["x_fondeo_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
