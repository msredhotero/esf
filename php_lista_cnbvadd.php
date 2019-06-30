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
$x_id_csv_lista_negra_cnbv = Null; 
$ox_id_csv_lista_negra_cnbv = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_valor = Null; 
$ox_valor = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_id_csv_lista_negra_cnbv = @$_GET["id_csv_lista_negra_cnbv"];
if (empty($x_id_csv_lista_negra_cnbv)) {
	$bCopy = false;
}

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
	$x_id_csv_lista_negra_cnbv = @$_POST["x_id_csv_lista_negra_cnbv"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_rfc = @$_POST["x_rfc"];
	$x_fecha_reporte = @$_POST["x_fecha_reporte"];
	$x_monto = @$_POST["x_monto"];
	$x_entidad_reporta = @$_POST["x_entidad_reporta"];
	$x_notas = @$_POST["x_notas"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cnbvlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cnbvlist.php");
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
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripcion es requerida."))
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
<p><span class="phpmaker">Agregar registro a la lista de la CNBV<br>
  <br>
    <a href="php_cnbvlist.php">Regresar a la lista</a></span></p>
<form name="mensajeria_tipoadd" id="mensajeria_tipoadd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
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
<table class="ewTable_small">
	<tr>
		<td class="ewTableHeader"><span>Nombre completo (Paterno Materno Nombres)</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="100" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>RFC</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_rfc" id="x_rfc" size="100" maxlength="25" value="<?php echo htmlspecialchars(@$x_rfc) ?>">
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader"><span>Fecha reporte (DD/MM/AAAA)</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_reporte" id="x_fecha_reporte" size="100" maxlength="25" value="<?php echo htmlspecialchars(@$x_fecha_reporte) ?>">

</span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader"><span>Monto</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto" id="x_monto" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_monto) ?>">
</span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader"><span>Entidad que reporta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_entidad_reporta" id="x_entidad_reporta" size="100" maxlength="150" value="<?php echo htmlspecialchars(@$x_entidad_reporta) ?>">
</span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader"><span>Notas</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_notas" id="x_notas" size="100" maxlength="150" value="<?php echo htmlspecialchars(@$x_notas) ?>">
</span></td>
	</tr>>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Agregar">
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
	global $x_id_csv_lista_negra_cnbv;
	$sSql = "SELECT * FROM `csv_lista_negra_cnbv`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_id_csv_lista_negra_cnbv) : $x_id_csv_lista_negra_cnbv;
	$sWhere .= "(`id_csv_lista_negra_cnbv` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_id_csv_lista_negra_cnbv"] = $row["id_csv_lista_negra_cnbv"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_rfc"] = $row["rfc"];
		$GLOBALS["x_fecha_reporte"] = $row["fecha_reporte"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_entidad_reporta"] = $row["entidad_reporta"];
		$GLOBALS["x_notas"] = $row["notas"];
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
	global $x_id_csv_lista_negra_cnbv;
	$sSql = "SELECT * FROM `csv_lista_negra_cnbv`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_id_csv_lista_negra_cnbv == "") || (is_null($x_id_csv_lista_negra_cnbv))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_id_csv_lista_negra_cnbv) : $x_id_csv_lista_negra_cnbv;			
		$sWhereChk .= "(`id_csv_lista_negra_cnbv` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
		
		
		
	
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_fecha_reporte"]) : $GLOBALS["x_fecha_reporte"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`fecha_reporte`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_monto"]) : $GLOBALS["x_monto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`monto`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_reporta"]) : $GLOBALS["x_entidad_reporta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad_reporta`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_notas"]) : $GLOBALS["x_notas"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`notas`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `csv_lista_negra_cnbv` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
