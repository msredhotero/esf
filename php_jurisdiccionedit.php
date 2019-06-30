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


?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_jurisdiccion_id = @$_GET["jurisdiccion_id"];

//if (!empty($x_jurisdiccion_id )) $x_jurisdiccion_id  = (get_magic_quotes_gpc()) ? stripslashes($x_jurisdiccion_id ) : $x_jurisdiccion_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_jurisdiccion_id = @$_POST["x_jurisdiccion_id"];
	$x_descripcion = @$_POST["x_descripcion"];
	
	$x_jurisdiccion_organismo_id = @$_POST["x_jurisdiccion_organismo_id"];
	$x_jurisdiccion_tipo_id = @$_POST["x_jurisdiccion_tipo_id"];
	
	$x_notas = @$_POST["x_notas"];
	
	
}

// Check if valid key
if (($x_jurisdiccion_id == "") || (is_null($x_jurisdiccion_id))) {
	ob_end_clean();
	header("Location: php_jurisdiccionlist.php");
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
			header("Location: php_jurisdiccionlist.php?cmd=resetall");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_jurisdiccionlist.php?cmd=resetall");
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
<p><span class="phpmaker">Jurisdicci&oacute;n<br>
  <br>
    <a href="php_jurisdiccionlist.php?cmd=resetall">Regresar a la lista</a></span></p>
<form name="mensajeria_tipoedit" id="mensajeria_tipoedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable_small">
	<tr>
		<td width="109" class="ewTableHeader">ID</td>
		<td width="879" class="ewTableAltRow"><span>
<?php echo $x_jurisdiccion_id; ?>
<input type="hidden" id="x_jurisdiccion_id" name="x_jurisdiccion_id" value="<?php echo htmlspecialchars(@$x_jurisdiccion_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Jurisdicci&oacute;n</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="100"  value="<?php echo $x_descripcion ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader">Organismo</td>
		<td class="ewTableAltRow"><span>
<?php
		 
		$x_estado_civil_idList = "<select name=\"x_jurisdiccion_organismo_id\"  class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT * FROM `jurisdiccion_organismo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["jurisdiccion_organismo_id"] == @$x_jurisdiccion_organismo_id) {
					$x_estado_civil_idList .= "' selected";
					$x_valor = $datawrk["descripcion"];
					$x_forma_pago_id = $datawrk["jurisdiccion_organismo_id"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		 
		echo $x_estado_civil_idList;
		  
			  ?>

</span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader">Tipo </td>
		<td class="ewTableAltRow"><span>

<?php
		 
		$x_estado_civil_idList = "<select name=\"x_jurisdiccion_tipo_id\"  class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT * FROM `jurisdiccion_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["jurisdiccion_tipo_id"] == @$x_jurisdiccion_tipo_id) {
					$x_estado_civil_idList .= "' selected";
					$x_valor = $datawrk["descripcion"];
					$x_forma_pago_id = $datawrk["jurisdiccion_tipo_id"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		 
		echo $x_estado_civil_idList;
		  
			  ?>
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
	global $x_jurisdiccion_id;
	$sSql = "SELECT * FROM `jurisdiccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_jurisdiccion_id) : $x_jurisdiccion_id;
	$sWhere .= "(`jurisdiccion_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_jurisdiccion_id"] = $row["jurisdiccion_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		#echo $row["descripcion"];
		
		$GLOBALS["x_jurisdiccion_organismo_id"] = $row["jurisdiccion_organismo_id"];
		$GLOBALS["x_jurisdiccion_tipo_id"] = $row["jurisdiccion_tipo_id"];
			
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
	global $x_jurisdiccion_id;
	$sSql = "SELECT * FROM `jurisdiccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_jurisdiccion_id) : $x_jurisdiccion_id;	
	$sWhere .= "(`jurisdiccion_id` = " . addslashes($sTmp) . ")";
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
	
		/*// Field fecha_registro
		$theValue = ($GLOBALS["jurisdiccion_organismo_id"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["jurisdiccion_organismo_id"]) . "'" : "Null";
		$fieldList["`fecha_reporte`"] = $theValue;*/
		$theValue = ($GLOBALS["x_jurisdiccion_organismo_id"] != "") ? " '" .intval( $GLOBALS["x_jurisdiccion_organismo_id"]) . "'" : "NULL";		
		$fieldList["`jurisdiccion_organismo_id`"] = $theValue;
		
		$theValue = ($GLOBALS["x_jurisdiccion_tipo_id"] != "") ? " '" .intval( $GLOBALS["x_jurisdiccion_tipo_id"]) . "'" : "NULL";		
		$fieldList["`jurisdiccion_tipo_id`"] = $theValue;
		
	
		// update
		$sSql = "UPDATE `jurisdiccion` SET ";
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
