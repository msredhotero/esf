<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creación de Facturas Electrónicas</title>
</head>
<body style="background-color:#666666;">
<table width="1050" height="150" border="0" align="center" cellspacing="0" background="imgs/top_1050.png">
  <tr>
    <td width="1220">&nbsp;</td>
  </tr>
</table>
<table width="1050" border="0" align="center" cellspacing="0" background="imgs/center_1050.png">
  <tr>
    <td width="1220"><table width="98%" border="0" align="center">
      <tr>
        <td width="548" bgcolor="#0087BD" style="color:#FFFFFF;">Módulo de Facturación Electrónica </td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">
		<form id="form1" name="form1" method="post" action="crea_cfd.php">
            <table width="100%" border="0">
              <tr>
                <td colspan="4" align="center">
				<br /><div  style="color:#000066; font-size:24px; font-family:Arial, Helvetica, sans-serif;">
				Datos del CFD</div></td>
              </tr>
              <tr>
                <td bgcolor="#E6E6E6" align="center"><strong>* Forma de Pago </strong></td>
                <td bgcolor="#E6E6E6" align="center"><strong>* Tipo</strong> <strong>CFD</strong></td>
                <td bgcolor="#E6E6E6" align="center"><strong>* Fecha  y Hora </strong></td>
                <td width="38%" align="center" bgcolor="#E6E6E6"><strong>* Aprobación </strong></td>
                </tr>
              <tr>
                <td width="24%" bgcolor="#EAEFFF" align="center">
				<input name="forma_pago" type="text" id="forma_pago" value="pago en una sola exhibición" size="30" maxlength="50" />
				</td>
                <td width="16%" bgcolor="#EAEFFF" align="center"><select name="tipo_cfd" id="tipo_cfd">
                  <option value="ingreso" selected="selected">ingreso</option>
                  <option value="egreso">egreso</option>
                  <option value="traslado">traslado</option>
                </select>
				</td>
                <td width="22%" bgcolor="#EAEFFF" align="center">
				<input name="fecha" type="text" id="fecha" 
					<?php
						 echo 'value="'.date("Y-m-d").'T'.date("H:i:s").'"';						 
					?>
				size="22" maxlength="10" /></td>
                <td align="center" bgcolor="#EAEFFF">Certificado: 
                  <input name="numero_certificado" type="text" id="numero_certificado" value="10001200000000022517" size="10" maxlength="24" />
                   Aprobacion:
                   <input name="aprobacion" type="text" id="aprobacion" value="1" size="4" maxlength="10" />
Año:
<input name="year_aprobacion" type="text" id="year_aprobacion" value="2010" size="4" maxlength="10" /></td>
                </tr>
              <tr>
                <td bgcolor="#E6E6E6" align="center"><strong>* Serie</strong></td>
                <td bgcolor="#E6E6E6" align="center"><strong>* Folio</strong></td>
                <td bgcolor="#E6E6E6" align="center"><strong>* Días de Crédito </strong></td>
                <td bgcolor="#E6E6E6" align="center"><strong>* IVA en % </strong></td>
                </tr>
              <tr>
                <td bgcolor="#EAEFFF" align="center"><input name="serie" type="text" id="serie" value="A" size="3" maxlength="6" /></td>
                <td bgcolor="#EAEFFF" align="center"><input name="folio" type="text" id="folio" value="1" size="4" maxlength="8" /></td>
                <td bgcolor="#EAEFFF" align="center">
                  <input name="dias_credito" type="text" id="dias_credito" value="60" size="6" maxlength="3" />                </td>
                <td bgcolor="#EAEFFF" align="center"><input name="iva" type="text" id="iva" value="16" size="6" maxlength="3" /></td>
                </tr>
              <tr>
                <td colspan="4" bgcolor="#FFFFFF" align="center">
			
				<br /><div  style="color:#000066; font-size:24px; font-family:Arial, Helvetica, sans-serif;">
				Datos del Emisor</div>
				
				</td>
              </tr>
              <tr>
                <td colspan="4" bgcolor="#FFFFFF">
				
					<table width="100%" border="0">
					<tr>
					  <td bgcolor="#E6E6E6" align="center"><strong>* RFC del Cliente </strong></td>
					  <td colspan="4" align="center" bgcolor="#E6E6E6"><strong>* Razón Social del Cliente </strong></td>
					  </tr>
					<tr>
					  <td bgcolor="#EAEFFF" align="center"><input name="rfc" type="text" id="rfc" value="CAUR390312S87" size="30" maxlength="60" /></td>
					  <td colspan="4" align="left" bgcolor="#EAEFFF"> 
					    <input name="razon_social" type="text" id="razon_social" value="Rosa María Calderón Uriegas" size="120" maxlength="128" />
					   </td>
					  </tr>
					<tr>
						<td bgcolor="#E6E6E6" align="center"><strong>* Calle</strong></td>
						<td bgcolor="#E6E6E6" align="center"><strong>* Núm. Ext. </strong></td>
						<td bgcolor="#E6E6E6" align="center">Número Int.</td>
						<td bgcolor="#E6E6E6" align="center"><strong>* Colonia</strong></td>
						<td bgcolor="#E6E6E6" align="center">Localidad/Ciudad/Pueblo</td>
					  </tr>
					  <tr>
						<td bgcolor="#EAEFFF" align="center">
							<input name="calle" type="text" id="calle" value="Topochico" size="30" maxlength="60" />						</td>
						<td bgcolor="#EAEFFF" align="center">
							<input name="num_exterior" type="text" id="num_exterior" value="52" size="10" maxlength="10" />						</td>
						<td bgcolor="#EAEFFF" align="center">
						<input name="num_interior" type="text" id="num_interior" size="10" maxlength="10" /></td>
						<td bgcolor="#EAEFFF" align="center">
							<input name="colonia" type="text" id="colonia" value="Jardines del Valle" size="35" maxlength="45" />						</td>
						<td bgcolor="#EAEFFF" align="center">
						  <input name="localidad" type="text" id="localidad" value="Monterrey" size="35" maxlength="45" />						</td>
					  </tr>
					  <tr>
						<td bgcolor="#E6E6E6" align="center"><strong>* Municipio</strong>/<strong>Delegación</strong></td>
						<td bgcolor="#E6E6E6" align="center"><strong>* Estado</strong></td>
						<td bgcolor="#E6E6E6" align="center"><strong>* País</strong></td>
						<td align="center" bgcolor="#E6E6E6"><strong>* Código Postal </strong></td>
					    <td align="center" bgcolor="#E6E6E6">Referencia</td>
					  </tr>
					  <tr>
						<td bgcolor="#EAEFFF" align="center">
							<input name="municipio" type="text" id="municipio" value="Monterrey" size="30" maxlength="50" />						</td>
						<td bgcolor="#EAEFFF" align="center">
							<input name="estado" type="text" id="estado" value="Nuevo León" size="10" maxlength="30" />						</td>
						<td bgcolor="#EAEFFF" align="center">
							<input name="pais" type="text" id="pais" value="México" size="10" maxlength="10" />						</td>
						<td align="center" bgcolor="#EAEFFF">
							<input name="codigo_postal" type="text" id="codigo_postal" value="95465"  size="10" maxlength="10" />						</td>
				        <td align="center" bgcolor="#EAEFFF"><input name="referencia" type="text" id="referencia" size="35" maxlength="45" /></td>
					  </tr>
					  <tr>
					    <td colspan="5" align="center" bgcolor="#FFFFFF">
						<br /><div  style="color:#000066; font-size:24px; font-family:Arial, Helvetica, sans-serif;">
				Detalle de Conceptos</div>
						</td>
				      </tr>
					</table>
				</td>
              </tr>
            </table>
			
            <table width="100%" border="0">
            <tr>
              <td bgcolor="#E6E6E6" align="center"><strong>Unidad</strong></td>
              <td bgcolor="#E6E6E6" align="center"><strong>Concepto</strong></td>
              <td bgcolor="#E6E6E6" align="center"><strong>Precio Unitario</strong></td>
              <td bgcolor="#E6E6E6" align="center"><strong>Cantidad</strong></td>
              </tr>
              
              <tr>
                <td width="15%" bgcolor="#EAEFFF"><select name="unidad1" id="unidad1">
                  <option value="Caja" selected="selected">Caja</option>
                  <option value="Pieza">Pieza</option>
                  <option value="Kilogramo">Kilogramo</option>
                  <option value="Litro">Litro</option>
                  <option value="Metro">Metro</option>
                </select>                </td>
                <td width="68%" bgcolor="#EAEFFF"><input name="d1" type="text" id="d1" value="Vasos decorados" size="100" maxlength="128" />                </td>
                <td width="9%" bgcolor="#EAEFFF">
                    <input name="precio1" type="text" id="precio1" value="20" size="15" maxlength="15" /></td>
                <td width="8%" bgcolor="#EAEFFF">                  <input name="cantidad1" type="text" id="cantidad1" value="10" size="10" maxlength="10" />                </td>
                </tr>
              <tr>
                <td bgcolor="#EAEFFF"><select name="unidad2" id="unidad2">
                  <option value="Caja">Caja</option>
                  <option value="Pieza" selected="selected">Pieza</option>
                  <option value="Kilogramo">Kilogramo</option>
                  <option value="Litro">Litro</option>
                  <option value="Metro">Metro</option>
                                                                </select></td>
                <td bgcolor="#EAEFFF">                  <input name="d2" type="text" id="d2" value="Charola metálica" size="100" maxlength="128" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="precio2" type="text" id="precio2" value="150" size="15" maxlength="15" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="cantidad2" type="text" id="cantidad2" value="1" size="10" maxlength="10" />                </td>
                </tr>
              <tr>
                <td bgcolor="#EAEFFF"><select name="unidad3" id="unidad3">
                  <option value="Caja">Caja</option>
                  <option value="Pieza" selected="selected">Pieza</option>
                  <option value="Kilogramo">Kilogramo</option>
                  <option value="Litro">Litro</option>
                  <option value="Metro">Metro</option>
                                </select></td>
                <td bgcolor="#EAEFFF">                  <input name="d3" type="text" id="d3" size="100" maxlength="128" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="precio3" type="text" id="precio3" size="15" maxlength="15" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="cantidad3" type="text" id="cantidad3" size="10" maxlength="10" />                </td>
                </tr>
              <tr>
                <td bgcolor="#EAEFFF"><select name="unidad4" id="unidad4">
                  <option value="Caja">Caja</option>
                  <option value="Pieza" selected="selected">Pieza</option>
                  <option value="Kilogramo">Kilogramo</option>
                  <option value="Litro">Litro</option>
                  <option value="Metro">Metro</option>
                                </select></td>
                <td bgcolor="#EAEFFF">                  <input name="d4" type="text" id="d4" size="100" maxlength="128" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="precio4" type="text" id="precio4" size="15" maxlength="15" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="cantidad4" type="text" id="cantidad4" size="10" maxlength="10" />                </td>
                </tr>
              <tr>
                <td bgcolor="#EAEFFF"><select name="unidad5" id="unidad5">
                  <option value="Caja">Caja</option>
                  <option value="Pieza" selected="selected">Pieza</option>
                  <option value="Kilogramo">Kilogramo</option>
                  <option value="Litro">Litro</option>
                  <option value="Metro">Metro</option>
                                </select></td>
                <td bgcolor="#EAEFFF">                  <input name="d5" type="text" id="d5" size="100" maxlength="128" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="precio5" type="text" id="precio5" size="15" maxlength="15" />                </td>
                <td bgcolor="#EAEFFF">                  <input name="cantidad5" type="text" id="cantidad5" size="10" maxlength="10" />                </td>
                </tr>
              <tr>
                <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
              <tr>
                <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                <td bgcolor="#E6E6E6" align="center">Descuento:</td>
                <td bgcolor="#EAEFFF"><div align="center">
                  <input name="descuento" type="text" id="descuento" value="5.25" size="10" maxlength="10" />
                </div></td>
              </tr>
              <tr>
                <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
              <tr>
                <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                <td colspan="2" bgcolor="#FFFFFF" align="center">
                    <input type="submit" name="Submit" value=" Crear CFD " />                    </td>
                </tr>
            </table>
          </form></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="1050" height="45" border="0" align="center" cellspacing="0" background="imgs/down_1050.png">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>