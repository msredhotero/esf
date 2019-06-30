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
$x_fondeo_credito_id = Null; 
$ox_fondeo_credito_id = Null;
$x_fondeo_credito_tipo_id = Null; 
$ox_fondeo_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fondeo_credito_status_id = Null; 
$ox_fondeo_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_fondeo_credito_id = @$_GET["credito_id"];

//if (!empty($x_fondeo_credito_id )) $x_fondeo_credito_id  = (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id ) : $x_fondeo_credito_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_fondeo_credito_id = @$_POST["x_fondeo_credito_id"];
	$x_fondeo_credito_num = @$_POST["x_fondeo_credito_num"];	
	$x_cliente_num = @$_POST["x_cliente_num"];		
	$x_fondeo_credito_tipo_id = @$_POST["x_fondeo_credito_tipo_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_fondeo_credito_status_id = @$_POST["x_fondeo_credito_status_id"];
	$x_fecha_otrogamiento = @$_POST["x_fecha_otrogamiento"];
	$x_importe = @$_POST["x_importe"];
	$x_tasa = @$_POST["x_tasa"];
	$x_iva = @$_POST["x_iva"];		
	$x_plazo = @$_POST["x_plazo"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];		
	$x_fecha_vencimiento = @$_POST["x_fecha_vencimiento"];
	$x_tasa_moratoria = @$_POST["x_tasa_moratoria"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_banco_id = @$_POST["x_banco_id"];	
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_num_pagos = @$_POST["x_num_pagos"];		
	$x_tdp = @$_POST["x_tdp"];			
}

// Check if valid key
if (($x_fondeo_credito_id == "") || (is_null($x_fondeo_credito_id))) {
	ob_end_clean();
	header("Location: php_fondeo_creditolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
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

function modifica(v_acc) {
EW_this = document.creditoedit;
validada = true;


	if (validada == true && EW_this.x_fecha_otrogamiento && !EW_hasValue(EW_this.x_fecha_otrogamiento, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_otrogamiento, "TEXT", "La fecha de otorgamiento es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_fecha_otrogamiento && !EW_checkeurodate(EW_this.x_fecha_otrogamiento.value)) {
		if (!EW_onError(EW_this, EW_this.x_fecha_otrogamiento, "TEXT", "La fecha de otorgamiento es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_num_pagos && !EW_hasValue(EW_this.x_num_pagos, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es requerido."))
			validada = false;
	}
	if (validada == true && EW_this.x_num_pagos && !EW_checkinteger(EW_this.x_num_pagos.value)) {
		if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es incorrecto."))
			validada = false;
	}
	if (validada == true && EW_this.x_tasa && !EW_hasValue(EW_this.x_tasa, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_tasa && !EW_checknumber(EW_this.x_tasa.value)) {
		if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_tasa_moratoria && !EW_hasValue(EW_this.x_tasa_moratoria, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_tasa_moratoria, "TEXT", "La tasa moratoria es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_tasa_moratoria && !EW_checknumber(EW_this.x_tasa_moratoria.value)) {
		if (!EW_onError(EW_this, EW_this.x_tasa_moratoria, "TEXT", "La tasa moratoria es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "SELECT", "La forma de pago es requerido."))
			validada = false;
	}	
	if (validada == true && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
			validada = false;
	}
	if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "El banco y la cuenta son requeridos."))
			validada = false;
	}	
	if (validada == true && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La refencia es requerida."))
			validada = false;
	}

	EW_this.a_edit.value = "U";

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
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">FONDOS - CREDITOS<br>
  <br>
    <a href="php_fondeo_creditolist.php">Regresar a la lista</a></span></p>
<form name="creditoedit" id="creditoedit" action="php_fondeo_creditoedit.php" method="post">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_fondeo_credito_id" value="<?php echo $x_fondeo_credito_id; ?>"  />
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<br />
<table width="700" cellpadding="1" cellspacing="2" border="0" class="phpmaker" >
  <tr>
    <td width="80" class="ewTableHeaderThin"><span>Cr&eacute;dito No:</span></td>
    <td width="98" class="ewTableAltRow"><span> <?php echo $x_fondeo_credito_num; ?> </span></td>
    <td width="10" class="ewTableAltRow">&nbsp;</td>
    <td width="100" class="ewTableHeaderThin">Status</td>
    <td width="220" class="ewTableAltRow"><span>
      <?php
if ((!is_null($x_fondeo_credito_status_id)) && ($x_fondeo_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_fondeo_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_fondeo_credito_status_id = $x_fondeo_credito_status_id; // Backup Original Value
$x_fondeo_credito_status_id = $sTmp;
?>
      <?php echo $x_fondeo_credito_status_id; ?>
      <?php $x_fondeo_credito_status_id = $ox_fondeo_credito_status_id; // Restore Original Value ?>
      </span></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Otrogamiento</span></td>
    <td class="ewTableAltRow">
      <span>
        <input type="text" name="x_fecha_otrogamiento" id="x_fecha_otrogamiento" value="<?php echo FormatDateTime(@$x_fecha_otrogamiento,7); ?>">
        &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_otrogamiento" alt="Calendario" style="cursor:pointer;cursor:hand;">
        <script type="text/javascript">
    Calendar.setup(
    {
    inputField : "x_fecha_otrogamiento", // ID of the input field
    ifFormat : "%d/%m/%Y", // the date format
    button : "cx_fecha_otrogamiento" // ID of the button
    }
    );
    </script>
        </span>    </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Vencimiento</td>
    <td class="ewTableAltRow"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Importe</span></td>
    <td class="ewTableAltRow"><span>
      <?php //echo "$".FormatNumber(@$x_importe,2,0,0,1); ?>
      <input type="text" name="x_importe" id="x_importe" value="<?php echo @$x_importe; ?>" onKeyPress="return solonumeros(this,event)">
      </span></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Tasa</td>
    <td class="ewTableAltRow"><span>
      <input name="x_tasa" type="text" id="x_tasa" value="<?php echo htmlspecialchars(@$x_tasa) ?>" size="8" maxlength="5" />    
      %</span></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Num. pagos </td>
    <td class="ewTableAltRow">
	<input name="x_num_pagos" type="text" id="x_num_pagos" value="<?php echo @$x_num_pagos; ?>" size="8" maxlength="5" />    </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Tasa moratoria</td>
    <td class="ewTableAltRow"><span><input name="x_tasa_moratoria" type="text" id="x_tasa_moratoria" value="<?php echo htmlspecialchars(@$x_tasa_moratoria) ?>" size="8" maxlength="5"> 
      %</span></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">IVA</td>
    <td class="ewTableAltRow"><input type="radio" name="x_iva" value="1" <?php if($x_iva == 1){ echo "checked";} ?> />
      &nbsp;SI
      &nbsp;&nbsp;
      <input type="radio" name="x_iva" value="2" <?php if($x_iva == 2){ echo "checked";} ?>/>
      &nbsp;NO </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">&nbsp;</td>
    <td class="ewTableAltRow">&nbsp;</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Plazo</span></td>
    <td class="ewTableAltRow"><span>
		<?php 
		$sSqlWrk = "SELECT descripcion FROM plazo where plazo_id = $x_plazo";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>		
<input type="hidden" name="x_plazo" id="x_plazo" value="<?php echo @$x_plazo; ?>">    
    </span></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Forma de Pago </td>
    <td class="ewTableAltRow">
<?php 

/*
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
*/		
		?>
 <!---       <input type="hidden" name="x_forma_pago_id" id="x_forma_pago_id" value="<?php //echo @$x_forma_pago_id; ?>" />   
 --->

<?php
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>    </td>
  </tr>
  
  <tr>
    <td colspan="5" class="ewTableRow"><label>
      <input type="button" name="btnrecalcular" id="btnrecalcular" value="Modificar Cr&eacute;dito" onclick="modifica(2)" />
      </label></td>
  </tr>
  </table>
<p>
<p>

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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `fondeo_credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;
	$sWhere .= "(`fondeo_credito_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_fondeo_credito_id"] = $row["fondeo_credito_id"];
		$GLOBALS["x_fondeo_credito_num"] = $row["credito_num"];		
		$GLOBALS["x_cliente_num"] = $row["cliente_num"];				
		$GLOBALS["x_fondeo_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fondeo_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_iva"] = $row["iva"];		
		if(empty($GLOBALS["x_iva"])){
			$GLOBALS["x_iva"] = 2;
		}
		$GLOBALS["x_plazo"] = $row["plazo_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_banco_id"] = $row["banco_id"];		
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_num_pagos"] = $row["num_pagos"];				
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];						
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `fondeo_credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;	
	$sWhere .= "(`fondeo_credito_id` = " . addslashes($sTmp) . ")";
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
		
		$theValue = ($GLOBALS["x_fondeo_credito_status_id"] != "") ? intval($GLOBALS["x_fondeo_credito_status_id"]) : "NULL";
		$fieldList["`credito_status_id`"] = $theValue;
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia_pago`"] = $theValue;
		
	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_otrogamiento`"] = $theValue;


	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;


	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
	$fieldList["`forma_pago_id`"] = $theValue;


	// Field tasa
	$theValue = ($GLOBALS["x_tasa"] != "") ? " '" . doubleval($GLOBALS["x_tasa"]) . "'" : "0";
	$fieldList["`tasa`"] = $theValue;

	$theValue = ($GLOBALS["x_iva"] != "") ? " '" . intval($GLOBALS["x_iva"]) . "'" : "2";
	$fieldList["`iva`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo"] != "") ? intval($GLOBALS["x_plazo"]) : "0";
	$fieldList["`plazo_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
	$fieldList["`fecha_vencimiento`"] = $theValue;

	// Field tasa_moratoria
	$theValue = ($GLOBALS["x_tasa_moratoria"] != "") ? " '" . doubleval($GLOBALS["x_tasa_moratoria"]) . "'" : "0";
	$fieldList["`tasa_moratoria`"] = $theValue;


	// 
	$theValue = ($GLOBALS["x_num_pagos"] != "") ? intval($GLOBALS["x_num_pagos"]) : "0";
	$fieldList["`num_pagos`"] = $theValue;








		// update
		$sSql = "UPDATE `fondeo_credito` SET ";
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
