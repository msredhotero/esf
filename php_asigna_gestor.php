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
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_status_id = Null; 
$ox_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// Handle Reset Command
ResetCmd();
#echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script src="lineafondeohint.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFFF'; // row color
var rowaltcolor = '#F5F5F5'; // row alternate color
var rowmovercolor = '#FFCCFF'; // row mouse over color
var rowselectedcolor = '#CCFFFF'; // row selected color
var roweditcolor = '#FFFF99'; // row edit color

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<script type="text/javascript">
<!--
function EW_selectKey(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			f.elements["key_d[]"][i].checked = elem.checked;	
	} else {
		f.elements["key_d[]"].checked = elem.checked;	
	}
	if (f.elements["checkall"])
	{
		if (f.elements["checkall"][0])
		{
			for (var i = 0; i<f.elements["checkall"].length; i++)
				f.elements["checkall"][i].checked = elem.checked;
		} else {
			f.elements["checkall"].checked = elem.checked;
		}
	}
	ew_clickall(elem);
}
function EW_selected(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return false;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			if (f.elements["key_d[]"][i].checked) return true;
	} else {
		return f.elements["key_d[]"].checked;
	}
	return false;
}

//-->
</script>

<p><span class="phpmaker">Asigna Gestor a Credito <?php if ($sExport == "") { ?>
<?php if ($sExport == "") { ?> <?php } ?>
<?php } ?>
</span></p>
<?php if ($sExport == "") {} ?>
<?php if ($sExport == "") { ?>


<p>
<?php } ?>

<?php 

$x_asignaGestor = $_POST["asignaGestor"];


if(!empty($x_asignaGestor)){
	// se hace la signacion del gestor a los creditos
	$x_gestor = $_POST["x_gestor"];
	$x_listado_creditos = $_POST["x_listado_creditos"];
	$x_mensaje = "Los creditos fueron asignados al gestor";	
	echo "LISTADO DE CREDITOS: ".$x_listado_creditos ."<BR>";
	// pasamos el listado de credito a un arreglo para poder trabajar con cada numero de credito;
	
	$x_array_cred = explode(",",$x_listado_creditos);
	
	foreach($x_array_cred as $x_no_credito){
		
		// seleccionamos el credito id que corresponda con el numero de credito.
		$x_n_c = trim($x_no_credito);
		echo "credito num".$x_n_c."<br>";
		$sqlCID = "SELECT credito_id FROM credito WHERE credito_num = $x_n_c ";
		$rsCID = phpmkr_query($sqlCID, $conn) or die ("Error al seleccionar el credito_id". phpmkr_error()."sql :".$sqlCID);
		$rowCID = phpmkr_fetch_array($rsCID);
		$x_credito_id = $rowCID["credito_id"];
		echo $sqlCID."<br> credito_id ".$x_credito_id."<br>";
		#verifcamos si esxiste ese credito id en el listadado de credito gestor, s existe se hara un update , si no existe se hra un insert
		 
		$sqlCID2 = "SELECT credito_id FROM gestor_credito WHERE credito_id = $x_credito_id";
		$rsCID2 = phpmkr_query($sqlCID2, $conn) or die ("Error al seleccionar el credito_id". phpmkr_error()."sql :".$sqlCID2);
		$x_c_e = mysql_num_rows($rsCID2);
		
		if($x_c_e > 0){
			// si es mayo de 0 ya existe el registro y solo se actuliza el campo getor
				$sqlUpdate = "UPDATE gestor_credito SET usuario_id = $x_gestor WHERE  credito_id = $x_credito_id";
				$rsUP = phpmkr_query($sqlUpdate, $conn) or die ("Error al seleccionar el credito_id". phpmkr_error()."sql :".$sqlUpdate);			
				#echo "Se actualiza";
				
				// se debe de actulizar la ultima tarea registrada para ese credito 
				$sqlUltimaTarea = "SELECT * FROM crm_caso where credito_id = $x_credito_id and crm_caso_tipo_id = 3 ";
				$rsUltimaTarea = phpmkr_query($sqlUltimaTarea,$conn)or die ("Error al seleccionar el caso".phpmkr_error().$sqlUltimaTarea);
				$rowUltimaTarea = phpmkr_fetch_array($rsUltimaTarea);
				$x_crm_caso_id = $rowUltimaTarea["crm_caso_id"];
				
				if(!empty($x_crm_caso_id)){
					//seleccionamos la ultima tarea
					$sqlUT = "SELECT crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id order by crm_tarea_id DESC LIMIT 0,1 ";
					$rsUT = phpmkr_query($sqlUT,$conn)or die ("Error al seleccionar la ultima tarea".phpmkr_error().$sqlUT);
					$rowUT = phpmkr_fetch_array($rsUT);
					$x_crm_tarea_id = $rowUT["crm_tarea_id"];
					
					}
					if (!empty($x_crm_tarea_id)){
							$sqlUpdate = "UPDATE crm_tarea SET destino = $x_gestor WHERE  crm_tarea_id = $x_crm_tarea_id";
							$rsUP = phpmkr_query($sqlUpdate, $conn) or die ("Error al actualizar la tarea con el nuevo destino". phpmkr_error()."sql :".$sqlUpdate);
						}
			
			}else{
				// no existe el regstro se inserta en la tabla gestor credito
				
				$sqlInsert = "INSERT INTO gestor_credito VALUES(NULL,$x_credito_id, $x_gestor)";
				$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al insertar en gestor credito". phpmkr_error()."sql :". $sqlInsert);
				#echo "Se inserta";
				
				}
		
		}// foreach
	
	
	
	
	}
?>
<form id="form1" name="form1" method="post" action="">
<?php
if (@$x_mensaje <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $x_mensaje; ?></span></p>
<?php
	$x_mensaje = ""; // Clear message
}
?>

<input type="hidden" name="asignaGestor" value="1" />
  <table width="700" border="0">
    <tr>
      <td width="132">Gestor de caso:</td>
      <td width="446"><select name="x_gestor" >      
      <option value="7184">Angelica Tabares</option>
      <option value="7182">Marcela Lopez</option>
      <option value="7183">Josefina Ochoa</option>
      <option value="7187">Victoria Garcia</option>      
      <option value="7180">Rodrigo Sanchez</option>
      <option value="7179">Mauricio Trejo</option>
      <option value="7181">Javier Foncerrada</option>
      <option value="7188">Cesar Olvera</option>
      <option value="7203">Cristian Castellon</option>
      <option value="7202"> Jose Luis Trejo </option>
      </select>
       </td>
      <td width="51">&nbsp;</td>
      <td width="53">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Numeros de credito:</td>
      <td><textarea name="x_listado_creditos" rows="10" cols="50">Inserte el listado de numeros de credito separados por coma ","</textarea></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="right"> <input type="submit" value="Asignar gestor" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{

$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	
$x_entero = intval($sKeyword);

	$BasicSearchSQL = "";
	if($x_entero > 0){

	$BasicSearchSQL.= "credito.credito_num LIKE '%" . $sKeyword . "%' OR ";
	
	}else{
/*
	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";	
	$BasicSearchSQL.= "cliente.apellido_paterno LIKE '%" . $sKeyword . "%' OR ";	
	$BasicSearchSQL.= "cliente.apellido_materno LIKE '%" . $sKeyword . "%' OR ";	
*/	
	}
//	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";	
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic;
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$_POST["psearch"]) : @$_POST["psearch"];
	$sSearchType = @$_GET["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "credito.credito_num+0";
			$sLastSort = @$_SESSION["credito_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "ASC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_id_Sort"] <> "") { @$_SESSION["credito_x_credito_id_Sort"] = ""; }
		}

		// Field credito_id
		if ($sOrder == "cliente_id") {
			$sSortField = "credito.cliente_num+0";
			$sLastSort = @$_SESSION["credito_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "ASC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_cliente_id_Sort"] <> "") { @$_SESSION["credito_x_cliente_id_Sort"] = ""; }
		}

		// Field credito_tipo_id
		if ($sOrder == "credito_tipo_id") {
			$sSortField = "credito.credito_tipo_id";
			$sLastSort = @$_SESSION["credito_x_credito_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_tipo_id_Sort"] <> "") { @$_SESSION["credito_x_credito_tipo_id_Sort"] = ""; }
		}

		// Field credito_tipo_id
		if ($sOrder == "cliente") {
			$sSortField = "cliente.nombre_completo";
			$sLastSort = @$_SESSION["credito_x_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_cliente_Sort"] <> "") { @$_SESSION["credito_x_cliente_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "credito.solicitud_id";
			$sLastSort = @$_SESSION["credito_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_solicitud_id_Sort"] <> "") { @$_SESSION["credito_x_solicitud_id_Sort"] = ""; }
		}

		// Field credito_status_id
		if ($sOrder == "credito_status_id") {
			$sSortField = "credito.credito_status_id";
			$sLastSort = @$_SESSION["credito_x_credito_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_status_id_Sort"] <> "") { @$_SESSION["credito_x_credito_status_id_Sort"] = ""; }
		}

		// Field fecha_otrogamiento
		if ($sOrder == "fecha_otrogamiento") {
			$sSortField = "credito.fecha_otrogamiento";
			$sLastSort = @$_SESSION["credito_x_fecha_otrogamiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_fecha_otrogamiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] <> "") { @$_SESSION["credito_x_fecha_otrogamiento_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "credito.importe";
			$sLastSort = @$_SESSION["credito_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_importe_Sort"] <> "") { @$_SESSION["credito_x_importe_Sort"] = ""; }
		}

		// Field tasa
		if ($sOrder == "tasa") {
			$sSortField = "credito.tasa";
			$sLastSort = @$_SESSION["credito_x_tasa_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_tasa_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_tasa_Sort"] <> "") { @$_SESSION["credito_x_tasa_Sort"] = ""; }
		}

		// Field plazo
		if ($sOrder == "plazo") {
			$sSortField = "credito.plazo";
			$sLastSort = @$_SESSION["credito_x_plazo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_plazo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_plazo_Sort"] <> "") { @$_SESSION["credito_x_plazo_Sort"] = ""; }
		}

		// Field fecha_vencimiento
		if ($sOrder == "fecha_vencimiento") {
			$sSortField = "credito.fecha_vencimiento";
			$sLastSort = @$_SESSION["credito_x_fecha_vencimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_fecha_vencimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_fecha_vencimiento_Sort"] <> "") { @$_SESSION["credito_x_fecha_vencimiento_Sort"] = ""; }
		}

		// Field tasa_moratoria
		if ($sOrder == "tasa_moratoria") {
			$sSortField = "credito.tasa_moratoria";
			$sLastSort = @$_SESSION["credito_x_tasa_moratoria_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_tasa_moratoria_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_tasa_moratoria_Sort"] <> "") { @$_SESSION["credito_x_tasa_moratoria_Sort"] = ""; }
		}

		// Field medio_pago_id
		if ($sOrder == "medio_pago_id") {
			$sSortField = "credito.medio_pago_id";
			$sLastSort = @$_SESSION["credito_x_medio_pago_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_medio_pago_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_medio_pago_id_Sort"] <> "") { @$_SESSION["credito_x_medio_pago_id_Sort"] = ""; }
		}

		// Field referencia_pago
		if ($sOrder == "referencia_pago") {
			$sSortField = "credito..referencia_pago";
			$sLastSort = @$_SESSION["credito_x_referencia_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_referencia_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_referencia_pago_Sort"] <> "") { @$_SESSION["credito_x_referencia_pago_Sort"] = ""; }
		}
		$_SESSION["credito_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["credito_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["credito_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["credito_OrderBy"] = $sOrderBy;
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
		$_SESSION["credito_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["credito_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["credito_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["credito_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["credito_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["credito_REC"] = $nStartRec;
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
			$_SESSION["credito_searchwhere"] = $sSrchWhere;

			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";			
			

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["credito_searchwhere"] = $sSrchWhere;
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";			
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";						
			
		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["credito_OrderBy"] = $sOrderBy;
			if (@$_SESSION["credito_x_credito_id_Sort"] <> "") { $_SESSION["credito_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_cliente_id_Sort"] <> "") { $_SESSION["credito_x_cliente_id_Sort"] = ""; }
			
			if (@$_SESSION["credito_x_credito_tipo_id_Sort"] <> "") { $_SESSION["credito_x_credito_tipo_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_solicitud_id_Sort"] <> "") { $_SESSION["credito_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_credito_status_id_Sort"] <> "") { $_SESSION["credito_x_credito_status_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] <> "") { $_SESSION["credito_x_fecha_otrogamiento_Sort"] = ""; }
			if (@$_SESSION["credito_x_importe_Sort"] <> "") { $_SESSION["credito_x_importe_Sort"] = ""; }
			if (@$_SESSION["credito_x_tasa_Sort"] <> "") { $_SESSION["credito_x_tasa_Sort"] = ""; }
			if (@$_SESSION["credito_x_plazo_Sort"] <> "") { $_SESSION["credito_x_plazo_Sort"] = ""; }
			if (@$_SESSION["credito_x_fecha_vencimiento_Sort"] <> "") { $_SESSION["credito_x_fecha_vencimiento_Sort"] = ""; }
			if (@$_SESSION["credito_x_tasa_moratoria_Sort"] <> "") { $_SESSION["credito_x_tasa_moratoria_Sort"] = ""; }
			if (@$_SESSION["credito_x_medio_pago_id_Sort"] <> "") { $_SESSION["credito_x_medio_pago_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_referencia_pago_Sort"] <> "") { $_SESSION["credito_x_referencia_pago_Sort"] = ""; }
			if (@$_SESSION["credito_x_cliente_Sort"] <> "") { $_SESSION["credito_x_cliente_Sort"] = ""; }			
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["credito_REC"] = $nStartRec;
	}
}
?>
