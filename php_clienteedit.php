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
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_tipo_negocio = Null; 
$ox_tipo_negocio = Null;
$x_edad = Null; 
$ox_edad = Null;
$x_sexo = Null; 
$ox_sexo = Null;
$x_estado_civil_id = Null; 
$ox_estado_civil_id = Null;
$x_numero_hijos = Null; 
$ox_numero_hijos = Null;
$x_nombre_conyuge = Null; 
$ox_nombre_conyuge = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_cliente_id = @$_GET["cliente_id"];

//if (!empty($x_cliente_id )) $x_cliente_id  = (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id ) : $x_cliente_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];		
	$x_tipo_negocio = @$_POST["x_tipo_negocio"];
	$x_edad = @$_POST["x_edad"];
	$x_sexo = @$_POST["x_sexo"];
	$x_estado_civil_id = @$_POST["x_estado_civil_id"];
	$x_numero_hijos = @$_POST["x_numero_hijos"];
	$x_nombre_conyuge = @$_POST["x_nombre_conyuge"];
	$x_email = @$_POST["x_email"];	
}

// Check if valid key
if (($x_cliente_id == "") || (is_null($x_cliente_id))) {
	ob_end_clean();
	header("Location: php_clientelist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los dato";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "El cliente ha sido actualizado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
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
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "El nombre es requerido."))
		return false;
}
if (EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "El apellido paterno es requerido."))
		return false;
}

if (EW_this.x_tipo_negocio && !EW_hasValue(EW_this.x_tipo_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_negocio, "TEXT", "El tipo de negocio es requerido."))
		return false;
}
if (EW_this.x_edad && !EW_hasValue(EW_this.x_edad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "La edad es requerida."))
		return false;
}
if (EW_this.x_edad && !EW_checkinteger(EW_this.x_edad.value)) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "La edad es requerida."))
		return false; 
}
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "RADIO", "Seleccione el genero"))
		return false;
}
if (EW_this.x_estado_civil_id && !EW_hasValue(EW_this.x_estado_civil_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_civil_id, "SELECT", "El estado civil es requerido."))
		return false;
}
if (EW_this.x_numero_hijos && !EW_checkinteger(EW_this.x_numero_hijos.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_hijos, "TEXT", "Numero de hijos incorrecto"))
		return false; 
}
/*
if (EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "Indique el Email."))
		return false;
}
*/
if (EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El Email es incorrecto, por favor verifiquelo."))
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
<p><span class="phpmaker">CLIENTES<br>
  <br>
    <a href="php_clientelist.php">Regresar a la Lista</a></span></p>
<form name="clienteedit" id="clienteedit" action="php_clienteedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td width="150" class="ewTableHeaderThin"><span>No</span></td>
		<td width="838" class="ewTableAltRow"><span>
<?php echo $x_cliente_id; ?>
<input type="hidden" id="x_cliente_id" name="x_cliente_id" value="<?php echo htmlspecialchars(@$x_cliente_id); ?>">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre(s)</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Apellido Paterno</td>
	  <td class="ewTableAltRow"><input type="text" name="x_apellido_paterno" id="x_apellido_paterno" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_apellido_paterno) ?>" /></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Apellido Materno</td>
	  <td class="ewTableAltRow"><input type="text" name="x_apellido_materno" id="x_apellido_materno" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_apellido_materno) ?>" /></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Tipo de negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tipo_negocio" id="x_tipo_negocio" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_tipo_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Edad</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_edad" type="text" id="x_edad" value="<?php echo htmlspecialchars(@$x_edad) ?>" size="4" maxlength="2">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Sexo</span></td>
		<td class="ewTableAltRow"><span>
<input type="radio" name="x_sexo"<?php if (@$x_sexo == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
<?php echo "M"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_sexo"<?php if (@$x_sexo == "2") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("2"); ?>">
<?php echo "F"; ?>
<?php echo EditOptionSeparator(1); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Estado Civil</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_estado_civil_idList = "<select name=\"x_estado_civil_id\">";
$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["estado_civil_id"] == @$x_estado_civil_id) {
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
		<td class="ewTableHeaderThin"><span>Numero de hijos</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_numero_hijos" type="text" id="x_numero_hijos" value="<?php echo htmlspecialchars(@$x_numero_hijos) ?>" size="4" maxlength="2">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Numero de hijos Dep. </td>
	  <td class="ewTableAltRow">
	  <input name="x_numero_hijos_dep" type="text" id="x_numero_hijos_dep" value="<?php echo htmlspecialchars(@$x_numero_hijos_dep) ?>" size="4" maxlength="2" /></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre del Conyuge</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_conyuge" id="x_nombre_conyuge" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_conyuge) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Email</td>
	  <td class="ewTableAltRow">
	  <input type="text" name="x_email" id="x_email" size="50" maxlength="150" value="<?php echo htmlspecialchars(@$x_email) ?>" /></td>
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
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;
	$sWhere .= "(`cliente_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row["apellido_paterno"];		
		$GLOBALS["x_apellido_materno"] = $row["apellido_materno"];				
		$GLOBALS["x_tipo_negocio"] = $row["tipo_negocio"];
		$GLOBALS["x_edad"] = $row["edad"];
		$GLOBALS["x_sexo"] = $row["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep"] = $row["numero_hijos_dep"];		
		$GLOBALS["x_nombre_conyuge"] = $row["nombre_conyuge"];
		$GLOBALS["x_email"] = $row["email"];		
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
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;	
	$sWhere .= "(`cliente_id` = " . addslashes($sTmp) . ")";
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
	
//		$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
//		$fieldList["`solicitud_id`"] = $theValue;
		
//		$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
//		$fieldList["`usuario_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tipo_negocio`"] = $theValue;
		$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "NULL";
		$fieldList["`edad`"] = $theValue;
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "NULL";
		$fieldList["`sexo`"] = $theValue;
		$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "NULL";
		$fieldList["`estado_civil_id`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos_dep"] != "") ? intval($GLOBALS["x_numero_hijos_dep"]) : "0";
		$fieldList["`numero_hijos_dep`"] = $theValue;	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;

		// update
		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
