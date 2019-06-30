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
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_telefono_oficina = Null; 
$ox_telefono_oficina = Null;
$x_telefono_particular = Null; 
$ox_telefono_particular = Null;
$x_telefono_movil = Null; 
$ox_telefono_movil = Null;
$x_promotor_status_id = Null; 
$ox_promotor_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_promotor_id = @$_GET["promotor_id"];
if (($x_promotor_id == "") || ((is_null($x_promotor_id)))) {
	ob_end_clean(); 
	header("Location: php_promotorlist.php"); 
	exit();
}

//$x_promotor_id = (get_magic_quotes_gpc()) ? stripslashes($x_promotor_id) : $x_promotor_id;
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
			header("Location: php_promotorlist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: PROMOTORES<br><br>
<a href="php_promotorlist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_promotor_id <> "") {echo "php_promotoredit.php?promotor_id=" . urlencode($x_promotor_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_promotor_id <> "") {echo "php_promotoradd.php?promotor_id=" . urlencode($x_promotor_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_promotor_id <> "") {echo "php_promotordelete.php?promotor_id=" . urlencode($x_promotor_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_promotor_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Usuario</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_usuario_id)) && ($x_usuario_id <> "")) {
	$sSqlWrk = "SELECT `usuario` FROM `usuario`";
	$sTmp = $x_usuario_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `usuario_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["usuario"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_usuario_id = $x_usuario_id; // Backup Original Value
$x_usuario_id = $sTmp;
?>
<?php echo $x_usuario_id; ?>
<?php $x_usuario_id = $ox_usuario_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre completo</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_completo; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>comision</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_comision)) ? FormatPercent($x_comision,0,-2,-2,-2) : $x_comision; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Dirección</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_direccion_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono de oficina</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono_oficina; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono particular</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono_particular; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono móvil</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono_movil; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_promotor_status_id)) && ($x_promotor_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `promotor_status`";
	$sTmp = $x_promotor_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_status_id = $x_promotor_status_id; // Backup Original Value
$x_promotor_status_id = $sTmp;
?>
<?php echo $x_promotor_status_id; ?>
<?php $x_promotor_status_id = $ox_promotor_status_id; // Restore Original Value ?>
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
	global $x_promotor_id;
	$sSql = "SELECT * FROM `promotor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_id) : $x_promotor_id;
	$sWhere .= "(`promotor_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_comision"] = $row["comision"];
		$GLOBALS["x_direccion_id"] = $row["direccion_id"];
		$GLOBALS["x_telefono_oficina"] = $row["telefono_oficina"];
		$GLOBALS["x_telefono_particular"] = $row["telefono_particular"];
		$GLOBALS["x_telefono_movil"] = $row["telefono_movil"];
		$GLOBALS["x_promotor_status_id"] = $row["promotor_status_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
