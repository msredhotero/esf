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

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_aval_id = @$_GET["aval_id"];
if (empty($_POST["x_nombre_galeria"])) {
	$bCopy = false;
}

$x_liga_ife = 0;
// Get action

if(empty($sAction)){
$sAction = @$_POST["a_add"];	
	}

if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{


$x_nombre_galeria = $_POST["x_nombre_galeria"];
$x_tipo_galeria = $_POST["x_tipo_galeria"];
$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
//echo "id galeria".$x_galeria_fotografica_id."<br>";


}
$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
$id = $x_galeria_fotografica_id;
//echo "id".$id."<br>";

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);



$sqlS = "SELECT  *  from galeria_fotografica  WHERE galeria_fotografica_id = $id ";
	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
$row = mysql_fetch_assoc($rsS);
$x_cadena_fotografica = "";

array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
//print_r($row);
foreach($row as $campo => $valor){
	$nombre_galeria = str_replace ("_" ," ", $campo);
	$nombre_pic = str_replace("_"," ", $valor);
	if(strlen($valor) >5){
		// forma parte de la cadena
		//echo "<br>".$campo."----> ".$valor." --<br>";
		$x_cadena_fotografica = $x_cadena_fotografica. "<a href=\"".ewUploadPath(0) .$valor."\"><img src=\"".ewUploadPath(0) .$valor."\" alt=\"".$nombre_pic."\" title=\"".$nombre_galeria."\"></a> ";
		
		}
	
	}


	
	
	



$sAction = "C";
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn,$x_galeria_fotografica_id)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			//phpmkr_db_close($conn);
			//ob_end_clean();
			//header("Location: php_avallist.php");
			//exit();
		}
		break;
	
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
    <script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
<script src="galleria/galleria-1.2.9.min.js"></script>
<style>
    #galleria{ width: 1000px; height: 800px; background: #000 }
</style>
<script>
    Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
	  Galleria.run('#galleria');
	 
	
</script>

<script>
   
	Galleria.configure('imageCrop', true);
	Galleria.ready(function(options) {

    // 'this' is the gallery instance
    // 'options' is the gallery options

    this.bind('image', function(e) {
        Galleria.log('Now viewing ' + e.imageTarget.src);
    });
});

jQuery(document).ready(function($){
	$('#galleria').data('galleria').enterFullscreen();
	
	})
	Galleria.ready(function(options) {

    // 'this' is the gallery instance
    // 'options' is the gallery options

    this.bind('image', function(e) {
        Galleria.log('Now viewing ' + e.imageTarget.src);
    });
});

</script>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">



function irAListado(){
	window.locationf="php_galeria_fotograficalist.php";
	}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>

<center><table align="center" width="90%">
<tr><td> <a href="php_galeriaview.php?x_galeria_fotografica_id=<?php echo $id?>"> VER CON LA GALERIA ANTERIOR</a></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<div id="galleria">
<?php 
echo $x_cadena_fotografica;

?>   
    
</div>
</td></tr>
</table></center>


<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn, $x_id)
{
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	#agregamos la galeria
	
	
	
	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_galeria"]) : $GLOBALS["x_nombre_galeria"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_galeria`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($GLOBALS["x_tipo_galeria"] != "") ? intval($GLOBALS["x_tipo_galeria"]) : "NULL";
	$fieldList["`tipo_galeria_id`"] = $theValue;

	

	// insert into database
	$sSql = "INSERT INTO `galeria_fotografica` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$GLOBALS["x_galeria_fotografica_id"] = mysql_insert_id();
	
	if($x_result){
	return true;
	}else{
		//die("error". phphmkr_error()."sql".$sSql);
				
		return false;
		}
}
?>
