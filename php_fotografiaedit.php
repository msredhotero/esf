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
$x_fotografia_id = Null; 
$ox_fotografia_id = Null;
$x_fotografia_categoria_id = Null; 
$ox_fotografia_categoria_id = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_direccion = Null; 
$ox_direccion = Null;
$x_mapa = Null; 
$ox_mapa = Null;
$fs_x_mapa = 0;
$fn_x_mapa = "";
$ct_x_mapa = "";
$w_x_mapa = 0;
$h_x_mapa = 0;
$a_x_mapa = "";
$x_foto = Null; 
$ox_foto = Null;
$fs_x_foto = 0;
$fn_x_foto = "";
$ct_x_foto = "";
$w_x_foto = 0;
$h_x_foto = 0;
$a_x_foto = "";
$x_pais_id = Null; 
$ox_pais_id = Null;
$x_estado_id = Null; 
$ox_estado_id = Null;
$x_fotografia_clave = Null;
$ox_fotografia_clave = Null;

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php


// Load key from QueryString
$x_fotografia_id = @$_GET["fotografia_id"];

//if (!empty($x_fotografia_id )) $x_fotografia_id  = (get_magic_quotes_gpc()) ? stripslashes($x_fotografia_id ) : $x_fotografia_id ;
// Get action


$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_fotografia_id = @$_POST["x_fotografia_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_titulo = @$_POST["x_titulo"];
	$x_descripcion = @$_POST["x_descripcion"];
	$x_foto = @$_POST["x_foto"];
}

// Check if valid key
if (($x_fotografia_id == "") || (is_null($x_fotografia_id))) {
	ob_end_clean();
	header("Location: php_fotografialist.php?x_solicitud_id=$x_solicitud_id");
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
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.listado.submit();		
			window.close(); 	
			//-->
			</script>";
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.listado.submit();		
			window.close(); 	
			//-->
			</script>";
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - FOTOS </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--

function EW_checkMyForm(EW_this) {
if (EW_this.x_titulo && !EW_hasValue(EW_this.x_titulo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_titulo, "TEXT", "El titulo es requerido."))
		return false;
}
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
<p align="center"><span class="phpmaker">Editando Fotografias<br><br>
<a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form name="fotografiaedit" id="fotografiaedit" action="php_fotografiaedit.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="EW_Max_File_Size" value="2000000">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>">

<table width="491" align="center" >
<tr>
		<td width="109" class="ewTableHeaderThin"><span>No</span></td>
	  <td width="370" class="ewTableAltRow"><span>
<?php echo $x_fotografia_id; ?>
<input type="hidden" id="x_fotografia_id" name="x_fotografia_id" value="<?php echo htmlspecialchars(@$x_fotografia_id); ?>">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin">Titulo</td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_titulo" id="x_titulo" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_titulo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin">Descripcion</td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeader"><span>Fotografia</span></td>
	  <td class="ewTableAltRow"><span>
<?php if ((!is_null($x_foto)) && $x_foto <> "") {  ?>
<input type="radio" name="a_x_foto" value="1" checked>Mantener&nbsp;
<input type="radio" name="a_x_foto" value="2">Quitar&nbsp;
<input type="radio" name="a_x_foto" value="3">Remplazar<br>
<?php } else {?>
<input type="hidden" name="a_x_foto" value="3">
<?php } ?>
<input type="file" id="x_foto" name="x_foto" size="30" onChange="if (this.form.a_x_foto[2]) this.form.a_x_foto[2].checked=true;">
</span></td>
	</tr>
</table>
<p align="center">
<input type="submit" name="Action" value="EDITAR">
</form>
</body>
</html>
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
	global $x_fotografia_id;
	$sSql = "SELECT * FROM `fotografia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fotografia_id) : $x_fotografia_id;
	$sWhere .= "(`fotografia_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_fotografia_id"] = $row["fotografia_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_titulo"] = $row["titulo"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_foto"] = $row["foto"];
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
	global $x_fotografia_id;
	$sSql = "SELECT * FROM `fotografia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fotografia_id) : $x_fotografia_id;	
	$sWhere .= "(`fotografia_id` = " . addslashes($sTmp) . ")";
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

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
		if (!empty($_FILES["x_foto"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_foto"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_foto = @$_POST["a_x_foto"];
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_titulo"]) : $GLOBALS["x_titulo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`titulo`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;

		
		if ($a_x_foto == "2") { // Remove
			$fieldList["`foto`"] = "NULL";
		} else if ($a_x_foto == "3") { // Update
			if (is_uploaded_file($_FILES["x_foto"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto"]["name"]);
						if (!move_uploaded_file($_FILES["x_foto"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto"]["name"])) : ewUploadFileName($_FILES["x_foto"]["name"]);
				$fieldList["`foto`"] = " '" . $theName . "'";
				@unlink($_FILES["x_foto"]["tmp_name"]);
			}
		}

		// update
		$sSql = "UPDATE `fotografia` SET ";
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
