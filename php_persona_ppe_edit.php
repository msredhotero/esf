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
$reporte_cnbv_puesto_ppe_id = Null; 
$oreporte_cnbv_puesto_ppe_id = Null;
$x_usuario_rol_id = Null; 
$ox_usuario_rol_id = Null;
$x_usuario_status_id = Null; 
$ox_usuario_status_id = Null;
$x_usuario = Null; 
$ox_usuario = Null;
$x_clave = Null; 
$ox_clave = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_caduca = Null; 
$ox_fecha_caduca = Null;
$x_fecha_visita = Null; 
$ox_fecha_visita = Null;
$x_visitas = Null; 
$ox_visitas = Null;
$x_email = Null; 
$ox_email = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$reporte_cnbv_puesto_ppe_id = @$_GET["id"];

//if (!empty($reporte_cnbv_puesto_ppe_id )) $reporte_cnbv_puesto_ppe_id  = (get_magic_quotes_gpc()) ? stripslashes($reporte_cnbv_puesto_ppe_id ) : $reporte_cnbv_puesto_ppe_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_reporte_cnbv_puesto_ppe_id = @$_POST["reporte_cnbv_puesto_ppe_id"];
	$x_origen_lista = @$_POST["x_origen_lista"];
	$x_id_dependencia = @$_POST["x_id_dependencia"];
	$x_puesto = @$_POST["x_puesto"];
	
}

// Check if valid key
if (($reporte_cnbv_puesto_ppe_id == "") || (is_null($reporte_cnbv_puesto_ppe_id))) {
	ob_end_clean();
	header("Location: php_persona_ppelist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se locaizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_persona_ppelist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_persona_ppelist.php");
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
if (EW_this.x_usuario_rol_id && !EW_hasValue(EW_this.x_usuario_rol_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_rol_id, "SELECT", "Los permisos de lusuario son requeridos."))
		return false;
}
if (EW_this.x_usuario_status_id && !EW_hasValue(EW_this.x_usuario_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_status_id, "SELECT", "El status es requerido."))
		return false;
}
if (EW_this.x_usuario && !EW_hasValue(EW_this.x_usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario, "TEXT", "La cuenta es requerida."))
		return false;
}
if (EW_this.x_clave && !EW_hasValue(EW_this.x_clave, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_clave, "TEXT", "La clave es requerida."))
		return false;
}
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre es requerido."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false; 
}
if (EW_this.x_fecha_caduca && !EW_hasValue(EW_this.x_fecha_caduca, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_caduca, "TEXT", "La fecha de caducidad es requerida."))
		return false;
}
if (EW_this.x_fecha_caduca && !EW_checkeurodate(EW_this.x_fecha_caduca.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_caduca, "TEXT", "La fecha de caducidad es requerida."))
		return false; 
}
if (EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El email es requerido."))
		return false;
}
if (EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El email es requerido."))
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
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">DEPENDENCIAS Y PUESTOS DE PERSONA POL&Iacute;TICAMENTE EXPUESTA<br>
  <br>
    <a href="php_persona_ppelist.php">Regresar a la lista</a></span></p>
<form name="usuarioedit" id="usuarioedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="x_reporte_cnbv_puesto_ppe_id" id="x_reporte_cnbv_puesto_ppe_id" value="<?php echo $x_reporte_cnbv_puesto_ppe_id ?>" />
<input type="hidden" name="a_edit" value="U">
<table width="394">
	
     <tr>
	  <td width="126" class="ewTableHeaderThin"><span>Origen lista</span></td>
	  <td width="256" class="ewTableAltRow"><span>
      <select name="x_origen_lista" id="x_origen_lista"  >
        <option value="0">Seleccione</option>
        <option value="1" <?php  if($x_origen_lista == 1) {?> selected="selected" <?php }?>>Lista CNBV</option>
        <option value="2" <?php  if($x_origen_lista == 2) {?> selected="selected" <?php }?>>Lista CREA</option>
      </select>
      </span></td>
	  </tr>
            
      <tr>
	  <td width="126" class="ewTableHeaderThin" ><span>Dependencia<br /></span></td>
	  <td width="256" class="ewTableAltRow"><span>
  
  <?php
		$x_entidad_idList = "<select name=\"x_id_dependencia\"  id=\"x_id_dependencia\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `id_dependencia`, `dependencia` FROM `reporte_cnbv_puestos_ppe` GROUP BY id_dependencia,dependencia ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["id_dependencia"] == @$x_id_dependencia) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["dependencia"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>
  </span></td>
	  </tr>
      <tr>
	  <td width="126" class="ewTableHeader" ><span>Puesto</span></td>
	  <td width="256" class="ewTableAltRow"><span>
      <textarea name="x_puesto" id="x_puesto" cols="80" rows="2"><?php echo htmlspecialchars(@$x_puesto) ?></textarea>
  
  </span></td>
	  </tr>
	
</table>
<p>
<input type="submit" name="Action" value="Editar">
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
	global $reporte_cnbv_puesto_ppe_id;
	$sSql = "SELECT * FROM `reporte_cnbv_puestos_ppe`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($reporte_cnbv_puesto_ppe_id) : $reporte_cnbv_puesto_ppe_id;
	$sWhere .= "(`reporte_cnbv_puesto_ppe_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_reporte_cnbv_puesto_ppe_id"] = $row["reporte_cnbv_puesto_ppe_id"];		
		$GLOBALS["x_origen_lista"] = $row["origen_lista"];
		$GLOBALS["x_id_dependencia"] = $row["id_dependencia"];
		$GLOBALS["x_puesto"] = $row["puesto"];
		
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
	global $reporte_cnbv_puesto_ppe_id;
	$sSql = "SELECT * FROM `reporte_cnbv_puestos_ppe`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($reporte_cnbv_puesto_ppe_id) : $reporte_cnbv_puesto_ppe_id;	
	$sWhere .= "(`reporte_cnbv_puesto_ppe_id` = " . addslashes($sTmp) . ")";
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
		
		
		
		
		$theValue = ($GLOBALS["x_origen_lista"] != "") ? intval($GLOBALS["x_origen_lista"]) : "0";
		$fieldList["`origen_lista`"] = $theValue;
		
		$theValue = ($GLOBALS["x_id_dependencia"] != "") ? intval($GLOBALS["x_id_dependencia"]) : "0";
		$fieldList["`id_dependencia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_dependencia"]) : $GLOBALS["x_dependencia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`dependencia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_puesto"]) : $GLOBALS["x_puesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`puesto`"] = $theValue;
		
		// update
		$sSql = "UPDATE `reporte_cnbv_puestos_ppe` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		
		echo $sSql ."<br>";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		$sqlSelect = " SELECT dependencia FROM  reporte_cnbv_puestos_ppe WHERE id_dependencia =  ".$GLOBALS["x_id_dependencia"]." ";
		$rs1 = phpmkr_query($sqlSelect,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row1 = @phpmkr_fetch_array($rs1);
		$x_nom_dep = $row1["dependencia"];
		
		$SQLup = "UPDATE `reporte_cnbv_puestos_ppe` SET  `dependencia` = '".$x_nom_dep."' WHERE ".$sWhere;
		
		phpmkr_query($SQLup,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
