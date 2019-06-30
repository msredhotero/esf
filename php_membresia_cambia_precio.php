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
if (($sKey == "") || (is_null($sKey))) { $sKey = @$_POST["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {


	foreach($_POST as $campo => $valor){
		$$campo = $valor;	
		}	

	
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_membresialist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script src="paisedohint.js"></script> 
<script src="lochint.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>

<script language="javascript">
	$(document).ready(function(){	
	
	$('#x_precio').change(function(e) {
        var tipo_precio = $('#x_precio').val();
		var precio_original = $('#x_precio_original').val();
		var monto_r = $('#x_monto_r').val();		
		var valor = 0;
		var total_a_pagar = 0;
		 if (precio_original == 3){
			 // el presio es madio mayoreo 			 
			 if(tipo_precio == 1){
				 // se va desde medio mayorea a precio oro// aumenta
				 total_a_pagar = 300;				 
				 }else if (tipo_precio == 2){
					 total_a_pagar = 150;
					 }
			 }else if (precio_original == 2){
				 // el precio es mayoreo.
				 if(tipo_precio == 1){
				 // se va desde  mayorea a precio oro// aumenta
				 total_a_pagar = 150;				 
				 }				 
				 }
		var valor_final = 0;
		alert("MONTO A PAGAR " +total_a_pagar );
		valor_final = 	parseInt(monto_r) + parseInt(total_a_pagar);
		$('#x_monto_r').attr('value', valor_final);
		$('#x_monto').attr('value', valor_final);
		
		
    });	
	
	$('#x_monto').change(function(e) {
        alert("PARA CAMBIAR EL MONTO SELECCIONE EL TIPO DE PRECIO");
		var valor_anterior = $('#x_monto_r').val();
		$('#x_monto').attr('value', valor_anterior);
		
    });
	
	      
			
	$('#x_fecha_registro').change(function(e) {   
	 alert("NO PUEDE EDITAR LAS FECHAS");     
		var valor_anterior = $('#x_fecha_registro_r').val();
		$('#x_fecha_registro').attr('value', valor_anterior);
		
    });
	
	$('#x_fecha_expiracion').change(function(e) { 
	alert("NO PUEDE EDITAR LAS FECHAS");        
		var valor_anterior = $('#x_fecha_expiracion_r').val();
		$('#x_fecha_expiracion').attr('value', valor_anterior);
		
    });
				
		
    });
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_fecha_registro && !EW_checkdate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha registro"))
		return false; 
}
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
if (EW_this.x_fecha_expiracion && !EW_checkdate(EW_this.x_fecha_expiracion.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_expiracion, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha expiracion"))
		return false; 
}
if (EW_this.x_numero_cliente && !EW_checkinteger(EW_this.x_numero_cliente.value)) {
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
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha nacimiento"))
		return false; 
}
if (EW_this.x_promotor_id && !EW_checkinteger(EW_this.x_promotor_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "TEXT", "Incorrect integer - promotor id"))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">MEMBRESIA<br><br><a href="php_membresialist.php">REGRESAR A LA LISTA</a></span></p>
<form name="membresiaedit" id="membresiaedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td width="204" class="ewTableHeader"><span>membresia id</span></td>
		<td width="784" class="ewTableAltRow"><span>
<?php echo $x_membresia_id; ?><input type="hidden" name="x_membresia_id" value="<?php echo $x_membresia_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha registro</span></td>
		<td class="ewTableAltRow"><span>
        <input type="hidden" name="x_fecha_registro_r" id="x_fecha_registro_r" value="<?php echo FormatDateTime(@$x_fecha_registro,5); ?>">
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,5); ?>">
</span></td>
	</tr>
    	<tr>
		<td class="ewTableHeader"><span>fecha expiracion</span></td>
		<td class="ewTableAltRow"><span>
        <input type="hidden" name="x_fecha_expiracion_r" id="x_fecha_expiracion_r" value="<?php echo FormatDateTime(@$x_fecha_expiracion,5); ?>">
<input type="text" name="x_fecha_expiracion" id="x_fecha_expiracion" value="<?php echo FormatDateTime(@$x_fecha_expiracion,5); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
        <input type="hidden" name="x_monto_r" id="x_monto_r" size="30" value="<?php echo htmlspecialchars(@$x_monto) ?>">
		<input type="text" name="x_monto" id="x_monto" size="30" value="<?php echo htmlspecialchars(@$x_monto) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>status</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_status" id="x_status" size="30" value="<?php echo htmlspecialchars(@$x_status) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>sucursal id</span></td>
		<td class="ewTableAltRow"><?php 
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
?></td>
	</tr>
    
     <tr>
		<td class="ewTableHeader">precio actual de mercancia</td>
		<td class="ewTableAltRow"><span><?php			
				$x_estado_civil_idList = "<select name=\"x_precio\" id=\"x_precio\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";				
				$sSqlWrk = "SELECT `membresia_precio_id`, `descripcion` FROM `membresia_precio` ";				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["membresia_precio_id"] == @$x_precio) {
					$x_estado_civil_idList .= "' selected";
					$x_descripcion_actual = $datawrk["descripcion"];
					?>
                    <input type="hidden" name="x_precio_original"  id="x_precio_original" value="<?php echo $x_precio;?>"/>
                    <?php
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_descripcion_actual;					
			
?>
</span></td>
	</tr>
    
     <tr>
		<td class="ewTableHeader">cambiar a precio </td>
		<td class="ewTableAltRow"><span><div id="precio_mercancia">
<?php			
				$x_estado_civil_idList = "<select name=\"x_precio\" id=\"x_precio\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";				
				$sSqlWrk = "SELECT `membresia_precio_id`, `descripcion` FROM `membresia_precio` ";
				if($x_precio == 3)	{
					$sSqlWrk .=  " WHERE `membresia_precio_id` IN (1,2)";
					}else if($x_precio == 2){
						$sSqlWrk .=  " WHERE `membresia_precio_id` IN (1)";
						}			
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["membresia_precio_id"] == @$x_precio) {
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
	</tr>

	<tr>
		<td class="ewTableHeader"><span>nombre</span></td>
		<td class="ewTableAltRow"><span>
        <input type="text" name="x_nombre" id="x_nombre"  size="30" value="<?php echo @$x_nombre. " ".$x_apellido_paterno." ".$x_apellido_materno ; ?>"  />

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
</table>
<p>
<input type="submit" name="Action" value="CAMBIAR PRECIO">
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($sKey,$conn)
{

	// Open record
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
		$EditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
		#$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_monto"] != "") ? " '" . doubleval($GLOBALS["x_monto"]) . "'" : "NULL";
		$fieldList["`monto`"] = $theValue;
		$theValue = ($GLOBALS["x_status"] != "") ? intval($GLOBALS["x_status"]) : "NULL";
		#$fieldList["`status`"] = $theValue;
		$theValue = ($GLOBALS["x_precio"] != "") ? intval($GLOBALS["x_precio"]) : "NULL";
		$fieldList["`precio_id`"] = $theValue;
		
		$theValue = ($GLOBALS["x_sucursal_id"] != "") ? intval($GLOBALS["x_sucursal_id"]) : "NULL";
		#$fieldList["`sucursal_id`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_expiracion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_expiracion"]) . "'" : "NULL";
		#$fieldList["`fecha_expiracion`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`nombre`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`apellido_paterno`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`apellido_materno`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_cliente"] != "") ? intval($GLOBALS["x_numero_cliente"]) : "NULL";
		#$fieldList["`numero_cliente`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_numero"]) : $GLOBALS["x_numero"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`numero`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		#$fieldList["`colonia`"] = $theValue;
		$theValue = ($GLOBALS["x_estado_id"] != "") ? intval($GLOBALS["x_estado_id"]) : "NULL";
		#$fieldList["`estado_id`"] = $theValue;
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
		#$fieldList["`delegacion_id`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
		#$fieldList["`fecha_nacimiento`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		#$fieldList["`promotor_id`"] = $theValue;

		// update
		$sSql = "UPDATE `membresia` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `membresia_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
