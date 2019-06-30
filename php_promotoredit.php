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
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_telefono_oficina = Null; 
$ox_telefono_oficina = Null;
$x_telefono_particular = Null; 
$ox_telefono_particular = Null;
$x_telefono_movil = Null; 
$ox_telefono_movil = Null;
$x_promotor_status_id = Null; 
$ox_promotor_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_promotor_id = @$_GET["promotor_id"];

//if (!empty($x_promotor_id )) $x_promotor_id  = (get_magic_quotes_gpc()) ? stripslashes($x_promotor_id ) : $x_promotor_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_sucursal_id = @$_POST["x_sucursal_id"];	
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_comision = @$_POST["x_comision"];
	$x_direccion_id = @$_POST["x_direccion_id"];

	$x_calle = $_POST["x_calle"];
	$x_colonia = $_POST["x_colonia"];
	$x_delegacion_id = $_POST["x_delegacion_id"];
	$x_otra_delegacion = $_POST["x_otra_delegacion"];
	$x_codigo_postal = $_POST["x_codigo_postal"];
	$x_ubicacion = $_POST["x_ubicacion"];

	$x_telefono_oficina = @$_POST["x_telefono_oficina"];
	$x_telefono_particular = @$_POST["x_telefono_particular"];
	$x_telefono_movil = @$_POST["x_telefono_movil"];
	$x_promotor_tipo_id = @$_POST["x_promotor_tipo_id"];
	$x_promotor_status_id = @$_POST["x_promotor_status_id"];	
}

// Check if valid key
if (($x_promotor_id == "") || (is_null($x_promotor_id))) {
	ob_end_clean();
	header("Location: php_promotorlist.php");
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
			header("Location: php_promotorlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotorlist.php");
			exit();
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
function buscadelegacion(){
	EW_this = document.promotoredit;
	EW_this.a_edit.value = "D";	
	EW_this.submit();	
}

function EW_checkMyForm() {
EW_this = document.promotoredit;
validada = true;

if (validada == true && EW_this.x_sucursal_id && !EW_hasValue(EW_this.x_sucursal_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_sucursal_id, "SELECT", "Indique la sucursal a la cual pertenece el promotor."))
		validada = false;
}


if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "El nombre es requerido."))
		validada = false;
}

if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio de la oficina."))
		validada = false;
}
if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio de la oficina."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio de la oficina."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion && !EW_hasValue(EW_this.x_otra_delegacion, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion, "TEXT", "Indique la delegación del domicilio de la oficina."))
			validada = false;
	}
}
if (validada == true && EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Indique la entidad del domicilio de la oficina."))

		validada = false;
}
if (validada == true && EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Indique el Código Postal del domicilio de la oficina."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "Indique la Ubicación del domicilio de la oficina."))
		validada = false;
}

if (validada == true && EW_this.x_comision && !EW_hasValue(EW_this.x_comision, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comisión es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_comision && !EW_checknumber(EW_this.x_comision.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comisión es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_promotor_tipo_id && !EW_hasValue(EW_this.x_promotor_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_tipo_id, "SELECT", "El tipo es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_promotor_status_id && !EW_hasValue(EW_this.x_promotor_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_status_id, "SELECT", "El status es requerido."))
		validada = false;
}

if(validada == true){
	EW_this.submit();
}
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">PROMOTORES<br>
  <br>
    <a href="php_promotorlist.php">Regresar a la lista</a></span></p>
<form name="promotoredit" id="promotoredit" action="php_promotoredit.php" method="post">
<input type="hidden" id="x_direccion_id" name="x_direccion_id" value="<?php echo htmlspecialchars(@$x_direccion_id); ?>">
<input type="hidden" id="x_usuario_id" name="x_usuario_id" value="<?php echo htmlspecialchars(@$x_usuario_id); ?>">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td width="173" class="ewTableHeaderThin"><span>No</span></td>
		<td width="815" class="ewTableAltRow"><span>
<?php echo $x_promotor_id; ?>
<input type="hidden" id="x_promotor_id" name="x_promotor_id" value="<?php echo htmlspecialchars(@$x_promotor_id); ?>">
</span></td>
	</tr>
	
	<tr>
	  <td class="ewTableHeaderThin">Sucursal</td>
	  <td class="ewTableAltRow">
<span>
<?php
$x_sucursal_dependiente_idList = "<select name=\"x_sucursal_id\">";
$x_sucursal_dependiente_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `sucursal_id`, `nombre` FROM `sucursal`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_sucursal_dependiente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["sucursal_id"] == @$x_sucursal_id) {
			$x_sucursal_dependiente_idList .= "' selected";
		}
		$x_sucursal_dependiente_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_sucursal_dependiente_idList .= "</select>";
echo $x_sucursal_dependiente_idList;
?>
</span>      
      </td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre completo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="50" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
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
		<td class="ewTableHeaderThin"><span>Teléfono de oficina</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_oficina" id="x_telefono_oficina" size="20" maxlength="20" value="<?php echo htmlspecialchars(@$x_telefono_oficina) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Teléfono particular</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_particular" id="x_telefono_particular" size="20" maxlength="20" value="<?php echo htmlspecialchars(@$x_telefono_particular) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Teléfono móvil</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_movil" id="x_telefono_movil" size="20" maxlength="20" value="<?php echo htmlspecialchars(@$x_telefono_movil) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Comision</td>
	  <td class="ewTableAltRow"><span>
	    <input name="x_comision" type="text" id="x_comision" value="<?php echo htmlspecialchars(@$x_comision) ?>" size="6" maxlength="6" />
	  </span></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Tipo</td>
	  <td class="ewTableAltRow">
	  <span>
<?php if (!(!is_null($x_promotor_tipo_id)) || ($x_promotor_tipo_id == "")) { $x_promotor_tipo_id = 0;} // Set default value ?>
<?php
$x_promotor_status_idList = "<select name=\"x_promotor_tipo_id\">";
$x_promotor_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `promotor_tipo_id`, `descripcion` FROM `promotor_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_promotor_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["promotor_tipo_id"] == @$x_promotor_tipo_id) {
			$x_promotor_status_idList .= "' selected";
		}
		$x_promotor_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_promotor_status_idList .= "</select>";
echo $x_promotor_status_idList;
?>
</span>	  
	  </td>
	  </tr>	  
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_promotor_status_idList = "<select name=\"x_promotor_status_id\">";
$x_promotor_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `promotor_status_id`, `descripcion` FROM `promotor_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_promotor_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["promotor_status_id"] == @$x_promotor_status_id) {
			$x_promotor_status_idList .= "' selected";
		}
		$x_promotor_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_promotor_status_idList .= "</select>";
echo $x_promotor_status_idList;
?>
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
	$x_usuario_id = "No asignado &nbsp; <input type=\"button\" name=\"x_usuario\" value=\"...\" onclick=\"window.open('php_cat_usuario_promotoradd.php?x_promotor_id=$x_promotor_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}else{
	$x_usuario_id .= "&nbsp; <input type=\"button\" name=\"x_usuario\" value=\"Cambiar\" onclick=\"window.open('php_cat_usuario_promotoredit.php?x_promotor_id=$x_promotor_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}
echo $x_usuario_id;
$x_usuario_id = $ox_usuario_id; // Restore Original Value 

?></td>
	  </tr>
</table>
<p>
<input type="button" name="Action" value="EDITAR" onclick="EW_checkMyForm()">
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
	global $x_promotor_id;
	$sSql = "SELECT * FROM `promotor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_id) : $x_promotor_id;
	$sWhere .= "(`promotor_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];		
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_comision"] = $row["comision"];
		$GLOBALS["x_direccion_id"] = $row["direccion_id"];
		$GLOBALS["x_telefono_oficina"] = $row["telefono_oficina"];
		$GLOBALS["x_telefono_particular"] = $row["telefono_particular"];
		$GLOBALS["x_telefono_movil"] = $row["telefono_movil"];
		$GLOBALS["x_promotor_tipo_id"] = $row["promotor_tipo_id"];
		$GLOBALS["x_promotor_status_id"] = $row["promotor_status_id"];

		$sSql = "select * from direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id where promotor_id = ".$GLOBALS["x_promotor_id"]." and direccion_tipo_id = 2";
		$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		$GLOBALS["x_direccion_id"] = $row4["direccion_id"];
		$GLOBALS["x_calle"] = $row4["calle"];
		$GLOBALS["x_colonia"] = $row4["colonia"];
		$GLOBALS["x_delegacion_id"] = $row4["delegacion_id"];
//		$GLOBALS["x_otra_delegacion"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row4["ubicacion"];



		
	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs4);
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
	global $x_promotor_id;
	$sSql = "SELECT * FROM `promotor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_id) : $x_promotor_id;	
	$sWhere .= "(`promotor_id` = " . addslashes($sTmp) . ")";
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		$theValue = ($GLOBALS["x_comision"] != "") ? " '" . doubleval($GLOBALS["x_comision"]) . "'" : "NULL";
		$fieldList["`comision`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_oficina"]) : $GLOBALS["x_telefono_oficina"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_oficina`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_particular"]) : $GLOBALS["x_telefono_particular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_particular`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil"]) : $GLOBALS["x_telefono_movil"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_tipo_id"] != "") ? intval($GLOBALS["x_promotor_tipo_id"]) : "0";
		$fieldList["`promotor_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_status_id"] != "") ? intval($GLOBALS["x_promotor_status_id"]) : "0";
		$fieldList["`promotor_status_id`"] = $theValue;

		$theValue = ($GLOBALS["x_sucursal_id"] != "") ? intval($GLOBALS["x_sucursal_id"]) : "0";
		$fieldList["`sucursal_id`"] = $theValue;


		// update
		$sSql = "UPDATE `promotor` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);



		if($GLOBALS["x_direccion_id"] > 0){

			$fieldList = NULL;
	
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
			$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
			$fieldList["`delegacion_id`"] = $theValue;
			/*
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otra_delegacion"]) : $GLOBALS["x_otra_delegacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`otra_delegacion`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`entidad`"] = $theValue;
			*/
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
	
			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id"];
			$x_result = phpmkr_query($sSql,$conn);
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
	

		}else{
		
			$fieldList = NULL;
			// Field cliente_id
		//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
			$fieldList["`cliente_id`"] = 0;
		
			// Field aval_id
		//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
			$fieldList["`aval_id`"] = 0;
		
			// Field promotor_id
		//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
			$fieldList["`promotor_id`"] = $x_promotor_id;
		
			// Field direccion_tipo_id
		//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
			$fieldList["`direccion_tipo_id`"] = 2;
		
			// Field calle
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
		
			// Field colonia
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
		
			// Field delegacion_id
			$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
			$fieldList["`delegacion_id`"] = $theValue;
/*		
			// Field otra_delegacion
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otra_delegacion"]) : $GLOBALS["x_otra_delegacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`otra_delegacion`"] = $theValue;
		
			// Field entidad
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`entidad`"] = $theValue;
*/		
			// Field codigo_postal
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
		
			// Field ubicacion
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
		
			// insert into database
			$sSql = "INSERT INTO `direccion` (";
			$sSql .= implode(",", array_keys($fieldList));
			$sSql .= ") VALUES (";
			$sSql .= implode(",", array_values($fieldList));
			$sSql .= ")";
		
			$x_result = phpmkr_query($sSql, $conn);
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
			phpmkr_query('commit;', $conn);	 
		}

		$bEditData = true; // Update Successful
	}
	
	return $bEditData;
}
?>


