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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_galeria_fotografica_id = @$_GET["x_galeria_fotografica_id"];
$x_tipo_galeria = @$_GET["x_tipo_galeria"];

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_galeria_fotografica_id = @$_POST["x_galeria_fotografica_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_tipo_galeria_id = $_POST["x_tipo_galeria_id"];
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sqlS = "SELECT tipo_galeria_id, status, nombre_galeria FROM galeria_fotografica WHERE galeria_fotografica_id = $x_galeria_fotografica_id";
$rs= phpmkr_query($sqlS, $conn) or die("error".phpmkr_error()."sql:".$sqlS);
$row = phpmkr_fetch_array($rs);
$x_tipo_gal = $row["tipo_galeria_id"];
if($x_tipo_gal == 1){
	$x_tipo_gal_2 ="Cliente";
	}else{
		$x_tipo_gal_2 = "Aval";
		
		}
$x_status = $row["status"];
if(empty($x_status)){
	$x_status = "No asignado";
	}
$x_gal_name = $row["nombre_galeria"];

switch ($sAction)
{
	case "D": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_delegacionlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_galeria_fotograficalist.php");
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

if (EW_this.x_solicitud_id && !EW_hasValue(EW_this.x_solicitud_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "El nombre de la delegacion o municipio es requerido."))
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
<p><span class="phpmaker">Asignar galeria a solicitud</span><span class="phpmaker"><br>
  <br>
  <a href="php_galeria_fotograficalist.php">Regresar a la lista</a></span></p>
<form name="delegacionadd" id="delegacionadd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
  <p>
<input type="hidden" name="a_add" value="A">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table class="ewTable">
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre galeria</span></td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_gal_name) ?>
</span></td>
	</tr>
   <tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_status) ?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin"><span>Tipo galeria</span></td>
		<td class="ewTableAltRow"><span><?php echo htmlspecialchars(@$x_tipo_gal_2) ?>
</span></td>
	</tr> 
	
    <tr>
		<td width="151" class="ewTableHeaderThin">Solicitud</td>
		<td width="837" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_entidad_id)) || ($x_entidad_id == "")) { $x_entidad_id = 0;} // Set default value ?>
<?php
$x_entidad_idList = "<select name=\"x_solicitud_id\">";
$x_entidad_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT solicitud.solicitud_id as sol_id, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM  solicitud JOIN solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id  join cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud.solicitud_status_id in ( 1,9,11)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["sol_id"] == @$x_sol_id) {
			$x_entidad_idList .= "' selected";
		}
		$x_entidad_idList .= ">" . $datawrk["nombre_completo"] ." ". $datawrk["apellido_paterno"] ." ".$datawrk["apellido_materno"] ."</option>";
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
      <td><input type="hidden" name="x_galeria_fotografica_id" value="<?php echo $x_galeria_fotografica_id;?>" />
      <input type="hidden" name="x_tipo_galeria_id" value="<?php echo $x_tipo_gal;?>" />
      </td><td><input type="submit" name="Action" value="Asignar galeria"></td></tr>
</table>
<p>

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
	global $x_galeria_fotografica_id;
	$sSql = "SELECT * FROM `galeria_fotografica";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_delegacion_id) : $x_delegacion_id;
	$sWhere .= "(`galeria_fotografica_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_status"] = $row["delegacion_id"];
		$GLOBALS["x_tipo_galeria_id"] = $row["entidad_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_delegacion_id;

	$sqlS = "UPDATE galeria_fotografica set solicitud_id = ". $GLOBALS["x_solicitud_id"]." ,status = 1 where galeria_fotografica_id = ". $GLOBALS["x_galeria_fotografica_id"]." ";
	// Field entidad_id
	

	phpmkr_query($sqlS, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
	return true;
}
?>
