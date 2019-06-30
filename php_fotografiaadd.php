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


$x_solicitud_id = $_GET["x_solicitud_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = $_POST["x_solicitud_id"];
	if(empty($x_solicitud_id)){
		echo "No se especifico la solicitud";
		exit();
	}
}


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	


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
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString


$bCopy = true;
$x_fotografia_id = @$_GET["fotografia_id"];
if (empty($x_fotografia_id)) {
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
	$x_fotografia_id = @$_POST["x_fotografia_id"];
//	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_titulo = @$_POST["x_titulo"];
	$x_descripcion = @$_POST["x_descripcion"];
	$x_foto = @$_POST["x_foto"];
	$x_fecha_registro = $currdate;
}

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fotografialist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
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
<title>e - SF >  FINANCIERA CRECE - FOTOs </title>
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
if (EW_this.x_foto && !EW_hasValue(EW_this.x_foto, "FILE" )) {
	if (!EW_onError(EW_this, EW_this.x_foto, "FILE", "La foto es requerida."))
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
<p align="center"><span class="phpmaker">Agragando fotografias<br>
  <br><a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form name="fotografiaadd" id="fotografiaadd" action="php_fotografiaadd.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>">
<input type="hidden" name="EW_Max_File_Size" value="2000000">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p align="center"><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table width="396" align="center" >
	
<tr>
		<td width="114" class="ewTableHeaderThin">Titulo</td>
		<td width="257" class="ewTableAltRow"><span>
<input type="text" name="x_titulo" id="x_titulo" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_titulo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Descripcion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Fotografia</span></td>
		<td class="ewTableAltRow"><span>
<?php $x_foto = ""; // Clear BLOB related fields ?>
<input type="file" id="x_foto" name="x_foto" size="30">
</span></td>
	</tr>
</table>
<p align="center">
<input type="submit" name="Action" value="Agregar">
</p>
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
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_fotografia_id;
	$sSql = "SELECT * FROM `fotografia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_fotografia_id == "") || (is_null($x_fotografia_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fotografia_id) : $x_fotografia_id;			
		$sWhereChk .= "(`fotografia_id` = " . addslashes($sTmp) . ")";
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

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_foto"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_foto"]["size"] > $EW_MaxFileSize) {
			die("Tamaño maximo de archivo excedido.");
		}
	}

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;

	// Field tituo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_titulo"]) : $GLOBALS["x_titulo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`titulo`"] = $theValue;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`descripcion`"] = $theValue;

// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;
	
		
	// Field foto
		if (is_uploaded_file($_FILES["x_foto"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_foto"]["name"]);
					if (!move_uploaded_file($_FILES["x_foto"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_foto"]["name"])) : ewUploadFileName($_FILES["x_foto"]["name"]);
			$fieldList["`foto`"] = " '" . $theName . "'";
			@unlink($_FILES["x_foto"]["tmp_name"]);
		}

	
	// insert into database
	$sSql = "INSERT INTO `fotografia` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
