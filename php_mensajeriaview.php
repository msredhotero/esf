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
$x_mensajeria_id = Null; 
$ox_mensajeria_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_inicio = Null; 
$ox_fecha_inicio = Null;
$x_fecha_fin = Null; 
$ox_fecha_fin = Null;
$x_asunto = Null; 
$ox_asunto = Null;
$x_mensajeria_tipo_id = Null; 
$ox_mensajeria_tipo_id = Null;
$x_formato_docto_id = Null; 
$ox_formato_docto_id = Null;
$x_mensajeria_status_id = Null; 
$ox_mensajeria_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_mensajeria_id = @$_GET["mensajeria_id"];
if (($x_mensajeria_id == "") || ((is_null($x_mensajeria_id)))) {
	ob_end_clean(); 
	header("Location: php_mensajerialist.php"); 
	exit();
}

//$x_mensajeria_id = (get_magic_quotes_gpc()) ? stripslashes($x_mensajeria_id) : $x_mensajeria_id;
// Get action

$sAction = @$_POST["a_view"];
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
			header("Location: php_mensajerialist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">Mensajeria<br>
  <br>
<a href="php_mensajerialist.php">Regresar a la lista</a></span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td width="144" class="ewTableHeaderThin"><span>No</span></td>
		<td width="212" class="ewTableAltRow"><span>
<?php echo $x_mensajeria_id; ?>
</span></td>
	    <td width="147" class="ewTableHeaderThin"><span class="ewTableHeader"><span>Fecha de registro</span></span></td>
	    <td width="477" class="ewTableAltRow"><span><?php echo FormatDateTime($x_fecha_registro,7); ?></span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de inicio</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_inicio,7); ?>
</span></td>
	    <td class="ewTableHeaderThin"><span class="ewTableHeader"><span>Fecha de fin</span></span></td>
	    <td class="ewTableAltRow"><span><?php echo FormatDateTime($x_fecha_fin,7); ?></span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span class="ewTableHeader"><span>Tipo de mensajeria</span></span></td>
		<td class="ewTableAltRow"><span>
		  <?php
if ((!is_null($x_mensajeria_tipo_id)) && ($x_mensajeria_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `mensajeria_tipo`";
	$sTmp = $x_mensajeria_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `mensajeria_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_mensajeria_tipo_id = $x_mensajeria_tipo_id; // Backup Original Value
$x_mensajeria_tipo_id = $sTmp;
?>
          <?php echo $x_mensajeria_tipo_id; ?>
          <?php $x_mensajeria_tipo_id = $ox_mensajeria_tipo_id; // Restore Original Value ?>
        </span></td>
	    <td class="ewTableHeaderThin"><span class="ewTableHeader"><span>Status</span></span></td>
	    <td class="ewTableAltRow"><span>
	      <?php
if ((!is_null($x_mensajeria_status_id)) && ($x_mensajeria_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `mensajeria_status`";
	$sTmp = $x_mensajeria_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `mensajeria_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_mensajeria_status_id = $x_mensajeria_status_id; // Backup Original Value
$x_mensajeria_status_id = $sTmp;
?>
          <?php echo $x_mensajeria_status_id; ?>
          <?php $x_mensajeria_status_id = $ox_mensajeria_status_id; // Restore Original Value ?>
        </span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Asunto</span></td>
		<td colspan="3" class="ewTableAltRow"><span>
<?php echo $x_asunto; ?>
</span></td>
	</tr>
	<tr>
		<td colspan="4" class="ewTableHeaderThin"><span class="ewTableHeader"><span>Formato</span></span></td>
		</tr>
	
	<tr>
		<td colspan="4" class="ewTableAltRow"><span>
		  <?php
if ((!is_null($x_formato_docto_id)) && ($x_formato_docto_id <> "")) {
	$sSqlWrk = "SELECT `contenido` FROM `formato_docto`";
	$sTmp = $x_formato_docto_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `formato_docto_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["contenido"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_formato_docto_id = $x_formato_docto_id; // Backup Original Value
$x_formato_docto_id = $sTmp;
?>
          <?php echo $x_formato_docto_id; ?>
          <?php $x_formato_docto_id = $ox_formato_docto_id; // Restore Original Value ?>
        </span></td>
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
	global $x_mensajeria_id;
	$sSql = "SELECT * FROM `mensajeria`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_mensajeria_id) : $x_mensajeria_id;
	$sWhere .= "(`mensajeria_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_mensajeria_id"] = $row["mensajeria_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_inicio"] = $row["fecha_inicio"];
		$GLOBALS["x_fecha_fin"] = $row["fecha_fin"];
		$GLOBALS["x_asunto"] = $row["asunto"];
		$GLOBALS["x_mensajeria_tipo_id"] = $row["mensajeria_tipo_id"];
		$GLOBALS["x_formato_docto_id"] = $row["formato_docto_id"];
		$GLOBALS["x_mensajeria_status_id"] = $row["mensajeria_status_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
