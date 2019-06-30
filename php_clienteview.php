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

// Get key
$x_cliente_id = @$_GET["cliente_id"];
if (($x_cliente_id == "") || ((is_null($x_cliente_id)))) {
	$x_cliente_id = @$_POST["x_cliente_id"];
	if (($x_cliente_id == "") || ((is_null($x_cliente_id)))) {
		ob_end_clean(); 
		header("Location: php_clientelist.php"); 
		exit();
	}
}

//$x_cliente_id = (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">CLIENTES<br>
  <br>
<a href="php_clientelist.php">Regresar a la lista</a>&nbsp;
<a href="<?php if ($x_cliente_id <> "") {echo "php_clienteedit.php?cliente_id=" . urlencode($x_cliente_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar Datos Generales </a>&nbsp;<a href="<?php if ($x_cliente_id <> "") {echo "php_clientedelete.php?cliente_id=" . urlencode($x_cliente_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a>&nbsp;
</span></p>
<p>
<form name="clienteview" method="post" action="php_clienteview.php">
<input type="hidden" name="x_cliente_id" value="<?php echo $x_cliente_id; ?>" />
<table class="ewTable">
	<tr>
		<td width="169" class="ewTableHeaderThin"><span>No</span></td>
		<td width="819" class="ewTableAltRow"><span>
<?php echo $x_cliente_id; ?>
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre(s)</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_completo; ?>
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Apellido Paterno</td>
	  <td class="ewTableAltRow"><?php echo $x_apellido_paterno; ?></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Apellido Materno</td>
	  <td class="ewTableAltRow"><?php echo $x_apellido_materno; ?></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Tipo de negocio</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_tipo_negocio; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin">Edad</td>
		<td class="ewTableAltRow"><span>
<?php echo $x_edad; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Sexo</span></td>
		<td class="ewTableAltRow"><span>
<?php
switch ($x_sexo) {
	case "1":
		$sTmp = "M";
		break;
	case "2":
		$sTmp = "F";
		break;
	default:
		$sTmp = "";
}
$ox_sexo = $x_sexo; // Backup Original Value
$x_sexo = $sTmp;
?>
<?php echo $x_sexo; ?>
<?php $x_sexo = $ox_sexo; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Estado Civil</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_estado_civil_id)) && ($x_estado_civil_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `estado_civil`";
	$sTmp = $x_estado_civil_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `estado_civil_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_estado_civil_id = $x_estado_civil_id; // Backup Original Value
$x_estado_civil_id = $sTmp;
?>
<?php echo $x_estado_civil_id; ?>
<?php $x_estado_civil_id = $ox_estado_civil_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Numero de hijos</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_numero_hijos; ?>
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Numero de hijos Dep. </td>
	  <td class="ewTableAltRow"><?php echo $x_numero_hijos_dep; ?></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre del Conyuge</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_conyuge; ?>
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Email</td>
	  <td class="ewTableAltRow"><span>
	  	<a href="mailto:<?php echo $x_email; ?>">
		<?php echo $x_email; ?>		</a>
		</span>	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Usuario : Password </span></td>
	  <td class="ewTableAltRow"><span>
	    <?php
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
	$x_usuario_id = "No asignado &nbsp; <input type=\"button\" name=\"x_usuario\" value=\"...\" onclick=\"window.open('php_cat_usuarioadd.php?x_cliente_id=$x_cliente_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}else{
	$x_usuario_id .= "&nbsp; <input type=\"button\" name=\"x_usuario\" value=\"Cambiar\" onclick=\"window.open('php_cat_usuarioedit.php?x_cliente_id=$x_cliente_id','Usuarios','width=500,height=300,left=250,top=150,scrollbars=yes');\"/>";
}
echo $x_usuario_id;
$x_usuario_id = $ox_usuario_id; // Restore Original Value 

?>
      </span></td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableHeaderThin">Direcciones</td>
	  </tr>
	<tr>
	  <td colspan="2" align="left" valign="top" >
	  <iframe name="direcciones" src="php_cat_direcciones.php?cliente_id=<?php echo $x_cliente_id; ?>" style="margin-left:2px; width:900px; height:300px; border:0 " allowtransparency="true"></iframe>	  </td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableRow">&nbsp;</td>
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
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
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
