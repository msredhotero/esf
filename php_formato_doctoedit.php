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
$x_formato_docto_id = Null; 
$ox_formato_docto_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_contenido = Null; 
$ox_contenido = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include_once("fckeditor/fckeditor.php") ?>
<?php

// Load key from QueryString
$x_formato_docto_id = @$_GET["formato_docto_id"];

//if (!empty($x_formato_docto_id )) $x_formato_docto_id  = (get_magic_quotes_gpc()) ? stripslashes($x_formato_docto_id ) : $x_formato_docto_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_formato_docto_id = @$_POST["x_formato_docto_id"];
	$x_descripcion = @$_POST["x_descripcion"];
	//$x_contenido = trim(stripslashes(@$_POST["FCKeditor1"]));

    $x_contenido = $_POST["x_contenido"];

/*
    $x_contenido = str_replace("�","&Aacute;",$x_contenido);
	$x_contenido = str_replace("�","&Eacute;",$x_contenido);
	$x_contenido = str_replace("�","&Iacute;",$x_contenido);	
	$x_contenido = str_replace("�","&Oacute;",$x_contenido);		
	$x_contenido = str_replace("�","&Uacute;",$x_contenido);			
	
	$x_contenido = str_replace("�","&aacute;",$x_contenido);	
	$x_contenido = str_replace("�","&eacute;",$x_contenido);
	$x_contenido = str_replace("�","&iacute;",$x_contenido);	
	$x_contenido = str_replace("�","&oacute;",$x_contenido);		
	$x_contenido = str_replace("�","&uacute;",$x_contenido);			

	$x_contenido = str_replace("�","&Ntilde;",$x_contenido);		
	$x_contenido = str_replace("�","&ntilde;",$x_contenido);
*/
	
}

// Check if valid key
if (($x_formato_docto_id == "") || (is_null($x_formato_docto_id))) {
	ob_end_clean();
	header("Location: php_formato_doctolist.php");
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
			header("Location: php_formato_doctolist.php");
			exit();
		}
		break;
	case "U": // Update

		$x_validada = true;
		
		if (($x_descripcion == "") || ((is_null($x_descripcion)))) {
			$_SESSION["ewmsg"] .= "El nombre del docto es requerido.<br>";	
			$x_validada = false;
		}		
		if (($x_contenido == "") || ((is_null($x_contenido)))) {
			$_SESSION["ewmsg"] .= "El detalle del docto es requerido.<br>";	
			$x_validada = false;
		}

		if($x_validada == true){
			if (EditData($conn)) { // Update Record based on key
				$_SESSION["ewmsg"] = "Los datos han sido actualizados";
				phpmkr_db_close($conn);
				ob_end_clean();
				header("Location: php_formato_doctolist.php");
				exit();
			}
		}
		break;
}
?>
<?php include ("header.php") ?>

<script type="text/javascript" src="2015/js/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="2015/js/plugins/ckeditor/lang/es.js"></script>

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
if (EW_this.x_contenido && !EW_hasValue(EW_this.x_contenido, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_contenido, "TEXTAREA", "El Contenido es requerido."))
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
<p><span class="phpmaker">FORMATOS<br>
  <br>
    <a href="php_formato_doctolist.php">Regresar a la Lista</a></span></p>
<form name="formato_doctoedit" id="formato_doctoedit" action="php_formato_doctoedit.php" method="post" >
<p>
<input type="hidden" name="a_edit" value="U">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><b><?php echo $_SESSION["ewmsg"] ?></b></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<table class="ewTable">
	<tr>
		<td width="95" class="ewTableHeader"><span>No</span></td>
		<td width="893" class="ewTableAltRow"><span>
<?php echo $x_formato_docto_id; ?>
<input type="hidden" id="x_formato_docto_id" name="x_formato_docto_id" value="<?php echo htmlspecialchars(@$x_formato_docto_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="50" maxlength="50" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
</table>

    <textarea name="x_contenido" id="x_contenido" class='ckeditor' rows="5" ><?php echo $x_contenido; ?></textarea>

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
	global $x_formato_docto_id;
	$sSql = "SELECT * FROM `formato_docto`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_formato_docto_id) : $x_formato_docto_id;
	$sWhere .= "(`formato_docto_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_formato_docto_id"] = $row["formato_docto_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_contenido"] = $row["contenido"];
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
	global $x_formato_docto_id;
	$sSql = "SELECT * FROM `formato_docto`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_formato_docto_id) : $x_formato_docto_id;	
	$sWhere .= "(`formato_docto_id` = " . addslashes($sTmp) . ")";
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_contenido"]) : $GLOBALS["x_contenido"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`contenido`"] = $theValue;

		// update
		$sSql = "UPDATE `formato_docto` SET ";
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

