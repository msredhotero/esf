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
<?php

// Get key
$x_formato_docto_id = @$_GET["formato_docto_id"];
if (($x_formato_docto_id == "") || ((is_null($x_formato_docto_id)))) {
	ob_end_clean(); 
	header("Location: php_formato_doctolist.php"); 
	exit();
}

//$x_formato_docto_id = (get_magic_quotes_gpc()) ? stripslashes($x_formato_docto_id) : $x_formato_docto_id;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_formato_doctolist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">FORMATOS<br>
  <br>
<a href="php_formato_doctolist.php">Regresar a la lista</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td width="125" class="ewTableHeaderThin"><span>No</span></td>
		<td width="863" class="ewTableAltRow"><span>
<?php echo $x_formato_docto_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_descripcion; ?>
</span></td>
	</tr>
	<tr>
		<td colspan="2" class="ewTableHeaderThin"><div align="center"><span>Contenido</span></div></td>
		</tr>
	<tr>
	  <td colspan="2" align="left" valign="top" class="ewTableAltRow"><span>
	  <?php //echo str_replace(chr(10), "<br>", $x_contenido); ?>
	  <?php echo $x_contenido; ?>	  
	  </span></td>
	  </tr>
</table>
</form>
<p>
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
