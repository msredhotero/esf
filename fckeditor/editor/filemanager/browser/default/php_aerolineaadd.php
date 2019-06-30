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
if (@$_SESSION["megaexpo_php_project_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_aerolinea_id = Null; 
$ox_aerolinea_id = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_logotipo = Null; 
$ox_logotipo = Null;
$fs_x_logotipo = 0;
$fn_x_logotipo = "";
$ct_x_logotipo = "";
$w_x_logotipo = 0;
$h_x_logotipo = 0;
$a_x_logotipo = "";
$x_pagina_web = Null; 
$ox_pagina_web = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_aerolinea_id = @$_GET["aerolinea_id"];
if (empty($x_aerolinea_id)) {
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
	$x_aerolinea_id = @$_POST["x_aerolinea_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_logotipo = @$_POST["x_logotipo"];
	$x_pagina_web = @$_POST["x_pagina_web"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_aerolinealist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_aerolinealist.php");
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HOG Memeber Data Upload</title>
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
if (EW_this.x_logotipo && !EW_hasValue(EW_this.x_logotipo, "FILE" )) {
	if (!EW_onError(EW_this, EW_this.x_logotipo, "FILE", "Please select your Excel File to be uploaded."))
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
<p><span class="phpmaker">Hog members data upload<br>
  <br>
    <a href="javascript: window.close();">Close this window</a></span></p>
<form name="aerolineaadd" id="aerolineaadd" action="hogupload.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="EW_Max_File_Size" value="3000000">
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
		<td class="ewTableHeader">Execl File:</td>
		<td class="ewTableAltRow"><span>
<?php $x_logotipo = ""; // Clear BLOB related fields ?>
<input type="file" id="x_logotipo" name="x_logotipo" size="30">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="UPLOAD">
</form>
</body>
</html>

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

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_logotipo"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_logotipo"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}


	// Field logotipo
		if (is_uploaded_file($_FILES["x_logotipo"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_logotipo"]["name"]);
					if (!move_uploaded_file($_FILES["x_logotipo"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_logotipo"]["name"])) : ewUploadFileName($_FILES["x_logotipo"]["name"]);
			$fieldList["`logotipo`"] = " '" . $theName . "'";
			@unlink($_FILES["x_logotipo"]["tmp_name"]);
		}

	return true;
}
?>
