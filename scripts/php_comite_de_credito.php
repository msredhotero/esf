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
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_usuario_rol_id = Null; 
$ox_usuario_rol_id = Null;
$x_usuario_status_id = Null; 
$ox_usuario_status_id = Null;
$x_usuario = Null; 
$ox_usuario = Null;
$x_clave = Null; 
$ox_clave = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_caduca = Null; 
$ox_fecha_caduca = Null;
$x_fecha_visita = Null; 
$ox_fecha_visita = Null;
$x_visitas = Null; 
$ox_visitas = Null;
$x_email = Null; 
$ox_email = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_usuario_id = @$_GET["usuario_id"];
if (empty($x_usuario_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_usuario_rol_id = @$_POST["x_usuario_rol_id"];
	$x_usuario_status_id = @$_POST["x_usuario_status_id"];
	$x_usuario = @$_POST["x_usuario"];
	$x_clave = @$_POST["x_clave"];
	$x_nombre = @$_POST["x_nombre"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_caduca = @$_POST["x_fecha_caduca"];
	$x_fecha_visita = @$_POST["x_fecha_visita"];
	$x_visitas = @$_POST["x_visitas"];
	$x_email = @$_POST["x_email"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_usuariolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_usuariolist.php");
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
if (EW_this.x_usuario_rol_id && !EW_hasValue(EW_this.x_usuario_rol_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_rol_id, "SELECT", "Los permisos de lusuario son requeridos."))
		return false;
}
if (EW_this.x_usuario_status_id && !EW_hasValue(EW_this.x_usuario_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_status_id, "SELECT", "El status es requerido."))
		return false;
}
if (EW_this.x_usuario && !EW_hasValue(EW_this.x_usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario, "TEXT", "La cuenta es requerida."))
		return false;
}

return true;
}

//-->
</script>


<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
<script src="scripts/valida_form/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/valida_form/jquery.validationEngine.js"  type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="scripts/valida_form/css/validationEngine.jquery.css" type="text/css"/>
<script language="javascript">
$(document).ready(function(e) {
	
	$("#comite_credito_form").validationEngine(); 
	var total_registros = $('.x_comite_solicitud').length;
	$('#x_total_registros').attr('value', total_registros);
	
	

		
	$('#x_agregar').click(function (evento){
		var total_registros_new =$('.x_comite_solicitud').length + 1;
		var total_registros =$('.x_comite_solicitud').length ;
		var newNum = total_registros_new;
		alert(total_registros);
		
	
		
		$('#x_total_registros').attr('value', total_registros_new);
		var newElem = $('#x_registro_'+total_registros+'').clone(true).attr({'id':'x_registro_'+total_registros_new,'class':'x_comite_solicitud'});
		newElem.find('td:eq(0) input:eq(0)').attr({'name':'x_numero_cliente_'+newNum,'id':'x_numero_cliente_'+newNum,'value': 0});
		newElem.find('td:eq(1) input:eq(0)').attr({'name':'x_nombre_cliente_'+newNum,'id':'x_nombre_cliente_'+newNum,'value': " "});
		newElem.find('td:eq(2) input:eq(0)').attr({'name':'x_cantidad_solicitada_'+newNum,'id':'x_cantidad_solicitada_'+newNum,'value': 0});
		newElem.find('td:eq(3) select:eq(0)').attr({'name':'x_pld_'+newNum,'id':'x_pld_'+newNum,'value': 0});
		newElem.find('td:eq(4) input:eq(0)').attr({'name':'x_documentos_'+newNum,'id':'x_documentos_'+newNum,'value': ""});
		newElem.find('td:eq(5) input:eq(0)').attr({'name':'x_cantidad_otorgada_'+newNum,'id':'x_cantidad_otorgada_'+newNum,'value': 0});
		newElem.find('td:eq(6) select:eq(0)').attr({'name':'x_status_'+newNum,'id':'x_status_'+newNum,'value': 0});
		newElem.find('td:eq(7) input:eq(0)').attr({'name':'x_observaciones_'+newNum,'id':'x_observaciones_'+newNum,'value': ""});
		newElem.find('td:eq(8) input:eq(0)').attr({'id':'x_quitar_registro_'+newNum});
		var ultimo = total_registros;
		
		$('#x_registro_' + ultimo).after(newElem);
		
		//cambiamos las propiedades de los elemnetos del formulario
		var total_registros = $('.x_comite_solicitud').length;
		
		});//click agregar	
		
		
		$('.x_quita_registro').click(function (evento){
		var total_registros = $('.x_comite_solicitud').length ;
		if (total_registros > 1){
		var total_menor =  total_registros - 1;
		$('#x_total_registros').attr('value', total_menor);
		var resgitro_actual = $(this).attr("id").split("_");
		var cadena = resgitro_actual;
		alert(resgitro_actual);
		alert(resgitro_actual[3]);		
		var capaEliminar = "x_registro_"+resgitro_actual[3];
		
		$('#'+capaEliminar+'').remove();
		
		}else{
			
			}
		});//click quitar registro
		
		$(".x_participantes_comite").change(function(){
			if($(this).is(':checked')){
				// si esta seleccionado se agraga a la lista
				}else{
					// si no esta seleccionado se quita de la lista
					
					}
});

		$('#x_firma_comite').click(function (evento){
			// buscamos todos los checks que estan selecionados y los mandamos como parametros a la pagina que se cargara.
			var participantes = "";
			$("input:checkbox:checked").each(function(){
  			//cada elemento seleccionado
			participantes = participantes+ $(this).val()+ "-";  
  			});
			alert(participantes);
			if(participantes.length>1){
				//cargamos la pagina para las firmas de los participantes
				alert("entra a load data");
				$('#x_integrantes_comite').load('php_firma_comite.php?x_lista_integrantes='+participantes+'');				
				}else{
				alert("DEBE SELECCIONAR A LOS INTEGRANTES DE COMITE");
					}
			
		});
		$(".x_password").live("change",function (evento){
		//$('.x_password').change(
			// si el elemento del paswir pierde el foco validamos que la contraseña se acorrecta
			var password = $(this).val();
			var integrante = $(this).attr('id').split("_");
			var integrante_actual = integrante[2];
			var lista_integrantes = $('#lista_integrantes_comite').val();
			var nueva_lista = lista_integrantes+integrante_actual+"-";
			alert(nueva_lista);
			var capa_editar = "x_firma_correcta_"+integrante_actual+"";
			$('#'+capa_editar+"").load('php_firma_comite_valida.php?id='+integrante_actual+'&password='+password+'',function(){
				//verfico el valor del campo oculto 1 sig que la contraseña fue correcta
				var oculto =  $('#x_oculto_'+integrante_actual).val();
				if(oculto > 0){
					//quitamos el campo password del formulario
					$('#x_password_'+integrante_actual).remove();
					$('#lista_integrantes_comite').val(nueva_lista);
					}
				// verificacmos si todos los ocultos estan en 1; si es asi ponemos el boton de registrar
				var imprime_boton = 1;
					$('.campo_oculto').each(function() {
                       var campo_culto_valor = $(this).val();
					   if(campo_culto_valor == 0){
						   imprime_boton = 0;
						   }
                    });//each campo_oculto
				
				if(imprime_boton == 1){
					$('#x_registrar_comite').removeAttr("disabled");
					//contamos cuantas solicitudes hay
					}
				
				});
			});// focusout in x_password
	
	$('#x_siguente_paso').click(function (evento){
		//verificamos que todos los campos esten llenos
		var no_reg = $('.x_comite_solicitud').length;
		var  validada = 1;
		var cadena_validada = "";
		for(i=1; i<= no_reg;i++){			
		var cliente = $('x_numero_cliente_'+no_reg).length;
		var nombre = $('x_nombre_cliente_'+no_reg).length;
		var nombre = $('x_cantidad_solicitada_'+no_reg).length;
		var nombre = $('x_pld_'+no_reg).length;
		var nombre = $('x_documentos_'+no_reg).length;
		var nombre = $('x_cantidad_otorgada_'+no_reg).length;
		var nombre = $('x_documentos_'+no_reg).length;	
		var nombre = $('x_status_'+no_reg).length;
					
		if(cliente > 0){
			// validamos que el elmento este lleno	
			var valor_elemto =  $('x_numero_cliente_'+no_reg).val();
								
			}
			
			}
		$('.valida_form').each(function() {
   				 if ($(this).val() == "") {
					 alert($(this).css());
					 $(this).css({ color: "#FFFFFF", background: "#FF0000" });
        validada = 0;
   			 }else{
				 $(this).css({ color: "#000000", background: "#FFFFFF" });
				 }
			});
			
			if(validada == 0){
				alert("TODOS LOS CAMPOS SON REQUERIDOS");
				}
		});// click x_siguente_paso
	
	});//gral

</script>

<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">USUARIOS<br>
  <br>
    <a href="php_usuariolist.php">Regresar a la lista</a></span></p>
<form name="comite_credito_form" id="comite_credito_form" action=" " method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_total_registros" id="x_total_registros"  value="0" />
<input type="hidden" name="lista_integrantes_comite" id="lista_integrantes_comite" value="" />
<input type="hidden" name="solictudes" id="solictudes" value="" />

<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<table>
<tr>
<td colspan="6">COMITE DE CREDITO</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td colspan="6">
<table>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>No. Cliente</td>
<td>Nombre</td>
<td>Cantidad solicitada</td>
<td>PLD</td>
<td>Documentos</td>
<td>Cantidad otorgada</td>
<td>Estatus</td>
<td>observaciones</td>
<td>&nbsp;</td>
</tr>
<?php
$x_no = 1;
$sqlSol =  "SELECT * FROM solicitud WHERE solicitud_status_id =  3 ";
$rsSol = phpmkr_query($sqlSol,$conn)or die ("Error al seleccionar las solcitudes en comiute".phpmkr_error()."sql:".$sqlSol);
//$x_numero_registros = mysql_num_rows($rsSol);
while($rowSol = phpmkr_fetch_array($rsSol)){
	//imprimo un reglon de la tabla por registro encontrado
	$x_solicitud_id = $rowSol["solicitud_id"];
	$x_status = $rowSol["solicitud_status_id"];	
	$x_nombre_cliente = $rowSol["nombre_completo"];
	$x_cantidad_solicitada = $rowSol["importe_solicitado"];
	$x_pld = "";
	$x_documentos = "";
	$x_cantidad_otorgada = "";
	$x_observaciones = "";
	
	$sqlCliente = "SELECT nombre_completo, apellido_paterno, apellido_materno, cliente_num FROM cliente join solicitud_cliente ON  solicitud_cliente.cliente_id = cliente.cliente_id WHERE solicitud_cliente.solicitud_id = $x_solicitud_id ";
	$rsCliente =  phpmkr_query($sqlCliente,$conn) or die ("Error al seleccionar el nombre del cliente".phpmkr_error()."sql:".$sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);
	$x_nombre_cliente =  $rowCliente["nombre_completo"]." ".$rowCliente["apellido_paterno"]." ".$rowCliente["apellido_materno"];
	$x_numero_cliente =  $rowCliente["cliente_num"];
	if(empty($x_numero_cliente)){
		$x_numero_cliente = "---";
		}	
	$x_registro =  "<tr id=\"x_registro_".$x_no."\" class=\"x_comite_solicitud\">					
					<td><input type=\"text\" class=\"validate[required]\" name=\"x_numero_cliente_".$x_no."\"  id=\"x_numero_cliente_".$x_no."\" value=\"$x_numero_cliente\" /></td>
					<td><input type=\"text\" class=\"validate[required]\"  name=\"x_nombre_cliente_".$x_no."\"  id=\"x_nombre_cliente_".$x_no."\" value=\"$x_nombre_cliente\"  /></td>
					<td><input type=\"text\" class=\"valida_form\"  name=\"x_cantidad_solicitada_".$x_no."\"  id=\"x_cantidad_solicitada_".$x_no."\" value=\"$x_cantidad_solicitada\" /> </td>
					<td><select  class=\"valida_form\" name=\"x_pld_".$x_no."\" id=\"x_pld_".$x_no."\"><option value=\"0\">Seleccione</option><option value=\"1\"> Si </option><option value=\"2\"> No </option></select></td>
					<td><input type=\"text\" class=\"valida_form\"  name=\"x_documentos_".$x_no."\" id=\"x_documentos_".$x_no."\" value=\"$x_documentos\" /></td>
					<td><input type=\"text\" class=\"valida_form\"  name=\"x_cantidad_otorgada_".$x_no."\"  id=\"x_cantidad_otorgada_".$x_no."\" value=\" \" /> </td>
					<td><select name=\"x_status_".$x_no."\" class=\"valida_form\"  id=\"x_status_".$x_no."\"><option value=\"0\">Seleccione</option><option value=\"1\"> Aprobada </option><option value=\"2\"> Rechazada </option></select></td>
					<td><textarea class=\"valida_form\"  name=\"x_observaciones_".$x_no."\" id=\"x_observaciones_".$x_no."\" cols=\"30\" rows=\"1\"> </textarea></td>
					<td><input type=\"button\"  name=\"x_quitar_registro\"  class=\"x_quita_registro\"  id=\"x_quitar_registro_".$x_no."\" value=\"Quitar de comite\" /></td>
					</tr>					
					";
	
	echo $x_registro ;
	$x_no ++;
	}


?>
	

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<?php //echo $x_registro ;
?>
<tr>
<td><input type="button" name="x_agregar" id="x_agregar" value="Agregar un caso m&aacute;s" /></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td colspan="2"><input type="button" name="x_siguiente"  id="x_siguente_paso" value="Siguente" /></td>
<td>&nbsp;</td>
</tr>

</table>
</td></tr>

<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td colspan="6">
<div id="x_integrantes_comite">
AQUI LA TABLA CON LOS PARTICIPANTES DE COMITE
<?php
$sqlIntegrantes = "	SELECT * FROM comite_credito_participantes_lista where status = 1 ";
$rsIntegrantes = phpmkr_query($sqlIntegrantes,$conn)or die ("Erro al seleccionar los integrantes de la lista de comite".phpmkr_error()."sql:".$sqlIntegrantes);
$x_n = 1;
while($rowIntegrantes = phpmkr_fetch_array($rsIntegrantes)){
$x_nombre =  $rowIntegrantes["nombre"];
$x_integrante_id = $rowIntegrantes["comite_credito_participantes_lista_id"];
if($x_n % 3 == 0 || $x_n == 1){
	// se agraga un tr a la cadena
	$x_cadena .= "<tr> ";
	}
	
	//AQUI los <TD>
	$x_cadena .= "<td>$x_nombre</td><td><input type=\"checkbox\" class=\"x_participantes_comite\"  name=\"x_participante\" id=\"x_participante\" value=\"$x_integrante_id\" /></td><td>&nbsp;</td><td>";
	
	
	
	if($x_n % 3 == 0 || $x_n == 1){
	// se agraga un tr a la cadena
	$x_cadena .= "</tr> ";
	}
$x_n++;

}

?>

<table>
<?php echo $x_cadena;?>
<tr><td colspan="5"> <input type="button" id="x_firma_comite"  value="FIRMAR"/></td></tr>
</table>
<table>

</table>

</div></td>
</tr>
</table>


<p>
<input type="submit" name="Action"  id="x_registrar_comite" value="Registrar comit&eacute; de cr&eacute;dito" disabled="disabled" >
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
	global $x_usuario_id;
	$sSql = "SELECT * FROM `usuario`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_usuario_id) : $x_usuario_id;
	$sWhere .= "(`usuario_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_usuario_rol_id"] = $row["usuario_rol_id"];
		$GLOBALS["x_usuario_status_id"] = $row["usuario_status_id"];
		$GLOBALS["x_usuario"] = $row["usuario"];
		$GLOBALS["x_clave"] = $row["clave"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_caduca"] = $row["fecha_caduca"];
		$GLOBALS["x_fecha_visita"] = $row["fecha_visita"];
		$GLOBALS["x_visitas"] = $row["visitas"];
		$GLOBALS["x_email"] = $row["email"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	
	
	foreach($_POST as $campo => $valor){
		$$campo = $valor;
		}
		
	$x_today = date("Y-m-d");	
	// primero insertamos en comite_credito
	$sqlInsertComite = "INSERT INTO `comite_credito` (`comite_credito_id` ,`fecha` ,`observaciones_generales`) ";
	$sqlInsertComite .= " VALUES (NULL ,  \"$x_today\",  \"$x_observaciones_generales\") ";
	$rsComite = phpmkr_query($sqlInsertComite,$conn);
	$x_comite_credito_id = mysql_insert_id();
	$x_total_registros = $x_total_registros +10;
	#x_total_registros
	for($i=1; $i<=$x_total_registros; $i++){
		$x_cliente = "x_numero_cliente_".$i;
		$x_nombre = "x_nombre_cliente_".$i;
		$x_solicitada = "x_cantidad_solicitada_".$i;
		$x_pld = "x_pld_".$i;
		$x_doctos = "x_documentos_".$i;
		$x_otorgada = "x_cantidad_otorgada_".$i;
		$x_status = "x_status_".$i;
		$x_obs = "x_observaciones_".$i;
		if(!empty($$x_nombre)){
		$sqlInsertCCS = "INSERT INTO  `comite_credito_solicitud` (`comite_credito_solicitud_id` ,`comite_credito_id` ,`solicitud_id` ,`no_cliente` ,`nombre_cliente` ,`cantidad_solicitadad` ,`pld` ,`documentos` ,`cantidad_otorgada` ,`status` ,`observaciones`) ";
	$sqlInsertCCS .= " VALUES (NULL ,$x_comite_credito_id,'0', ".$$x_cliente." ,  \"".$$x_nombre."\", ".$$x_solicitada.", \"".$$x_pld."\",  \"".$$x_doctos."\", ".$$x_otorgada.", ". $$x_status.",  \"". $$x_obs."\") ";
		$rsIserteCCS = phpmkr_query($sqlInsertCCS,$conn) or die ("Error al insertar en comite_credito_solitud".phpmkr_error()."sql:".$sqlInsertCCS);			
		}
		}
	$lista_integrantes_comite = trim($lista_integrantes_comite,"-");
	$x_integrantes_comite_array = explode("-",$lista_integrantes_comite);
	
	foreach($x_integrantes_comite_array as $campo => $valor){
		// por cada un lo inserto en la tabla... so
		$sqlParti = "INSERT INTO `comite_credito_participantes_activos` (`comite_credito_particpante_activo`, `comite_credito_id`, `comite_credito_participante_id`)";
		$sqlParti .= " VALUES (NULL, $x_comite_credito_id, $valor)";
		$rsParti = phpmkr_query($sqlParti,$conn)or die ("Error al insertarlos participantes del comite".phpmkr_error()."sql:".$sqlParti); 
		}
	
		
	
	
	return true;
}
?>
