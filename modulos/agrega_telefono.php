<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_cliente_id = $_GET["x_cliente_id"];
$x_tipo_telefono = $_GET["x_tipo_telefono"];
$x_pertenece = $_GET["x_pertenece"];
//echo "cliente id".$x_cliente_id ."<br> tipo telefono".$x_tipo_telefono."<br>";
if($x_pertenece == 1){
	$sqlTelefono = "Select * from telefono where cliente_id = $x_cliente_id and telefono_tipo_id = $x_tipo_telefono ";
	}else if($x_pertenece == 0){
		$sqlTelefono = "Select * from telefono where cliente_id = $x_cliente_id and telefono_tipo_id = $x_tipo_telefono ";
		}


$rsTelefono = phpmkr_query($sqlTelefono,$conn)or die ("Error al sele tel".phpmkr_error(). $sqlTelefono);

$x_tabla = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr bgcolor="#FFE6E6">
    <td height="25" colspan="4"><center> Lista de telefonos </center></td>
    </tr>';
	$x_numero = 1;
while($rowTelefono = phpmkr_fetch_array($rsTelefono)){
	$x_renglon = '<tr>
    <td>'.$x_numero .':</td>
    <td>'.$rowTelefono["numero"].'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr> ';
	$x_tabla = $x_tabla .$x_renglon;
	
	}
$x_tabla = $x_tabla."</table>";


$x_tabla_tel = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr bgcolor="#FFE6E6">
    <td height="25" colspan="4"><center> Agregar nuevo </center></td>
    </tr>';
if($x_tipo_telefono == 1){
	// ES TELEFONO DE CASA
$x_tabla_tel = 	$x_tabla_tel.'

  <tr>
    <td>Numero telefonico</td>
    <td><input type="text" name="x_numero_telefonico" value="" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>Comentario</td>
    <td><input type="text" name="x_comentario" value="" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input  type="submit" value="Guardar" /> </td>
  </tr>';
 
	}else if($x_tipo_telefono == 2){
		//es celular
		
		
	$x_entidad_idList = "<select name=\"x_compania_celular_1\" id=\"x_compania_celular_1\" $x_readonly2 class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_1) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";	
	$x_tabla_tel = 	$x_tabla_tel.'	
  <tr>
    <td>Numero telefonico</td>
    <td><input type="text" name="x_numero_telefonico" value="" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>Comentario</td>
    <td><input type="text" name="x_comentario" value="" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Compañia</td>
    <td>'.$x_entidad_idList.'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input  type="submit" value="Guardar" /> </td>
  </tr>
  ';
		}
$x_tabla_tel = $x_tabla_tel.'</table>';
$x_tabla_v = '<table  width="100%" border="0" cellspacing="0" cellpadding="0" align="center"><tr>
<td>&nbsp;</td></tr></table>';
echo $x_tabla.$x_tabla_v.$x_tabla_tel;
?>