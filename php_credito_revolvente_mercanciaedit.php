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
$x_delegacion_id = Null; 
$ox_delegacion_id = Null;
$x_entidad_id = Null; 
$ox_entidad_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_delegacion_id = @$_GET["key_id"];

//if (!empty($x_delegacion_id )) $x_delegacion_id  = (get_magic_quotes_gpc()) ? stripslashes($x_delegacion_id ) : $x_delegacion_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	foreach($_POST as $campo => $valor){
		$$campo = $valor;		
		}
	
	
}

// Check if valid key
if (($x_delegacion_id == "") || (is_null($x_delegacion_id))) {
	ob_end_clean();
	header("Location: php_credito_revolvente_mercancialist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_credito_revolvente_mercancialist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_credito_revolvente_mercancialist.php");
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
if (EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "La entidad es requerida."))
		return false;
}
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "El nombre es requerido."))
		return false;
}
return true;
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">CREDITO REVOLVENTE ENTREGA DE MERCANCIA<br>
<br>
    <a href="php_credito_revolvente_mercancialist.php">Regresar a la lista</a></span></p>
<form name="delegacionedit" id="delegacionedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable_small">
	<tr>
		<td width="146" class="ewTableHeaderThin"><span>No</span></td>
		<td width="642" class="ewTableAltRow"><span>
<?php # echo $x_id; ?>
<input type="hidden" id="x_delegacion_id" name="x_delegacion_id" value="<?php echo htmlspecialchars(@$x_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php

echo $x_cliente;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin">numero de credito</td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_numero_credito); ?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin"><span>monto</span></td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_monto); ?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin">fecha otorgamiento</td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_fecha); ?>
</span></td>
	</tr>
    	<tr>
		<td class="ewTableHeaderThin">status</td>
		<td class="ewTableAltRow"><span>
<?php 
$x_entidad_idList = "<select name=\"x_status_id\">";
$x_entidad_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT  credito_revolvente_mercancia_status_id, descripcion  FROM `credito_revolvente_mercancia_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["credito_revolvente_mercancia_status_id"] == @$x_status_id ) {
			$x_entidad_idList .= "' selected";
		}
		$x_entidad_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_entidad_idList .= "</select>";
echo $x_entidad_idList;
?>
</span></td>
	</tr>
    <tr>
    <td></td>
    <td></td>
    </tr>
</table>
<p>
<input type="submit" name="Action" value="EDITAR">
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

function LoadData($conn)
{
	global $x_delegacion_id;
	$sSql = "SELECT * FROM `credito_revolvente_mercancia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_delegacion_id) : $x_delegacion_id;
	$sWhere .= "(`credito_revolvente_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_id"] = $x_delegacion_id;
		$x_credito_id = $row["credito_id"];
		$GLOBALS["x_status_id"] = $row["status_id"];
		
		$sqlCliente = "SELECT cliente.*,credito.credito_num, credito.importe, credito.fecha_otrogamiento FROM cliente JOIN solicitud_cliente ON 	solicitud_cliente.cliente_id = cliente.cliente_id  join credito on credito.solicitud_id = solicitud_cliente.solicitud_id ";
		$sqlCliente .= " WHERE credito.credito_id = $x_credito_id ";
		$rsCliente =  phpmkr_query($sqlCliente,$conn) or die ("Errro al seleccionar los datpos del cliente".phpmkr_error()."Sql:".$sqlCliente);
		$rowCliente = phpmkr_fetch_array($rsCliente);
		$GLOBALS["x_cliente"] = $rowCliente["nombre_completo"]. " ".$rowCliente["apellido_paterno"]. " ".$rowCliente["apellido_materno"];
		$GLOBALS["x_numero_credito"] =  $rowCliente["credito_num"];
		$GLOBALS["x_monto"] =  $rowCliente["importe"];
		$GLOBALS["x_fecha"] = $rowCliente["fecha_otrogamiento"];
		
		mysql_free_result($rsCliente);
		$sqlCliente = "SELECT descripcion from credito_revolvente_mercancia_status  ";
		$sqlCliente .= " WHERE credito_revolvente_mercancia_status_id = $x_status_id ";
		#$rsCliente =  phpmkr_query($sqlCliente,$conn) or die ("Errro al seleccionar los datpos del cliente".phpmkr_error()."Sql:".$sqlCliente);
		#$rowCliente = phpmkr_fetch_array($rsCliente);
		#$x_status_descripcion = $rowCliente["descripcion"];
		
		
		
		
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
{
	global $x_delegacion_id;
	$sSql = "SELECT * FROM `credito_revolvente_mercancia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_delegacion_id) : $x_delegacion_id;	
	$sWhere .= "(`credito_revolvente_id` = " . addslashes($sTmp) . ")";
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_status_id"] != "") ? intval($GLOBALS["x_status_id"]) : "NULL";
		$fieldList["`status_id`"] = $theValue;
		
		// update
		$sSql = "UPDATE `credito_revolvente_mercancia` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
