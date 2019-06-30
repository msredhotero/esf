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
$sKey = @$_GET["key"];
if (($sKey == "") || ((is_null($sKey)))) {
	$sKey = @$_GET["key"]; 
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean(); 
	header("Locationphp_membresialist.php"); 
}
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location php_membresialist.php");
		}
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">MEMBRESIA<br><br>
<a href="php_membresialist.php">Regresar al listado</a>&nbsp;

</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
    <td width="153" class="ewTableHeader"><span>membresia id</span></td>
	<td width="835" class="ewTableAltRow"><span>
<?php echo $x_membresia_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_monto; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>status</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_status_descripcion; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>sucursal id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_sucursal; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha expiracion</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_expiracion,5); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>nombre</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_nombre); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>apellido paterno</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_apellido_paterno); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>apellido materno</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_apellido_materno); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>numero cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_numero_cliente; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>calle</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_calle); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>numero</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_numero); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>colonia</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_colonia); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>estado id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_entidad; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>delegacion id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_delegacion; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha nacimiento</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_nacimiento,5); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>promotor id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_promotor; ?>
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
		
		$sqlStatus = "SELECT descripcion  FROM membresia_status WHERE membresia_status_id = ".$GLOBALS["x_status"]." ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$GLOBALS["x_status_descripcion"] =  $rowstatus["descripcion"];		
		
		$sqlStatus = "SELECT nombre  FROM sucursal WHERE sucursal_id = ". $GLOBALS["x_sucursal_id"]." " ;
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$GLOBALS["x_sucursal"] =  $rowstatus["nombre"];
				
		$sqlStatus = "SELECT nombre  FROM entidad WHERE entidad_id = ".$GLOBALS["x_estado_id"]." ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$GLOBALS["x_entidad"] =  $rowstatus["nombre"];
		
		
		$sqlStatus = "SELECT descripcion  FROM delegacion WHERE delegacion_id = ".$GLOBALS["x_delegacion_id"]." ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$GLOBALS["x_delegacion"] =  $rowstatus["descripcion"];
			
		$sqlStatus = "SELECT nombre_completo  FROM promotor WHERE promotor_id = ".$GLOBALS["x_promotor_id"]." ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$GLOBALS["x_promotor"] =  $rowstatus["nombre_completo"];
		
		
		
		
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
