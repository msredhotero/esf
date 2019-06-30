<?php session_start(); ?>
<?php ob_start(); ?>
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
$x_today = date("d/m/Y");
$x_vigencia =date("d/m/Y", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1)); 
// Initialize common variables
$x_membresia_id = Null;
$x_fecha_registro = Null;
$x_monto = Null;
$x_status = Null;
$x_sucursal_id = Null;
$x_fecha_expiracion = Null;
$x_nombre = Null;
$x_apellido_paterno = Null;
$x_apellido_materno = Null;
$x_numero_cliente = Null;
$x_calle = Null;
$x_numero = Null;
$x_colonia = Null;
$x_estado_id = Null;
$x_delegacion_id = Null;
$x_fecha_nacimiento = Null;
$x_promotor_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sKey = @$_GET["key"];
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

	// Get fields from form
	foreach($_POST as $campo => $valor){
		$$campo = $valor;
		
		}
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script src="paisedohint.js"></script> 
<script src="lochint.js"></script>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>

<script language="javascript">
	$(document).ready(function(){	
		
	$('#x_monto').change(function(e) {
        var valor_anterior = $('#x_monto_r').val();
		$('#x_monto').attr('value', valor_anterior);
		
    });
	
			
	$('#x_fecha_registro').change(function(e) {        
		var valor_anterior = $('#x_fecha_registro_r').val();
		$('#x_fecha_registro').attr('value', valor_anterior);
		
    });
	
	$('#x_fecha_expiracion').change(function(e) {        
		var valor_anterior = $('#x_fecha_expiracion_r').val();
		$('#x_fecha_expiracion').attr('value', valor_anterior);
		
    });
				
		
    });
</script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {

if (EW_this.x_monto && !EW_checknumber(EW_this.x_monto.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto, "TEXT", "Incorrect floating point number - monto"))
		return false; 
}
if (EW_this.x_status && !EW_checkinteger(EW_this.x_status.value)) {
	if (!EW_onError(EW_this, EW_this.x_status, "TEXT", "Incorrect integer - status"))
		return false; 
}
if (EW_this.x_sucursal_id && !EW_checkinteger(EW_this.x_sucursal_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_sucursal_id, "TEXT", "Incorrect integer - sucursal id"))
		return false; 
}
/*if (EW_this.x_fecha_expiracion && !EW_checkdate(EW_this.x_fecha_expiracion.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_expiracion, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha expiracion"))
		return false; 
}
*/if (EW_this.x_numero_cliente && !EW_checkinteger(EW_this.x_numero_cliente.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_cliente, "TEXT", "Incorrect integer - numero cliente"))
		return false; 
}
if (EW_this.x_estado_id && !EW_checkinteger(EW_this.x_estado_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_estado_id, "TEXT", "Incorrect integer - estado id"))
		return false; 
}
if (EW_this.x_delegacion_id && !EW_checkinteger(EW_this.x_delegacion_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "TEXT", "Incorrect integer - delegacion id"))
		return false; 
}
/*if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha nacimiento"))
		return false; 
}*/
if (EW_this.x_promotor_id && !EW_checkinteger(EW_this.x_promotor_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "TEXT", "Incorrect integer - promotor id"))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">MEMBRESIAS<br><br>
<a href="php_membresialist.php">REGRESAR A LA LISTA</a></span></p>
<form name="membresiaadd" id="membresiaadd" action="php_membresiaadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<table class="ewTable">
	<tr>
		<td width="150" class="ewTableHeader"><span>fecha registro</span></td>
		<td width="838" class="ewTableAltRow"><span>
        <input type="hidden" name="x_fecha_registro_r" id="x_fecha_registro_r" value="<?php echo FormatDateTime(@$x_today,5); ?>">
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_today,5); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
        <input type="hidden" name="x_monto_r" id="x_monto_r" size="30" value="150">
		<input type="text" name="x_monto" id="x_monto" size="30" value="150">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>status</span></td>
		<td class="ewTableAltRow"><span>
<?php			
				$x_estado_civil_idList = "<select name=\"x_status\" id=\"x_status\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";				
				$sSqlWrk = "SELECT `membresia_status_id`, `descripcion` FROM `membresia_status` ";				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["membresia_status_id"] == @$x_status) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;					
			
?>
</span></td>
	</tr>
    <tr>
		<td class="ewTableHeader">precio mercancia</td>
		<td class="ewTableAltRow"><span><div id="precio_mercancia">
<?php			
				$x_estado_civil_idList = "<select name=\"x_precio\" id=\"x_precio\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";				
				$sSqlWrk = "SELECT `membresia_precio_id`, `descripcion` FROM `membresia_precio` ";				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["membresia_precio_id"] == @$x_status) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;					
			
?>
</div></span></td>
    
	<tr>
		<td class="ewTableHeader"><span>sucursal</span></td>
		<td class="ewTableAltRow"><span>
<?php 
$x_estado_civil_idList = "<select name=\"x_sucursal_id\" id=\"x_sucursal_id\"  class=\"texto_normal\">";
$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `sucursal_id`, `nombre` FROM `sucursal` ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == @$x_sucursal_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
				
						
			
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha expiracion</span></td>
		<td class="ewTableAltRow"><span>
        <input type="hidden" name="x_fecha_expiracion_r" id="x_fecha_expiracion_r" value="<?php echo FormatDateTime(@$x_vigencia,5); ?>">
<input type="text" name="x_fecha_expiracion" id="x_fecha_expiracion" value="<?php echo FormatDateTime(@$x_vigencia,5); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>nombre</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_nombre" id="x_nombre"  size="30" value="<?php echo @$x_nombre; ?>"  />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>apellido paterno</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_apellido_paterno" id="x_apellido_paterno" size="30"  value="<?php echo @$x_apellido_paterno; ?>"  />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>apellido materno</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_apellido_materno" id="x_apellido_materno"  size="30"  value="<?php echo @$x_apellido_materno; ?>"  />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>numero cliente</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_numero_cliente" id="x_numero_cliente" size="30" value="<?php echo htmlspecialchars(@$x_numero_cliente) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>calle</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_calle" id="x_calle" size="30"  value="<?php echo @$x_calle; ?>" />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>numero</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_numero" id="x_numero" size="30"  value="<?php echo @$x_numero; ?>" />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>colonia</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_colonia" id="x_colonia" size="30"  value="<?php echo @$x_colonia; ?>" />

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>estado id</span></td>
		<td class="ewTableAltRow"><span>
		  <?php
		$x_entidad_idList = "<select name=\"x_estado_id\" id=\"x_estado_id\" $x_readonly2 onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_estado_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
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
		<td class="ewTableHeader"><span>delegacion</span></td>
		<td class="ewTableAltRow"><span>
		  <div id="txtHint1" class="texto_normal">
		    Del/Mun:
		    <?php
		if($x_entidad_domicilio > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\"  echo $x_readonly2>";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_estado_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
		    </div>

</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha nacimiento</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_nacimiento" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_fecha_nacimiento,7); ?>">
</span>
<img src="images/ew_calendar.gif" id="cx_fecha_nacimiento" onClick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%d/%m/%Y',
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" /></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>promotor id</span></td>
		<td class="ewTableAltRow"><span>
		  <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor ";
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";		
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == @$x_promotor_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["nombre_completo"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="AGREGAR">
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
	$sSql = "SELECT * FROM `membresia`";
	$sSql .= " WHERE `membresia_id` = " . $sKeyWrk;
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
		$GLOBALS["x_membresia_id"] = $row["membresia_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_status"] = $row["status"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_fecha_expiracion"] = $row["fecha_expiracion"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_apellido_paterno"] = $row["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row["apellido_materno"];
		$GLOBALS["x_numero_cliente"] = $row["numero_cliente"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_numero"] = $row["numero"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_estado_id"] = $row["estado_id"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_fecha_nacimiento"] = $row["fecha_nacimiento"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_precio"] = $row["precio_id"];
		
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
	// Add New Record
	$sSql = "SELECT * FROM `membresia`";
	$sSql .= " WHERE 0 = 1";
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

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field monto
	$theValue = ($GLOBALS["x_monto"] != "") ? " '" . doubleval($GLOBALS["x_monto"]) . "'" : "NULL";
	$fieldList["`monto`"] = $theValue;

	// Field status
	$theValue = ($GLOBALS["x_status"] != "") ? intval($GLOBALS["x_status"]) : "NULL";
	$fieldList["`status`"] = $theValue;

	// Field sucursal_id
	$theValue = ($GLOBALS["x_sucursal_id"] != "") ? intval($GLOBALS["x_sucursal_id"]) : "NULL";
	$fieldList["`sucursal_id`"] = $theValue;

	// Field fecha_expiracion
	$theValue = ($GLOBALS["x_fecha_expiracion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_expiracion"]) . "'" : "NULL";
	$fieldList["`fecha_expiracion`"] = $theValue;

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field apellido_paterno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	// Field apellido_materno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	// Field numero_cliente
	$theValue = ($GLOBALS["x_numero_cliente"] != "") ? intval($GLOBALS["x_numero_cliente"]) : "NULL";
	$fieldList["`numero_cliente`"] = $theValue;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field numero
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_numero"]) : $GLOBALS["x_numero"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`numero`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field estado_id
	$theValue = ($GLOBALS["x_estado_id"] != "") ? intval($GLOBALS["x_estado_id"]) : "NULL";
	$fieldList["`estado_id`"] = $theValue;

	// Field delegacion_id
	$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field fecha_nacimiento
	$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
	$fieldList["`fecha_nacimiento`"] = $theValue;

	// Field promotor_id
	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// insert into database
	$strsql = "INSERT INTO `membresia` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn);
	return true;
}
?>
