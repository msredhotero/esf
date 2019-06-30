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
$x_responsable_sucursal_id = Null;
$x_sucursal_id = Null;
$x_usuario_id = Null;
$x_responsable_sucursal_status_id = Null;
$x_nombre_completo = Null;
$x_email = Null;
$x_telefono_sucursal = Null;
$x_telefono_movil = Null;
$x_telefono_casa = Null;
$x_new_field0 = Null;
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

	// Get fields from form
	$x_responsable_sucursal_id = @$_POST["x_responsable_sucursal_id"];
	$x_sucursal_id = @$_POST["x_sucursal_id"];
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_responsable_sucursal_status_id = @$_POST["x_responsable_sucursal_status_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_email = @$_POST["x_email"];
	$x_telefono_sucursal = @$_POST["x_telefono_sucursal"];
	$x_telefono_movil = @$_POST["x_telefono_movil"];
	$x_telefono_casa = @$_POST["x_telefono_casa"];
	$x_new_field0 = @$_POST["x_new_field0"];
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_responsable_sucursallist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
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
if (EW_this.x_sucursal_id && !EW_hasValue(EW_this.x_sucursal_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sucursal_id, "TEXT", "Please enter required field - sucursal id"))
		return false;
}
if (EW_this.x_sucursal_id && !EW_checkinteger(EW_this.x_sucursal_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_sucursal_id, "TEXT", "Incorrect integer - sucursal id"))
		return false; 
}
if (EW_this.x_usuario_id && !EW_hasValue(EW_this.x_usuario_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_id, "TEXT", "Please enter required field - usuario id"))
		return false;
}
if (EW_this.x_usuario_id && !EW_checkinteger(EW_this.x_usuario_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_usuario_id, "TEXT", "Incorrect integer - usuario id"))
		return false; 
}
if (EW_this.x_responsable_sucursal_status_id && !EW_hasValue(EW_this.x_responsable_sucursal_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_responsable_sucursal_status_id, "SELECT", "Debe especificar el status del encargado de sucursal"))
		return false;
}
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "El nombre del responsable es requerido."))
		return false;
}
if (EW_this.x_telefono_movil && !EW_hasValue(EW_this.x_telefono_movil, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono_movil, "TEXT", "Debe  llenar el campo de telefono movil"))
		return false;
}
if (EW_this.x_new_field0 && !EW_checkinteger(EW_this.x_new_field0.value)) {
	if (!EW_onError(EW_this, EW_this.x_new_field0, "TEXT", "Incorrect integer - new field 0"))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">EDITAR RESPONSABLE DE SUCURSAL<br><br>
  <a href="php_responsable_sucursallist.php">Regresar al listado</a></span></p>
<form name="responsable_sucursaledit" id="responsable_sucursaledit" action="php_responsable_sucursaledit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td width="213" class="ewTableHeaderThin"><span>responsable sucursal id</span></td>
		<td width="775" class="ewTableAltRow"><span>
<?php echo $x_responsable_sucursal_id; ?><input type="hidden" name="x_responsable_sucursal_id" value="<?php echo $x_responsable_sucursal_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>sucursal id</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_vendedor_status_idList = "<select name=\"x_sucursal_id\">";
$x_vendedor_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `sucursal_id`, `nombre` FROM `sucursal`";
$rswrk = phpmkr_query($sSqlWrk,$conn);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_vendedor_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["sucursal_id"] == @$x_sucursal_id) {
			$x_vendedor_status_idList .= "' selected";
		}
		$x_vendedor_status_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_vendedor_status_idList .= "</select>";
echo $x_vendedor_status_idList;
?>
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>responsable sucursal status id</span></td>
	  <td class="ewTableAltRow"><span>
  <?php
$x_responsable_sucursal_status_idList = "<select name=\"x_responsable_sucursal_status_id\">";
$x_responsable_sucursal_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `responsable_sucursal_status_id`, `descripcion` FROM `responsable_sucursal_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_responsable_sucursal_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["responsable_sucursal_status_id"] == @$x_responsable_sucursal_status_id) {
			$x_responsable_sucursal_status_idList .= "' selected";
		}
		$x_responsable_sucursal_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_responsable_sucursal_status_idList .= "</select>";
echo $x_responsable_sucursal_status_idList;
?>
  </span></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>nombre completo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>email</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_email" id="x_email" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_email) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>telefono sucursal</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_sucursal" id="x_telefono_sucursal" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_sucursal) ?>">
</span></td>
	</tr>
    
     <tr>
	  <td colspan="2" class="ewTableHeaderThin"><div align="left">Direcci&oacute;n</div></td>
	  </tr>
	<tr>
		<td colspan="2" class="ewTableAltRow"><table width="802" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="194"><span class="texto_normal" style="font-size: 10px">Calle no. Ext e Int. : </span></td>
            <td colspan="3">
			<input name="x_calle" type="text" id="x_calle" value="<?php echo htmlspecialchars(@$x_calle) ?>" size="80" maxlength="150" />			</td>
          </tr>
          <tr>
            <td><span class="texto_normal" style="font-size: 10px">Colonia: </span></td>
            <td colspan="3">
			<input name="x_colonia" type="text" id="x_colonia" value="<?php echo htmlspecialchars(@$x_colonia) ?>" size="80" maxlength="150" />			</td>
          </tr>
          <tr>
            <td><span class="texto_normal" style="font-size: 10px">Entidad:</span></td>
            <td width="207"><span class="texto_normal">
		<?php
		$x_delegacion_idList = "<select name=\"x_entidad_id\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
            <td width="401"><div align="left"><span class="texto_normal">
              
        </span><span class="texto_normal">
        <div id="txtHint1" class="texto_normal">
        Del/Mun:
        <?php
		if($x_entidad_id > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
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
        </span></div></td>
            </tr>
          <tr>
            <td class="texto_normal" style="font-size: 10px">C.P.
              :</td>
            <td colspan="3"><span class="texto_normal">
              <input name="x_codigo_postal" type="text" id="x_codigo_postal" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="5" maxlength="10"/>
            </span></td>
          </tr>
          <tr> </tr>
		  <tr>
            <td><span class="texto_normal" style="font-size: 10px">Ubicaci&oacute;n:</span></td>
		    <td colspan="4"><input name="x_ubicacion" type="text" id="x_ubicacion" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
		    </tr>
          
        </table></td>
		</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>telefono movil</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_movil" id="x_telefono_movil" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_movil) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>telefono casa</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_casa" id="x_telefono_casa" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_casa) ?>">
</span></td>
	</tr>
    <tr>
	  <td class="ewTableHeaderThin">Usuario : Password </td>
	  <td class="ewTableAltRow"><?php
if ((!is_null($x_usuario_id)) && ($x_usuario_id <> "")) {
	$sSqlWrk = "SELECT usuario, clave FROM `usuario`";
	$sTmp = $x_usuario_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `usuario_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["usuario"]. "   :   ".$rowwrk["clave"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_usuario_id = $x_usuario_id; // Backup Original Value
$x_usuario_id = $sTmp;

if($x_usuario_id == "0"){
	$x_usuario_id = "No asignado &nbsp; <input type=\"button\" name=\"x_usuario\" value=\"...\" onclick=\"window.open('php_cat_usuario_responsableadd.php?x_responsable_id=$x_responsable_sucursal_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}else{
	$x_usuario_id .= "&nbsp; <input type=\"button\" name=\"x_usuario\" value=\"Cambiar\" onclick=\"window.open('php_cat_usuario_responsableedit.php?x_responsable_id=$x_responsable_sucursal_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}
echo $x_usuario_id;
$x_usuario_id = $ox_usuario_id; // Restore Original Value 

?></td>
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

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `responsable_sucursal`";
	$sSql .= " WHERE `responsable_sucursal_id` = " . $sKeyWrk;
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
		$GLOBALS["x_responsable_sucursal_id"] = $row["responsable_sucursal_id"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_responsable_sucursal_status_id"] = $row["responsable_sucursal_status_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_email"] = $row["email"];
		$GLOBALS["x_telefono_sucursal"] = $row["telefono_sucursal"];
		$GLOBALS["x_telefono_movil"] = $row["telefono_movil"];
		$GLOBALS["x_telefono_casa"] = $row["telefono_casa"];
		$GLOBALS["x_new_field0"] = $row["new_field0"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_entidad_id"] = $row["entidad_id"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row["ubicacion"];

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
	$sSql = "SELECT * FROM `responsable_sucursal`";
	$sSql .= " WHERE `responsable_sucursal_id` = " . $sKeyWrk;
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
		$theValue = ($GLOBALS["x_sucursal_id"] != "") ? intval($GLOBALS["x_sucursal_id"]) : "NULL";
		$fieldList["`sucursal_id`"] = $theValue;
		$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
		$fieldList["`usuario_id`"] = $theValue;
		$theValue = ($GLOBALS["x_responsable_sucursal_status_id"] != "") ? intval($GLOBALS["x_responsable_sucursal_status_id"]) : "NULL";
		$fieldList["`responsable_sucursal_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sucursal"]) : $GLOBALS["x_telefono_sucursal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_sucursal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil"]) : $GLOBALS["x_telefono_movil"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_casa"]) : $GLOBALS["x_telefono_casa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_casa`"] = $theValue;
		$theValue = ($GLOBALS["x_new_field0"] != "") ? intval($GLOBALS["x_new_field0"]) : "NULL";
		$fieldList["`new_field0`"] = $theValue;

		// update
		$sSql = "UPDATE `responsable_sucursal` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `responsable_sucursal_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
