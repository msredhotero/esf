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

$x_puntaje_id = Null;
$ox_puntaje_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_parentesco_tipo_id = Null; 
$ox_parentesco_tipo_id = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_ingresos_mensuales = Null; 
$ox_ingresos_mensuales = Null;
$x_ocupacion = Null; 
$ox_ocupacion = Null;
?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php


// Load key from QueryString

$x_puntaje_id = @$_GET["puntaje_id"];
//if (!empty($x_puntaje_id )) $x_puntaje_id  = (get_magic_quotes_gpc()) ? stripslashes($x_puntaje_id ) : $x_puntaje_id ;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {


	// Get fields from form

	$x_puntaje_id = @$_POST["x_puntaje_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_valor = @$_POST["x_valor"];
	$x_razon_cambio = @$_POST["x_razon_cambio"];
	$x_grado = @$_POST["x_grado"];
	$x_actualiza_original = @$_POST["x_actualiza_original"];
	$x_puntuacion_original = @$_POST["x_puntuacion_original"];
}



// Check if valid key

if (($x_puntaje_id == "") || (is_null($x_puntaje_id))) {
	ob_end_clean();
	header("Location: php_puntajelist.php");
	exit();

}

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{

	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_puntajelist.php");
			exit();
		}

		break;

	case "U": // Update

		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Se actualizó el puntaje del cliente";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_puntajelist.php");
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
if (EW_this.x_razon_cambio && !EW_hasValue(EW_this.x_razon_cambio, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_razon_cambio, "SELECT", "la justificacion es requerida."))
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

<p><span class="phpmaker">Editar puntuaci&oacute;n del cliente(OF)<br><br></span></p>
<form name="puntajeedit" id="puntajeedit" action="php_puntajeedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>

<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_puntuacion_original" value="<?php echo $x_puntuacion_original;?>">
<input type="hidden" name="x_actualiza_original" value="<?php echo $x_actualiza_original;?>">

<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_puntaje_id; ?><input type="hidden" id="x_puntaje_id" name="x_puntaje_id" value="<?php echo htmlspecialchars(@$x_puntaje_id); ?>">

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_cliente;
if($x_puntuacion_original !=''){
	echo "<br><b>Este cliente originalmente fue catalogado como riesgo: ".$x_puntuacion_original."</b>";
	}
?>

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Valor</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_valor; ?>

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Grado</span></td>
		<td class="ewTableAltRow"><span>
<?php
 
$x_parentesco_tipo_idList = "<select name=\"x_grado\" id=\"x_grado\">";
$x_parentesco_tipo_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `descripcion` FROM `cat_puntaje`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["descripcion"] == @$x_grado) {
			$x_parentesco_tipo_idList .= "' selected";
		}

		$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;

	}

}

@phpmkr_free_result($rswrk);
$x_parentesco_tipo_idList .= "</select>";
echo $x_parentesco_tipo_idList;
?>

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader">Fecha de edici&oacute;n</td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_fecha_cambio) ?>

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Justificaci&oacute;n del cambio</span></td>
		<td class="ewTableAltRow"><span>
      <textarea rows="4" cols="70"  name="x_razon_cambio" id="x_razon_cambio"><?php echo $x_razon_cambio;?>
</textarea></span></td>
	</tr>
    	
</table>
<p>

<input type="submit" name="Action" value="Guardar">
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



function LoadData($conn){

	global $x_puntaje_id;
	$sSql = "SELECT * FROM `puntaje`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_puntaje_id) : $x_puntaje_id;
	$sWhere .= "(`puntaje_id` = " . addslashes($sTmp) . ")";
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

		$GLOBALS["x_puntaje_id"] = $row["puntaje_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_valor"] = $row["valor"];
		$GLOBALS["x_razon_cambio"] = $row["razon_cambio"];
		$GLOBALS["x_grado"] = $row["grado"];
		$GLOBALS["x_fecha_cambio"] = $row["fecha_cambio"];
		$GLOBALS["x_puntuacion_original"] = $row["puntuacion_original"];
		if(empty($row["puntuacion_original"])){
			$GLOBALS["x_puntuacion_original"] = $row["grado"];
			$GLOBALS["x_actualiza_original"] = 1;
			$GLOBALS["x_fecha_cambio"]= date("Y-m-d");
			}
			
	$sqlCliente =  "SELECT * FROM cliente WHERE cliente_id = ".$row["cliente_id"]." ";	
	$rsCliente = phpmkr_query($sqlCliente,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);	
	#print_r($rowCliente);	
	$GLOBALS["x_nombre_cliente"] = $rowCliente["nombre_completo"]." ".$rowCliente["apellido_paterno"]." ".$rowCliente["apellido_materno"];
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
	global $x_puntaje_id;
	$sSql = "SELECT * FROM `puntaje`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_puntaje_id) : $x_puntaje_id;	
	$sWhere .= "(`puntaje_id` = " . addslashes($sTmp) . ")";
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

		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_razon_cambio"]) : $GLOBALS["x_razon_cambio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`razon_cambio`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_grado"]) : $GLOBALS["x_grado"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL"; 
		$fieldList["`grado`"] = $theValue;
		$theValue = date("Y-m-d");
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`fecha_cambio`"] = $theValue;
	 	if($GLOBALS["x_actualiza_original"]==1){				
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_puntuacion_original"]) : $GLOBALS["x_puntuacion_original"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`puntuacion_original`"] = $theValue;	 
		 }


		// update

		$sSql = "UPDATE `puntaje` SET ";
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

