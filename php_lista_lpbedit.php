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
$x_csv_lista_lpb_id = Null; 
$ox_csv_lista_lpb_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_valor = Null; 
$ox_valor = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_csv_lista_lpb_id = @$_GET["csv_lista_lpb_id"];

//if (!empty($x_csv_lista_lpb_id )) $x_csv_lista_lpb_id  = (get_magic_quotes_gpc()) ? stripslashes($x_csv_lista_lpb_id ) : $x_csv_lista_lpb_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_csv_lista_lpb_id = @$_POST["x_csv_lista_lpb_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	
	$x_fecha_listado = @$_POST["x_fecha_listado"];
	
	$x_notas = @$_POST["x_notas"];
	
	
}

// Check if valid key
if (($x_csv_lista_lpb_id == "") || (is_null($x_csv_lista_lpb_id))) {
	ob_end_clean();
	header("Location: php_lpblist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No selocalizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_lpblist.php?cmd=resetall");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_lpblist.php?cmd=resetall");
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
<p><span class="phpmaker">Lista LPB<br>
  <br>
    <a href="php_lpblist.php?cmd=resetall">Regresar a la lista</a></span></p>
<form name="mensajeria_tipoedit" id="mensajeria_tipoedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable_small">
	<tr>
		<td width="109" class="ewTableHeader">ID</td>
		<td width="879" class="ewTableAltRow"><span>
<?php echo $x_csv_lista_lpb_id; ?>
<input type="hidden" id="x_csv_lista_lpb_id" name="x_csv_lista_lpb_id" value="<?php echo htmlspecialchars(@$x_csv_lista_lpb_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre completo (Paterno Materno Nombres)</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="100" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader">UN Tipo Lista</td>
		<td class="ewTableAltRow"><span>
<input type="text"  disabled="disabled" name="x_un_tipo_lista" id="x_un_tipo_lista" size="100" maxlength="25" value="<?php echo htmlspecialchars(@$x_un_tipo_lista) ?>">
</span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader">Tipo Lista</td>
		<td class="ewTableAltRow"><span>
<input type="text" disabled="disabled" name="x_tipo_lista" id="x_tipo_lista" size="100" maxlength="25" value="<?php echo htmlspecialchars(@$x_tipo_lista) ?>">
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader"><span>Fecha listado</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_listado" id="x_fecha_listado" size="100" maxlength="25" value="<?php echo htmlspecialchars(@$x_fecha_listado) ?>">

</span></td>
	</tr>
    
   
    
    <tr>
		<td class="ewTableHeader"><span>Notas</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_notas" id="x_notas" size="100" maxlength="150" value="<?php echo htmlspecialchars(@$x_notas) ?>">
</span></td>
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
	global $x_csv_lista_lpb_id;
	$sSql = "SELECT * FROM `csv_lista_lpb`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_csv_lista_lpb_id) : $x_csv_lista_lpb_id;
	$sWhere .= "(`csv_lista_lpb_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_csv_lista_lpb_id"] = $row["csv_lista_lpb_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];		
		$GLOBALS["x_fecha_listado"] = $row["fecha_listado"];
		$GLOBALS["x_un_tipo_lista"] = $row["un_tipo_lista"];
		$GLOBALS["x_tipo_lista"] = $row["tipo_lista"];
		$GLOBALS["x_notas"] = $row["notas"];	
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
	global $x_csv_lista_lpb_id;
	$sSql = "SELECT * FROM `csv_lista_lpb`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_csv_lista_lpb_id) : $x_csv_lista_lpb_id;	
	$sWhere .= "(`csv_lista_lpb_id` = " . addslashes($sTmp) . ")";
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
	
		
		
		/*// Field fecha_registro
		$theValue = ($GLOBALS["x_fecha_listado"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_listado"]) . "'" : "Null";
		$fieldList["`fecha_reporte`"] = $theValue;*/
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_fecha_listado"]) : $GLOBALS["x_fecha_listado"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`fecha_listado`"] = $theValue;
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_notas"]) : $GLOBALS["x_notas"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`notas`"] = $theValue;
		
		

		// update
		$sSql = "UPDATE `csv_lista_lpb` SET ";
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
