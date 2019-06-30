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
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_tipo_negocio = Null; 
$ox_tipo_negocio = Null;
$x_edad = Null; 
$ox_edad = Null;
$x_sexo = Null; 
$ox_sexo = Null;
$x_estado_civil_id = Null; 
$ox_estado_civil_id = Null;
$x_numero_hijos = Null; 
$ox_numero_hijos = Null;
$x_nombre_conyuge = Null; 
$ox_nombre_conyuge = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_cliente_id = @$_GET["cliente_id"];
if (!empty($x_cliente_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_cliente_id;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$_POST["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = split(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = split(",", $sKey);
}
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: php_clientelist.php");
	exit();
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!get_magic_quotes_gpc()) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`cliente_id`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";		// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "El cliente ha sido eliminado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">CLIENTES<br>
  <br>
    <a href="php_clientelist.php">Regresar a la lista</a></span></p>
<form action="php_clientedelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Nombre Completo</span></td>
		<td valign="top"><span>Tipo de negocio</span></td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while ($i < count($arRecKey)) {
	$nRecCount++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$x_cliente_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_cliente_id; ?>
</span></td>
		<td><span>
<?php echo $x_nombre_completo; ?>
</span></td>
		<td><span>
<?php echo $x_tipo_negocio; ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p>
<table width="764" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="136" align="center" valign="middle" bgcolor="#FF9900" class="texto_normal_bold">IMPORTANTE</td>
    <td width="15" style=" border-bottom: solid 1PX #FF9900; border-top: solid 1PX #FF9900; " align="left" valign="top" bgcolor="#FFFFFF" class="texto_normal_bold">&nbsp;</td>
    <td width="613" style=" border-bottom: solid 1PX #FF9900; border-top: solid 1PX #FF9900; border-rigth: solid 1PX #FF9900"><span class="texto_normal_bold">Al Eliminar a un Cliente toda su informaci&oacute;n: Solicitudes, Creditos, Avales, Referencias, Ingresos, etc. ser&aacute; ELIMINADA. <br />
        <br />
Este proceso NO es reversible.</span></td>
  </tr>
</table>
<p>
  <input type="submit" name="Action" value="CONFIRME">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" name="Button" value="CANCELAR" onclick="javascript: window.location='php_clientelist.php'" />
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;
	$sWhere .= "(`cliente_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_tipo_negocio"] = $row["tipo_negocio"];
		$GLOBALS["x_edad"] = $row["edad"];
		$GLOBALS["x_sexo"] = $row["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row["numero_hijos"];
		$GLOBALS["x_nombre_conyuge"] = $row["nombre_conyuge"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sSql .= " WHERE " . $sqlKey;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
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
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	global $x_cliente_id;
	$sSql = "Delete FROM `cliente`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
