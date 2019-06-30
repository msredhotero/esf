<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

// Initialize common variables
$x_convenio_liquidacion_id = Null;
$x_credito_id = Null;
$x_monto = Null;
$x_status = Null;
$x_fecha = Null;
$x_fecha_modificacion = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=convenio_liquidacion.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=convenio_liquidacion.doc');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMaster = "";
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

// Handle Reset Command
ResetCmd();

foreach($_POST as $campo=>$valor){
	$$campo=$valor;
	}

$filter = array();
if(!empty($x_bitacora)){
	$sqlBitacora = " UPDATE condonacion SET bitacora = \"$x_bitacora\" WHERE condonacion_id = $x_condonacion_id";
	$rsBitacora = phpmkr_query($sqlBitacora,$conn)or die("Error al actuaizar la btacora".phpmkr_error()."sql:".$sqlBitacora);
	header("Location: php_condonacionlist.php");
	}
	

$filter['x_crenum_srch'] = '';
$filter['x_tipo_srch'] = '';
#echo "rol id".$_SESSION["php_project_esf_status_UserRolID"]."<br>";

//print_r($_POST);
//print_r($_POST);






?>

<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
<script language="javascript" src="concilia_conevio_liquidacion.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
    <script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
   <script>
  
//Cuando el DOM esté descargado por completo:
$(document).ready(function(){	
	$(".cambia_fondo").click(function(event) {
		var capa_credito_id = this.id;		
		var myarr = capa_credito_id.split(".");
		var id = myarr[1];
		var capa = myarr[0];
		
		//alert("el fondeo del credito se cambiara"+ capa_credito_id);
		//alert("capa"+capa);
		//alert("id"+id);
                    $("#capa_fondo_"+id).load('cambia_credito_fondo.php?x_credito_id='+id);
                });
	
	$("#x_tipo_srch").change(function(){
		if($( this ).val()==6 ){
			$("#msgid1").show("slow")
			}else{
				$("#msgid1").hide("slow")
				}
		
		});
	$('#x_mensaje').blur(function(){
		var men_length = $(this).val().length;		
		var restantes =  114 -men_length;
		$('#longitud_mensaje').text("Caracteres disponibles "+ restantes + "  evite caracteres especiales y tildes");
		});	
		
	});
	
	
	

   </script> 


<?php } ?>
<?php
#echo $sSql;
// Set up Record Set
//$rs = phpmkr_query($sSql,$conn)or die(phpmkr_error().$sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records

	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Bitacora de condonaci&oacute;n<?php if ($sExport == "") { ?><?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<p>
  <?php } ?>
  <?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<p></p>



<form action="" name="ewpagerform" id="ewpagerform" method="post">
<input type="hidden" name="x_condonacion_id" id="x_condonacion_id" value="<?php echo $_GET["x_condonacion_id"];?>" " />
<table width="640" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td width="280">&nbsp;</td>
	  <td width="46">&nbsp;</td>
	  <td width="226">&nbsp;</td>
	  <td width="60">&nbsp;</td>
	  <td width="28">&nbsp;</td>
	  </tr>
	<tr>
	  <td>N&uacute;mero cr&eacute;dito</td>
	  <td>&nbsp;</td>
	  <td><?php echo $_GET["x_credito_num"];?></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="4"><div id="longitud_mensaje"></div></td>
	  </tr>    
    <tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>  
      
	<tr id="msgid1">
	  <td height="66">Explique la razon de la condonacion</td>
	  <td>&nbsp;</td>
	  <td><textarea name="x_bitacora" id="x_bitacora"  cols="60" rows="6"> </textarea></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
      <tr>
	  <td><span class="phpmaker">
	    <input type="submit" name="Submit" value="Registrar" />
	  </span></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
      <tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
</table>




<table class="ewTablePager">
	<tr>
		<td nowrap>&nbsp;</td>
	</tr>
</table>
</form>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for Ctrl pressed
	if (strlen(@$_GET["ctrl"]) > 0) {
		$bCtrl = true;
	}
	else
	{
		$bCtrl = false;
	}

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field convenio_liquidacion_id
		if ($sOrder == "convenio_liquidacion_id") {
			$sSortField = "`convenio_liquidacion_id`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = "" ; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_credito_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_credito_id_Sort"] = "" ; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_monto_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_monto_Sort"] = "" ; }
		}

		// Field status
		if ($sOrder == "status") {
			$sSortField = "`status`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_status_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_status_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_status_Sort"] = "" ; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_fecha_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_Sort"] = "" ; }
		}

		// Field fecha_modificacion
		if ($sOrder == "fecha_modificacion") {
			$sSortField = "`fecha_modificacion`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["convenio_liquidacion_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["convenio_liquidacion_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["convenio_liquidacion_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["convenio_liquidacion_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
	}
	elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["convenio_liquidacion_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["convenio_liquidacion_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["convenio_liquidacion_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["convenio_liquidacion_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
			if (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_credito_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_monto_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_monto_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_status_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_status_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_fecha_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
	}
}
?>
