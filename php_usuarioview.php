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
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_usuario_rol_id = Null; 
$ox_usuario_rol_id = Null;
$x_usuario_status_id = Null; 
$ox_usuario_status_id = Null;
$x_usuario = Null; 
$ox_usuario = Null;
$x_clave = Null; 
$ox_clave = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_caduca = Null; 
$ox_fecha_caduca = Null;
$x_fecha_visita = Null; 
$ox_fecha_visita = Null;
$x_visitas = Null; 
$ox_visitas = Null;
$x_email = Null; 
$ox_email = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_usuario_id = @$_GET["usuario_id"];
if (($x_usuario_id == "") || ((is_null($x_usuario_id)))) {
	ob_end_clean(); 
	header("Location: php_usuariolist.php"); 
	exit();
}

//$x_usuario_id = (get_magic_quotes_gpc()) ? stripslashes($x_usuario_id) : $x_usuario_id;
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
			header("Location: php_usuariolist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: USUARIOS<br><br>
<a href="php_usuariolist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_usuario_id <> "") {echo "php_usuarioedit.php?usuario_id=" . urlencode($x_usuario_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_usuario_id <> "") {echo "php_usuarioadd.php?usuario_id=" . urlencode($x_usuario_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_usuario_id <> "") {echo "php_usuariodelete.php?usuario_id=" . urlencode($x_usuario_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_usuario_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Perimisos</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_usuario_rol_id)) && ($x_usuario_rol_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `usuario_rol`";
	$sTmp = $x_usuario_rol_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `usuario_rol_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_usuario_rol_id = $x_usuario_rol_id; // Backup Original Value
$x_usuario_rol_id = $sTmp;
?>
<?php echo $x_usuario_rol_id; ?>
<?php $x_usuario_rol_id = $ox_usuario_rol_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_usuario_status_id)) && ($x_usuario_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `usuario_status`";
	$sTmp = $x_usuario_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `usuario_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_usuario_status_id = $x_usuario_status_id; // Backup Original Value
$x_usuario_status_id = $sTmp;
?>
<?php echo $x_usuario_status_id; ?>
<?php $x_usuario_status_id = $ox_usuario_status_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_usuario; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Clave</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_clave; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de caducidad</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_caduca,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ultima visita</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_visita,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>No. de visitas</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_visitas; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Email</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_email; ?>
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
	global $x_usuario_id;
	$sSql = "SELECT * FROM `usuario`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_usuario_id) : $x_usuario_id;
	$sWhere .= "(`usuario_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_usuario_rol_id"] = $row["usuario_rol_id"];
		$GLOBALS["x_usuario_status_id"] = $row["usuario_status_id"];
		$GLOBALS["x_usuario"] = $row["usuario"];
		$GLOBALS["x_clave"] = $row["clave"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_caduca"] = $row["fecha_caduca"];
		$GLOBALS["x_fecha_visita"] = $row["fecha_visita"];
		$GLOBALS["x_visitas"] = $row["visitas"];
		$GLOBALS["x_email"] = $row["email"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
