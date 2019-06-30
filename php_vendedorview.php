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
$x_vendedor_id = Null;
$x_sucursal_id = Null;
$x_vendedor_status_id = Null;
$x_promotor_id = Null;
$x_nombre_completo = Null;
$x_telefono_movil = Null;
$x_telefono_fijo = Null;
$x_fecha_registro = Null;
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
	header("Locationphp_vendedorlist.php"); 
}
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST,USER,PASS,DB,PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location php_vendedorlist.php");
		}
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">VENDEDOR<br><br>
<a href="php_vendedorlist.php">Regresar a la lista</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td width="193" class="ewTableHeaderThin"><span>vendedor id</span></td>
		<td width="795" class="ewTableAltRow"><span>
<?php echo $x_vendedor_id; ?>
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
		<td class="ewTableHeaderThin"><span>vendedor status id</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_vendedor_status_id)) && ($x_vendedor_status_id <> "")) {
	$sSqlWrk = "SELECT * FROM `vendedor_status`";
	$sTmp = $x_vendedor_status_id;
	$sTmp = (!get_magic_quotes_gpc()) ? addslashes($sTmp) : $sTmp;
	$sSqlWrk .= " WHERE `vendedor_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vendedor_status_id = $x_vendedor_status_id; // Backup Original Value
$x_vendedor_status_id = $sTmp;
?>
<?php echo $x_vendedor_status_id; ?>
<?php $x_vendedor_status_id = $ox_vendedor_status_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>promotor id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_promotor_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>nombre completo</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_completo; ?>
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
<?php echo $x_telefono_movil; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>telefono fijo</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono_fijo; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>fecha registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
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
	$sSql = "SELECT * FROM `vendedor`";
	$sSql .= " WHERE `vendedor_id` = " . $sKeyWrk;
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
		$GLOBALS["x_vendedor_id"] = $row["vendedor_id"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_vendedor_status_id"] = $row["vendedor_status_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_telefono_movil"] = $row["telefono_movil"];
		$GLOBALS["x_telefono_fijo"] = $row["telefono_fijo"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		
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
