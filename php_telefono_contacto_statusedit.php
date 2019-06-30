<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_banco_id = Null;
$x_nombre = Null;
$x_cuenta = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$sKey = @$_GET["x_credito_id"];
if (($sKey == "") || (is_null($sKey))) { $sKey = @$_POST["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	
	foreach($_POST as $campo => $valor){
		$$campo = $valor;
		}
	
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_bancolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cartera_vencidalist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location:  php_cartera_vencidalist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "EL nombre es requerido."))
		return false;
}
if (EW_this.x_cuenta && !EW_hasValue(EW_this.x_cuenta, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_cuenta, "TEXT", "La cuenta es requerida."))
		return false;
}
return true;
}

//-->
</script>
<p><span class="phpmaker">STAUS DEL TELEFONO DE CONTACTO <br><br>

<form name="bancoedit" id="bancoedit" action="php_telefono_contacto_statusedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td width="139" class="ewTableHeader"><span>ID</span></td>
		<td width="849" class="ewTableAltRow"><span>
<?php echo $x_telefono_contacto_status_id; ?><input type="hidden" name="x_banco_id" value="<?php echo $x_banco_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader">Credito</td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_credito_num) ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader">Status</td>
		<td class="ewTableAltRow"><span>
         <?php
$x_medio_pago_idList = "<select name=\"x_telefono_status_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT * FROM `telefono_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["telefono_status_id"] == @$x_telefono_status_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"]. "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader">Usuario que editó</td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_usuario_id) ?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader">Fecha</td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_fecha) ?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader">Hora</td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_hora) ?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Editar">
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `telefono_contacto_status`";
	$sSql .= " WHERE `credito_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_telefono_contacto_status_id"] = $row["banco_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_telefono_status_id"] = $row["telefono_status_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_fecha"] = $row["fecha"];
		$GLOBALS["x_hora"] = $row["hora"];
		
		$sqlUsuario  = "select nombre FROM usuario WHERE usuario_id = ".$row["usuario_id"]." ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn) or die("error usuario".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);		
		$GLOBALS["x_usuario_id"] = $rowUsuario["nombre"];
		$sqlUsuario  = "select credito_num FROM credito WHERE credito_id = ".$sKeyWrk." ";
		$rsUsuario = phpmkr_query($sqlUsuario,$conn) or die("error credito".phpmkr_error()."sql:".$sqlUsuario);
		$rowUsuario = phpmkr_fetch_array($rsUsuario);		
		$GLOBALS["x_credito_num"] = $rowUsuario["credito_num"];
		
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($sKey,$conn)
{

	// Open record
	$sKeyWrk = "" . addslashes($sKey) . "";
	
		
		$x_fecha = date("Y-m-d");
		$x_hora = date("H:i:s");
		$x_usuario = $_SESSION["php_project_esf_status_UserID"];		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_status_id"]) : $GLOBALS["x_telefono_status_id"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_fecha) : $x_fecha; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_hora) : $x_hora; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`hora`"] = $theValue;		
		$fieldList["`usuario_id`"] = $x_usuario;
		// update
		$sSql = "UPDATE `telefono_contacto_status` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `credito_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
		$EditData = true; // Update Successful
		#echo $sSql;
		#die();
	
	return $EditData;
}
?>
