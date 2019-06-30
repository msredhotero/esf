<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
include ("db.php");
include ("phpmkrfn.php");

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_cliente_id = @$_GET["cliente_id"];
// Check if valid key
if (($x_cliente_id == "") || (is_null($x_cliente_id))) {
	$x_cliente_id = @$_POST["x_cliente_id"];
	if (($x_cliente_id == "") || (is_null($x_cliente_id))) {
		ob_end_clean();
		exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direcciones</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color:transparent; border:0 ">
<script type="text/javascript">
<!--
function selpais(){
	frm = document.catpais;
	frm.submit();
}

//-->
</script>
<form name="direcciones" method="post" action="php_cat_direcciones.php" >
<input type="hidden" name="x_cliente_id" value="<?php echo $x_cliente_id; ?>">

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
<td>&nbsp;</td>
		<td valign="top"><span>
Tipo de dirección
		</span></td>
		<td valign="top"><span>
Calle
		</span></td>
		<td valign="top"><span>
Colonia
		</span></td>
		<td valign="top"><span>
Entidad
		</span></td>		
		<td valign="top"><span>
Delegación
		</span></td>
		<td valign="top"><span>
Tipo de vivienda
		</span></td>		
		<td valign="top"><span>
Propietario
		</span></td>
		<td valign="top"><span>
Codigo postal
		</span></td>
		<td valign="top"><span>
Ubicación
		</span></td>
		<td valign="top"><span>
Antiguedad
		</span></td>
		<td valign="top"><span>
Teléfono
		</span></td>
		<td valign="top"><span>
Teléfono secundario
		</span></td>
	</tr>
<?php

$sSql = "SELECT * FROM direccion JOIN delegacion on delegacion.delegacion_id = direccion.delegacion_id where direccion.cliente_id = $x_cliente_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nRecActual = 0;
while ($row = @phpmkr_fetch_array($rs)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_direccion_id = $row["direccion_id"];
		$x_cliente_id = $row["cliente_id"];
		$x_aval_id = $row["aval_id"];
		$x_promotor_id = $row["promotor_id"];
		$x_direccion_tipo_id = $row["direccion_tipo_id"];
		$x_calle = $row["calle"];
		$x_colonia = $row["colonia"];
		$x_delegacion_id = $row["delegacion_id"];
		$x_propietario = $row["propietario"];
		$x_entidad_id = $row["entidad_id"];
		$x_codigo_postal = $row["codigo_postal"];
		$x_ubicacion = $row["ubicacion"];
		$x_antiguedad = $row["antiguedad"];
		$x_vivienda_tipo_id = $row["vivienda_tipo_id"];
		$x_otro_tipo_vivienda = $row["otro_tipo_vivienda"];
		$x_telefono = $row["telefono"];
		$x_telefono_secundario = $row["telefono_secundario"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_direccion_id <> "") {echo "php_cat_direcciones_edit.php?cliente_id=$x_cliente_id&direccion_id=" . urlencode($x_direccion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Modificar</a></span></td>
<?php } ?>
		<!-- direccion_tipo_id -->
		<td><span>
<?php
if ((!is_null($x_direccion_tipo_id)) && ($x_direccion_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `direccion_tipo`";
	$sTmp = $x_direccion_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `direccion_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_direccion_tipo_id = $x_direccion_tipo_id; // Backup Original Value
$x_direccion_tipo_id = $sTmp;
?>
<?php echo $x_direccion_tipo_id; ?>
<?php $x_direccion_tipo_id = $ox_direccion_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- calle -->
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<!-- colonia -->
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<!-- entidad -->
		<td><span>
<?php
if ((!is_null($x_entidad_id)) && ($x_entidad_id <> "")) {
	$sSqlWrk = "SELECT `nombre` FROM `entidad`";
	$sTmp = $x_entidad_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `entidad_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_entidad_id = $x_entidad_id; // Backup Original Value
$x_entidad_id = $sTmp;
?>
<?php echo $x_entidad_id; ?>
<?php $x_entidad_id = $ox_entidad_id; // Restore Original Value ?>
</span></td>
		<!-- delegacion_id -->
		<td><span>
<?php
if ((!is_null($x_delegacion_id)) && ($x_delegacion_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `delegacion`";
	$sTmp = $x_delegacion_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `delegacion_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_delegacion_id = $x_delegacion_id; // Backup Original Value
$x_delegacion_id = $sTmp;
?>
<?php echo $x_delegacion_id; ?>
<?php $x_delegacion_id = $ox_delegacion_id; // Restore Original Value ?>
</span></td>
		<!-- vivienda_tipo_id -->
		<td><span>
<?php
if ((!is_null($x_vivienda_tipo_id)) && ($x_vivienda_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vivienda_tipo`";
	$sTmp = $x_vivienda_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vivienda_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vivienda_tipo_id = $x_vivienda_tipo_id; // Backup Original Value
$x_vivienda_tipo_id = $sTmp;
?>
<?php echo $x_vivienda_tipo_id; ?>
<?php $x_vivienda_tipo_id = $ox_vivienda_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- otra_delegacion -->
		<td><span>
<?php echo $x_propietario; ?>
</span></td>
		<!-- codigo_postal -->
		<td><span>
<?php echo $x_codigo_postal; ?>
</span></td>
		<!-- ubicacion -->
		<td><span>
<?php echo $x_ubicacion; ?>
</span></td>
		<!-- antiguedad -->
		<td><span>
<?php echo $x_antiguedad; ?>
</span></td>
		<!-- telefono -->
		<td><span>
<?php echo $x_telefono; ?>
</span></td>
		<!-- telefono_secundario -->
		<td><span>
<?php echo $x_telefono_secundario; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
</form>
</body>
</html>

