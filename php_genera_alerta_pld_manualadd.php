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
$x_informacion_extra_id = Null;
$x_cliente_id = Null;
$x_solicitud_id = Null;
$x_acta_numero = Null;
$x_fecha_acta = Null;
$x_nombre_licenciado = Null;
$x_numero_notaria = Null;
$x_estado = Null;
$x_numero_folio_mercantil = Null;
$x_fecha_folio_mercantil = Null;
$x_estado_registro = Null;
$x_escritura_numero = Null;
$x_fecha_escritura = Null;
$x_e_nombre_licenciado = Null;
$x_e_numero_notaria = Null;
$x_e_estado = Null;
$x_rfc = Null;
$x_copia = Null;
$x_copia_no = Null;
$x_dia_pago = Null;
$x_fecha_corte = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$sKey  = $_GET["solicitud_id"];
$regresa = $_GET["pagina_regresa"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = $_GET["solicitud_id"];
	}	
	
		
		$x_regresa_pm_cc = $_GET["x_pm_cc"];
	
$_SESSION["ewmsg"] = "";	
// Get action
$sAction = @$_POST["a_add"];

$pagina_regresa = $_POST["x_regresa"];
if (($sAction == "") || ((is_null($sAction)))) {
	#$sKey = @$_GET["key"];
	$sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;
	if ($sKey <> "") {
		$sAction = "C"; // Copy record
	}
	else
	{
		$sAction = "I"; // Display blank record
	}
}
else
{

	
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB,PORT);
#echo "action".$sAction;
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_informacion_extralist.php");
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "SU AVISO SOBRE LA SOLICITUD HA SIDO ENVIADA AL OFICIAL DE CUMPLIMIENTO, ESTA SOLICITUD PERMANECERA BLOQUEDAD HASTA QUE UN SUPERIOR CAMBIE SU ESTATUS";
			phpmkr_db_close($conn);
			ob_end_clean();
			if($pagina_regresa==1){
			header("Location: php_solicitudlist_nomina.php");
			}else{
				header("Location: php_solicitudlist_moral.php");
				}
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />

<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
	



	var confirmacion = confirm("Al guardar esta alerta de sistema, la solicitud cambiara su estatus a bloqueada y solo un superior podra cambiar el status");
if(confirmacion){
	
	if (EW_this.x_fecha_corte && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "Debe especificar el motivo de la alerta"))
		return false;
	else
	return true;	
}

	
} else {
	alert("El aviso no se registro");
}


}

//-->
</script>
<p><span class="phpmaker">Enviar un alerta al oficial de cumplimiento.<br><br>



<?php
//<form name="informacion_extraadd" id="informacion_extraadd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
?>


</span></p>
<form name="informacion_extraadd" id="informacion_extraadd" action="" >
<p>
<input type="hidden" name="a_add" value="A">
<span class="ewTableAltRow">
<input type="hidden" name="x_cliente_id" id="x_cliente_id" size="30" value="<?php echo htmlspecialchars(@$x_cliente_id) ?>" />
</span>
<input type="hidden" name="x_regresa" id="x_regresa" value="<?php echo $regresa;?>" />
<span class="ewTableAltRow">
<input type="hidden" name="x_solicitud_id" id="x_solicitud_id" size="30" value="<?php echo htmlspecialchars(@$x_solicitud_id) ?>" />
</span>
<?php
echo $_SESSION["ewmsg"];
?>
<table width="745" class="ewTable_small">
	
      <tr>
	  <td class="ewTableHeader" colspan="2"><span>Generar alerta para oficial de cumplimiento(Internas Preocupantes)</span></td>
	
	  </tr>
      <tr>
	  <td width="167" class="ewTableHeader">Fecha de registro</td>
	  <td width="621" class="ewTableAltRow"><span><?php echo $x_fecha_registro;?>11-02-2018
  <input type="hidden" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo htmlspecialchars(@$x_fecha_registro) ?>">
  </span></td>
	  </tr>
	<tr>
		<td class="ewTableHeader">usuario</td>
		<td class="ewTableAltRow">ADMINISTRADOR<?php echo $x_nombre_usuario;?><input type="hidden" name="x_usuario_id" id="x_usuario_id" value="<?php echo htmlspecialchars(@$x_usuario_id) ?>">

</td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>zulma(prueba) ortiz anzurez<?php echo $x_nombre_cliente;?>


</span></td>
	</tr>
	
	
    
   
    <tr>
	  <td width="167" class="ewTableHeader"><span>Motivo de la Alerta</span></td>
	  <td width="621" class="ewTableAltRow"><span>
 <textarea rows="6" cols="80" name="x_descripcion" id="x_descripcion" >Aqui se explica el motivo de la alerta para que el oficial de cumplimiento la revise y proceda o no con el reporte a la CNBV<?php echo $x_descripcion; ?></textarea>
  </span></td>
	  </tr>
      
      
    
</table>
<p>
<?php if (empty($_SESSION["ewmsg"])){?>
<input type="submit" value="Guardar">
<?php }
?>
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

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `solicitud_cliente`";
	$sSql .= " WHERE `solicitud_id` = " . $sKeyWrk;
	
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		
		$GLOBALS["x_fecha_registro"] = date("Y-m-d");
		
		
		$sSql2 = "SELECT * FROM `cliente`";
		$sSql2 .= " WHERE `cliente_id` = " . $row["cliente_id"];	
		$rs2 = phpmkr_query($sSql2,$conn);
		
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_nombre_cliente"] = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
		
		$sSql2 = "SELECT * FROM `usuario`";
		$sSql2 .= " WHERE `usuario_id` = " . $_SESSION["php_project_esf_status_UserID"];	
		$rs2 = phpmkr_query($sSql2,$conn);
		
		
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_nombre_usuario"] = $row2["nombre"];
		$GLOBALS["x_usuario_id"] = $_SESSION["php_project_esf_status_UserID"];
		 
		
		
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

	die();
	// Field cliente_id
	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $theValue;

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;
// Field solicitud_id
	$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
	$fieldList["`usuario_id`"] = $theValue;

	// Field fecha_acta
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field nombre_licenciado
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`descripcion`"] = $theValue;
	
	$theValue = ($GLOBALS["x_status_id"] != "") ? intval($GLOBALS["x_status_id"]) : "NULL";
	$fieldList["`status_id`"] = 1;

	
	// insert into database
	$strsql = "INSERT INTO `alerta_pld_manual` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn) or die ("error al insertar en eiformacion extra". phpmkr_error()."sql".$strsql);
	
	// revisamos el estatus de la solcitud, y lo cambiamos a bloqueda solo si no hay credito activo
	$sSql2 = "SELECT solicitud_status_id FROM `solicitud`";
	$sSql2 .= " WHERE `solicitud_id` = " . $GLOBALS["x_solicitud_id"];	
	$rs2 = phpmkr_query($sSql2,$conn);
	
	$row2 = phpmkr_fetch_array($rs2);
	$x_solicitud_status_id = $row2["solicitud_status_id"];
	if($x_solicitud_status_id <= 3){
		
		$SQLup = "UPDATE solicitud SET 	solicitud_status_id = 9 WHERE `solicitud_id` = " . $GLOBALS["x_solicitud_id"];
		$rs2UP = phpmkr_query($SQLup,$conn);
		
		echo $SQLup;
		if(!$rs2UP) die("error al actualiza la solictu".$SQLup);
		}
		
	
	return true;
}
?>
