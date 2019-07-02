<?php include("../db.php");?>
<?php include ("../phpmkrfn.php") ?>
<?php
header('Content-Type: text/html; charset=ISO-8859-1');
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_respuesta_mapa = '<table width="100%">
           <tr>
            <td colspan="3" bgcolor="#FFE6E6"><div align="center">Direccion en mapa Negocio</div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div> Direcci&oacute;n negocio:
                <input id="address2" type="textbox" value="Mexico, DF" size="60" />
              <input type="button" value="Buscar" onclick="codeAddress2();" />
              <input onclick="deleteOverlays2();" type="button" value="Eliminar marcador"/>
            </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><center>
              <div id="map_canvas2" style="top:30px;width:800px; height:400px"></div>
            </center>
              <input type="hidden" name="x_latlong2" id="x_latlong2" /></td>
            <td>&nbsp;</td>
          </tr></table>';
echo $x_respuesta_mapa;

?>