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
	
	// DATOS DE LA DIRECCION
	
	$x_calle = $_POST["x_calle"];
	$x_colonia = $_POST["x_colonia"];
	$x_delegacion_id = $_POST["x_delegacion_id"];
	$x_otra_delegacion = $_POST["x_otra_delegacion"];
	$x_codigo_postal = $_POST["x_codigo_postal"];
	$x_ubicacion = $_POST["x_ubicacion"];
	$x_entidad_id = $_POST["x_entidad_id"];
	
	
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script src="paisedohint.js"></script>
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
<p><span class="phpmaker">AGREGAR RESPONSABLE DE SUCURSAL<br><br>
  <a href="php_responsable_sucursallist.php">Regresar a la lista</a></span></p>
<form name="responsable_sucursaladd" id="responsable_sucursaladd" action="php_responsable_sucursaladd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<table class="ewTable">
	<tr>
		<td width="194" class="ewTableHeaderThin"><span>sucursal id</span></td>
		<td width="794" class="ewTableAltRow"><span>
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
	  <td class="ewTableHeaderThin"><span> status</span></td>
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
		<td class="ewTableHeaderThin">telefono <span>sucursal</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_sucursal" id="x_telefono_sucursal" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_sucursal) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>celular</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_movil" id="x_telefono_movil" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_movil) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin">casa</td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_casa" id="x_telefono_casa" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_casa) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Agregar">
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
	$sSql = "SELECT * FROM `responsable_sucursal`";
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

	// Field sucursal_id
	$theValue = ($GLOBALS["x_sucursal_id"] != "") ? intval($GLOBALS["x_sucursal_id"]) : "NULL";
	$fieldList["`sucursal_id`"] = $theValue;

	// Field usuario_id
	$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
	$fieldList["`usuario_id`"] = $theValue;

	// Field responsable_sucursal_status_id
	$theValue = ($GLOBALS["x_responsable_sucursal_status_id"] != "") ? intval($GLOBALS["x_responsable_sucursal_status_id"]) : "NULL";
	$fieldList["`responsable_sucursal_status_id`"] = $theValue;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// Field telefono_sucursal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sucursal"]) : $GLOBALS["x_telefono_sucursal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_sucursal`"] = $theValue;

	// Field telefono_movil
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil"]) : $GLOBALS["x_telefono_movil"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_movil`"] = $theValue;

	// Field telefono_casa
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_casa"]) : $GLOBALS["x_telefono_casa"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_casa`"] = $theValue;

	// Field new_field0
	$theValue = ($GLOBALS["x_new_field0"] != "") ? intval($GLOBALS["x_new_field0"]) : "NULL";
	$fieldList["`new_field0`"] = $theValue;
	
	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;
	
			// Field delegacion_id
	$theValue = ($GLOBALS["x_entidad_id"] != "") ? intval($GLOBALS["x_entidad_id"]) : "NULL";
	$fieldList["`entidad_id`"] = $theValue;	


	// Field delegacion_id
	$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
	$fieldList["`delegacion_id`"] = $theValue;


	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;
	

	// insert into database
	$strsql = "INSERT INTO `responsable_sucursal` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn) or die ("Error al insertar en  responsable sucursal". phpmkr_error()."sql:".$strsql);
	return true;
}
?>
