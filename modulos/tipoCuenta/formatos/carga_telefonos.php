<?php include ("../../../db.php") ?>
<?php include ("../../../phpmkrfn.php") ?>
<?php
//conexion con db
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
// pagina para carga los campo de telefono ne el formulario de solicitud
$x_tipo = $_GET["x_tipo_campo"];
$x_id = $_GET["x_id"];
if($x_tipo == 1){
	echo '<table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_';echo $x_id; echo'" type="text" value="" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><select name="x_comentario_casa_';echo $x_id; echo'">
        <option value="">Seleccione</option>
         <option value="Domicilio">Domicilio</option>
         <option value="Negocio">Negocio</option>
        </select></td>
      </tr>
    </table>';
	/*echo '<td width="6%">Telefono</td>
    <td width="15%"><input name="x_telefono_casa_$x_id" type="text" value="" size="10" maxlength="15"/></td>
    <td width="8%">Comentario</td>
    <td width="15%"><input name="c_comentario_casa_$x_id" type="text" value="" size="30" maxlength="250" /></td>';*/
	}else{	
		$x_entidad_idList = "<select name=\"x_compania_celular_$x_id\" id=\"x_compania_celular_$x_id\" class=\"texto_normal\">";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . " SQL:" . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id) {
					$x_entidad_idList .= "selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";	
			
		
		
echo '<table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_';echo $x_id; echo'" type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%">';echo $x_entidad_idList;echo'</td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_';echo $x_id; echo'" size="30" maxlength="250" /></td>
      </tr>
    </table>';			
		}

?>