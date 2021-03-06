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
$x_garantia_id = Null; 
$ox_garantia_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_valor = Null; 
$ox_valor = Null;
$x_documento = Null; 
$ox_documento = Null;
$fs_x_documento = 0;
$fn_x_documento = "";
$ct_x_documento = "";
$w_x_documento = 0;
$h_x_documento = 0;
$a_x_documento = "";
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_garantia_id = @$_GET["garantia_id"];
if (empty($x_garantia_id)) {
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
	$x_garantia_id = @$_POST["x_garantia_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_descripcion = @$_POST["x_descripcion"];
	$x_valor = @$_POST["x_valor"];
	$x_documento = @$_POST["x_documento"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_garantialist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_garantialist.php");
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
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "SELECT", "El Cliente es requerido."))
		return false;
}
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripción es requerida."))
		return false;
}
if (EW_this.x_valor && !EW_hasValue(EW_this.x_valor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_valor, "TEXT", "El valor es requerido."))
		return false;
}
if (EW_this.x_valor && !EW_checknumber(EW_this.x_valor.value)) {
	if (!EW_onError(EW_this, EW_this.x_valor, "TEXT", "El valor es requerido."))
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
<p><span class="phpmaker">Add to TABLE: GARANTIAS<br><br><a href="php_garantialist.php">Back to List</a></span></p>
<form name="garantiaadd" id="garantiaadd" action="php_garantiaadd.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="EW_Max_File_Size" value="2000000">
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
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_cliente_id)) || ($x_cliente_id == "")) { $x_cliente_id = 0;} // Set default value ?>
<?php
$x_cliente_idList = "<select name=\"x_cliente_id\">";
$x_cliente_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `cliente_id`, `nombre_completo` FROM `cliente`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_cliente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["cliente_id"] == @$x_cliente_id) {
			$x_cliente_idList .= "' selected";
		}
		$x_cliente_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_cliente_idList .= "</select>";
echo $x_cliente_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descripción</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Valor</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_valor" id="x_valor" size="30" value="<?php echo htmlspecialchars(@$x_valor) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Documento</span></td>
		<td class="ewTableAltRow"><span>
<?php $x_documento = ""; // Clear BLOB related fields ?>
<input type="file" id="x_documento" name="x_documento" size="30">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="ADD">
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
	global $x_garantia_id;
	$sSql = "SELECT * FROM `garantia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_garantia_id) : $x_garantia_id;
	$sWhere .= "(`garantia_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_garantia_id"] = $row["garantia_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_valor"] = $row["valor"];
		$GLOBALS["x_documento"] = $row["documento"];
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
	global $x_garantia_id;
	$sSql = "SELECT * FROM `garantia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_garantia_id == "") || (is_null($x_garantia_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_garantia_id) : $x_garantia_id;			
		$sWhereChk .= "(`garantia_id` = " . addslashes($sTmp) . ")";
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
	if (!empty($_FILES["x_documento"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_documento"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}

	// Field cliente_id
	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $theValue;

	// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`descripcion`"] = $theValue;

	// Field valor
	$theValue = ($GLOBALS["x_valor"] != "") ? " '" . doubleval($GLOBALS["x_valor"]) . "'" : "NULL";
	$fieldList["`valor`"] = $theValue;

	// Field documento
		if (is_uploaded_file($_FILES["x_documento"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_documento"]["name"]);
					if (!move_uploaded_file($_FILES["x_documento"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!get_magic_quotes_gpc()) ? addslashes(ewUploadFileName($_FILES["x_documento"]["name"])) : ewUploadFileName($_FILES["x_documento"]["name"]);
			$fieldList["`documento`"] = " '" . $theName . "'";
			@unlink($_FILES["x_documento"]["tmp_name"]);
		}

	// insert into database
	$sSql = "INSERT INTO `garantia` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
