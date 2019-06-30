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
$x_ppe_listado_id = Null; 
$ox_ppe_listado_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_ppe_listado_id = @$_GET["ppe_listado_id"];

//if (!empty($x_ppe_listado_id )) $x_ppe_listado_id  = (get_magic_quotes_gpc()) ? stripslashes($x_ppe_listado_id ) : $x_ppe_listado_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_ppe_listado_id = @$_POST["x_ppe_listado_id"];
	$status = @$_POST["status"];
	$comentarios_oficial_cumplimiento = @$_POST["comentarios_oficial_cumplimiento"];
}

// Check if valid key
if (($x_ppe_listado_id == "") || (is_null($x_ppe_listado_id))) {
	ob_end_clean();
	header("Location: php_ppe_listadolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_ppelist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_ppelist.php");
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
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripción es requerida."))
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
<p><span class="phpmaker">PERSONA POL&Iacute;TICAMENTE EXPUESTA<br>
  <br>
    <a href="php_ppelist.php">Regresar a la lista</a></span></p>
<form name="ppe_listadoedit" id="ppe_listadoedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable_small">
	<tr>
		<td width="160" class="ewTableHeaderThin"><span>ID</span></td>
		<td width="846" class="ewTableAltRow"><span>
<?php echo $x_ppe_listado_id; ?>
<input type="hidden" id="x_ppe_listado_id" name="x_ppe_listado_id" value="<?php echo htmlspecialchars(@$x_ppe_listado_id); ?>">
</span></td>
	</tr>
    
    
	<tr>
		<td class="ewTableHeaderThin"><span>Promotor</span></td>
		<td class="ewTableAltRow"><span><?php echo $nombre_promotor;?>

</span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro del evento</span></td>
		<td class="ewTableAltRow"><span><?php echo $fecha;?>

</span></td>
	</tr>
    
      
    
    <tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<select name="status" id="status" >
        <option value="0">Seleccione</option>
        <option value="1" <?php  if($status == 1) {?> selected="selected" <?php }?> >Nuevo</option>
        <option value="2" <?php  if($status == 2) {?> selected="selected" <?php }?>>Atendido por Oficial de Cumplimiento</option>
      </select>
</span></td>
	</tr>
    
    
    
   <tr>
		<td class="ewTableHeaderThin"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span><?php echo $x_cliente;?></span></td>
	</tr>
    
    
   
    
     <tr>
		<td class="ewTableHeaderThin"><span>Puesto reportado</span></td>
		<td class="ewTableAltRow"><span><?php echo strtoupper($puesto);?></span></td>
	</tr>
    <tr>
		<td class="ewTableHeaderThin"><span>Dependencia</span></td>
		<td class="ewTableAltRow"><span><?php echo strtoupper($dependencia);?></span></td>
	</tr>
    
    <tr>
		<td class="ewTableHeader"><span>Observaciones Oficial de Cumplimiento</span></td>
		<td class="ewTableAltRow"><span>
        <textarea name="comentarios_oficial_cumplimiento"  id="comentarios_oficial_cumplimiento" rows="2" cols="80"><?php echo $comentarios_oficial_cumplimiento;?></textarea>
</span></td></tr>

<tr>
		<td class="ewTableHeaderThin"><span>Usuario que registro el evento</span></td>
		<td class="ewTableAltRow"><span><?php echo $nombre_usuario;?> </span></td>
	
	</tr>
</table>
<p>
<input type="submit" name="Action" value="EDITAR">
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
	global $x_ppe_listado_id;
	$sSql = "SELECT * FROM `ppe_listado`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_ppe_listado_id) : $x_ppe_listado_id;
	$sWhere .= "(`ppe_listado_id` = " . addslashes($sTmp) . ")";
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

		foreach($row as $nombre => $valor){
			$$nombre = $valor;
			$GLOBALS[$nombre] = $valor;
			
			}
		$sqlCredito = "SELECT   cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud_cliente, cliente WHERE   cliente.cliente_id = solicitud_cliente.cliente_id and solicitud_id = ".$solicitud_id;
		$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error al seleccionar credito".phpmkr_error().$sqlCredito);
		$rowCredito = phpmkr_fetch_array($rsCredito);
		foreach($rowCredito as $nombre => $valor){
			$$nombre = $valor;
			}
			$GLOBALS["x_cliente"] = $nombre_completo." ".$apellido_paterno." ".$apellido_materno;
		$sqlPromotor = "SELECT p.nombre_completo as nombre_promotor FROM promotor as p, solicitud as s WHERE p.promotor_id = s.promotor_id and s.solicitud_id = ".$solicitud_id;
		$rsPromotor = phpmkr_query($sqlPromotor,$conn)or die ("Error al seleccionar credito".phpmkr_error().$sqlCredito);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		foreach($rowPromotor as $nombre => $valor){
			$GLOBALS[$nombre] = $valor;
			}
			
			
		$sqlPromotor = "SELECT u.nombre as nombre_usuario FROM usuario as u, ppe_listado as l WHERE u.usuario_id = l.id_usuario_registro and l.solicitud_id = ".$solicitud_id;
		$rsPromotor = phpmkr_query($sqlPromotor,$conn)or die ("Error al seleccionar credito".phpmkr_error().$sqlCredito);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		foreach($rowPromotor as $nombre => $valor){
			$GLOBALS[$nombre] = $valor;
			
			}
			
		$sqlPromotor = "SELECT p.puesto, p.dependencia  FROM 	reporte_cnbv_puestos_ppe as p, ppe_listado as l WHERE p.reporte_cnbv_puesto_ppe_id = l.	reporte_cnbv_puesto_ppe_id and l.solicitud_id = ".$solicitud_id;
		$rsPromotor = phpmkr_query($sqlPromotor,$conn)or die ("Error al seleccionar credito".phpmkr_error().$sqlCredito);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		foreach($rowPromotor as $nombre => $valor){
			$GLOBALS[$nombre] = $valor;
			
			}
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
	global $x_ppe_listado_id;
	$sSql = "SELECT * FROM `ppe_listado`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_ppe_listado_id) : $x_ppe_listado_id;	
	$sWhere .= "(`ppe_listado_id` = " . addslashes($sTmp) . ")";
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["status"]) : $GLOBALS["status"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`status`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["comentarios_oficial_cumplimiento"]) : $GLOBALS["comentarios_oficial_cumplimiento"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentarios_oficial_cumplimiento`"] = $theValue;

		// update
		$sSql = "UPDATE `ppe_listado` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		//echo $sSql;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	
	return $bEditData;
}
?>
