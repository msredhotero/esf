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

$x_solicitud_id = @$_GET["x_solicitud_id"];


$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$sAction = @$_POST["a_add"];

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
			header("Location: php_galeriaedit.php?x_galeria_fotografica_id=".$x_galeria_fotografica_id);
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

	validada = true;

	if (EW_this.x_nombre_galeria && !EW_hasValue(EW_this.x_nombre_galeria, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_galeria, "TEXT", "EL nombre de la galeria es requerido."))
		validada = false;
	}

	if (EW_this.x_tipo_galeria && !EW_hasValue(EW_this.x_tipo_galeria, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_galeria, "SELECT", "El tipo de galeria es requerido."))
		validada = false;
	}

	return validada;
}

//-->
</script>

<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">Crear galeria para la solicitud</span><span class="phpmaker"><br>
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
		<td width="693" class="ewTableAltRow"><span>
<input type="text" name="x_nombre_galeria" id="x_nombre_galeria" size="50" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
   <tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>Iniciado</span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin"><span>Tipo galeria</span></td>
		<td class="ewTableAltRow">
			<span>
			<?php
			$x_entidad_idList = "<select name=\"x_tipo_galeria\">";
			$x_entidad_idList .= "<option value=''>Seleccione</option>";
			$sSqlWrk = "SELECT `tipo_galeria_id`, `descripcion` FROM `tipo_galeria`";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk) {
				$rowcntwrk = 0;
				while ($datawrk = phpmkr_fetch_array($rswrk)) {
					$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
					if ($datawrk["tipo_galeria_id"] == @$x_tipo_galeria_id) {
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
			</span>
		</td>
	</tr>

    <tr>
		<td width="151" class="ewTableHeaderThin">Solicitud</td>
		<td width="837" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_entidad_id)) || ($x_entidad_id == "")) { $x_entidad_id = 0;} // Set default value ?>
<?php
$x_entidad_idList = "<select name=\"x_solicitud_id\">";
$sSqlWrk = "SELECT solicitud.solicitud_id as sol_id, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM  solicitud JOIN solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id  join cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud.solicitud_id = ".$x_solicitud_id;
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
	 </td><td><input type="submit" name="Action" value="Agregar Galeria"></td></tr>
</table>
<p>

</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>

<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

	$GLOBALS["x_nombre_galeria"] = $_POST["x_nombre_galeria"];
	$GLOBALS["x_tipo_galeria"] = $_POST["x_tipo_galeria"];

	$x_nombre_galeria = dropAccents($GLOBALS["x_nombre_galeria"]);

	$charset='ISO-8859-1'; // o 'UTF-8'

	$x_nombre_galeria = elimina_acentos($GLOBALS["x_nombre_galeria"]);
	$x_nombre_galeria = $GLOBALS["x_nombre_galeria"];

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_galeria) : $x_nombre_galeria;
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_galeria`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($GLOBALS["x_tipo_galeria"] != "") ? intval($GLOBALS["x_tipo_galeria"]) : "NULL";
	$fieldList["`tipo_galeria_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `galeria_fotografica` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$GLOBALS["x_galeria_fotografica_id"] = mysql_insert_id();

	if($x_result){
		$sqlS = "UPDATE galeria_fotografica set solicitud_id = ".$_GET["x_solicitud_id"]." ,status = 1 where galeria_fotografica_id = ". $GLOBALS["x_galeria_fotografica_id"]." ";
		// Field entidad_id

		phpmkr_query($sqlS, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		return true;
	}else{
	//die("error". phphmkr_error()."sql".$sSql);
		return false;
	}
}

function dropAccents($incoming_string){
	$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return utf8_encode(strtr(utf8_decode($incoming_string),
	utf8_decode($tofind),
	$replac));
}


function elimina_acentos($texto){ //www.webenphp.com
	$texto=utf8_decode($texto);
	$con_acento = utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ");
	$sin_acento = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	$texto= strtr($texto, $con_acento, $sin_acento);
	$texto = preg_replace("/[^A-Za-z0-9 _]/","",$texto);
	//si queremos pasar todos los carácteres a minusculas
	$texto = strtolower(trim ($texto));
	//si queremos sustituir el espacio en blanco por -
	$texto = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $texto);
	return $texto;
}


?>
