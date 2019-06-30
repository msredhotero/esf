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

$filter = array();


$filter['x_crenum_srch'] = '';
$filter['x_tipo_srch'] = '';


#echo "rol id".$_SESSION["php_project_esf_status_UserRolID"]."<br>";

//print_r($_POST);
//print_r($_POST);

if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}	
}


if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}		
}


if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret    = array();
            foreach((array)$data as $k => $v) {
                $k    = urlencode($k);
                if(is_int($k) && $prefix != null) {
                    $k    = $prefix.$k;
                };
                if(!empty($key)) {
                    $k    = $key."[".$k."]";
                };

                if(is_array($v) || is_object($v)) {
                    array_push($ret,http_build_query($v,"",$sep,$k));
                }
                else {
                    array_push($ret,$k."=".urlencode($v));
                };
            };

        if(empty($sep)) {
            $sep = ini_get("arg_separator.output");
        };

        return    implode($sep, $ret);
    };
};

if(!empty($filter["x_tipo_srch"])){
	//echo "enra a filro sucursal";
		if((!empty($filter["x_tipo_srch"])) && ($filter["x_tipo_srch"] != "1000")){
			$ssrch_cre .= "(id_tipo_mensaje = ".$filter["x_tipo_srch"].") AND ";
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
			
			switch($filter["x_tipo_srch"]){				
				case 1:
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_status_id != 2";
				break;
				case 2:
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_status_id = 1";
				break;
				case 3:
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_status_id = 1 or credito_status_id = 4 ";
				break;
				case 4:
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_id in (Select credito_id from vencimiento where vencimiento_status_id =  3 group by credito_id) ";
				break;
				case 5:
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_id in (Select credito_id from vencimiento where vencimiento_status_id =  3 group by credito_id) ";
				break;
				case 6:
						$x_listado_de_credito = trim($_POST["x_numero_creditos"],",");
						$sqlS ="SELECT solicitud_id, credito_num FROM credito WHERE credito_num in ( ".$x_listado_de_credito.")";
						$sqlErroLis ="<strong><br><br>VERIFIQUE EL LISTADO DE CREDITOS <br><br> </strong>".$x_listado_de_credito;
				break;		
				
				}
				//echo $sqlS."<br>";
			if(!empty($sqlS)){
			$rsS = phpmkr_query($sqlS,$conn) or die ("Error al seleccionar los creditos".$sqlS."<br> ".$sqlErroLis);
			$solicitudes = array();
			while($rowS = phpmkr_fetch_array($rsS)){
				$solicitudes[$rowS["credito_num"]]= $rowS["solicitud_id"];
				}
				
			}
			
			
			//print_r($solicitudes);
			//echo "Listado de sol ".$x_listado_sol;
			//die();
			foreach($solicitudes as $credito =>$solicitud){
				// se ace el envio
				// seleccionamos los datos del cliente
				$sqlDatosCliente = "SELECT cliente.cliente_id,email FROM cliente JOIN solicitud_cliente ON solicitud_cliente.cliente_id = cliente.cliente_id AND solicitud_cliente.solicitud_id = $solicitud ";
				$rsDatosCliente =  phpmkr_query($sqlDatosCliente,$conn) or die("Error cliente".phpmkr_error().$sqlDatosCliente);
				$rowDatosCliente = phpmkr_fetch_array($rsDatosCliente);
				$x_cliente_id = $rowDatosCliente["cliente_id"];
				$email = $rowDatosCliente["email"];
				//echo $x_email.", ";
				if(!empty($x_cliente_id)){
				// seleccionamos el telefono
				//echo $x_cliente_id.",";
				$sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_id  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
				$rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
					$rowCelular = phpmkr_fetch_array($rsCelular);
					$x_no_celular = $rowCelular["numero"];
					
					//if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = $_POST["x_mensaje"];	
						//echo "mensaje en ost".$_POST["x_mensaje"];											
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";				
						
						// Mail it
						//mail($para, $titulo, $x_mensaje, $cabeceras);						
						$x_fecha_sms = date("Y-m-d");
						//$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino` , `cliente_id`) VALUES (NULL, '100','" .$x_mensaje."', $credito, '".$x_no_celular."', '".$x_fecha_sms."', '2', '1',$x_cliente_id)";
	
	
	//$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);												
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		//    echo "Esta dirección de correo ($email_a) es válida.";
		//mail($email, $titulo2, $mensajeMail, $cabeceras2);	
		$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '100','" .$mensajeMail."', $credito, '".$email."', '".$x_fecha_sms."', '2', '1',$x_cliente_id)";
		//$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
		//}	
		$_SESSION["ewmsg"] = "- EMAIL ENVIADO -";
		echo $sqlInsertsms ."<BR>";
		}	
		
					
				}//cliente_id
				}
			
			
				
		}
	}
	
if(!empty($filter["x_crenum_srch"])){
			$ssrch_cre .= "( credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
		}	



$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere;


// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
	 
$sSql = "SELECT * FROM sms_enviados  "	; 

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere;
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

$sSql .= "  order by id_sms_enviado desc ";
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
		$('#longitud_mensaje').text("Caracteres escritos "+ men_length + "  evite caracteres especiales y tildes");
		});	
		
	});
	
	
	

   </script> 


<?php } ?>
<?php
#echo $sSql;
// Set up Record Set
$rs = phpmkr_query($sSql,$conn)or die(phpmkr_error().$sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records

	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Envio masivo de E-mail
  <?php if ($sExport == "") { ?><?php } ?>
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





<form action="" name="ewpagerform" id="ewpagerform" method="post">
<table width="640" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td width="280">&nbsp;</td>
	  <td width="46">&nbsp;</td>
	  <td width="226">&nbsp;</td>
	  <td width="60">&nbsp;</td>
	  <td width="28">&nbsp;</td>
	  </tr>
	<tr>
	  <td>Enviar a</td>
	  <td>&nbsp;</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_tipo_srch\" id=\"x_tipo_srch\" class=\"texto_normal\">";
			$sSqlWrk = "SELECT sms_envio_masivo_id, descripcion FROM sms_envio_masivo ";
			$x_estado_civil_idList .= "<option value=\"1000\">Seleccione</option>";	
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sms_envio_masivo_id"] == $filter["x_tipo_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
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
	  <td>Mensaje </td>
	  <td>&nbsp;</td>
	  <td><textarea name="x_mensaje" id="x_mensaje" cols="150" rows="10"> Escriba el E-mail aqui</textarea></td>
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
	  <td>N&uacute;mero de cr&eacute;dito</td>
	  <td>&nbsp;</td>
	  <td><textarea name="x_numero_creditos" id="x_numero_creditos"  cols="60" rows="4"> Listado de cr&eacute;ditos separados por "," ejemplo 4356,2345,7654</textarea></td>
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
	    <input type="submit" name="Submit" value="Enviar Mensaje" />
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
