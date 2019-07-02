<?php session_start(); ?>
<?php ob_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../crm.css" rel="stylesheet" type="text/css" />
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php


$x_crm_caso_id = $_GET["key"];
$x_tab_id = $_GET["key2"];
$x_crm_tarea_id = $_GET["key3"];
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
if(!empty($x_crm_caso_id)){
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB,PORT);
	LoadData($conn);
}else{
	echo "No se ha espcificadoel Numero de Caso.";
	exit();
}
if(empty($x_tab_id)){
	$x_tab_id = 1;
}
?>
<br />
<br />
<?php if($x_tab_id == 1){ ?>
<table align='center' width='700' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td width='165' class="txt_negro_medio">Caso Num:</td>
        <td width="535"  ><table width="100%" border="0">
          <tr>
              <td width="23%"><span class="txt_datos_azul"><?php echo @$x_crm_caso_id; ?></span></td>
              <td width="6%">&nbsp;</td>
              <td width="20%" class="txt_negro_medio">Tipo</td>
              <td width="4%">&nbsp;</td>
              <td width="47%" class="txt_datos_azul"><?php echo @$x_tipo_caso_desc; ?></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
	  <tr>
	    <td class="txt_negro_medio" >Estado:</td>
	    <td  ><table width="100%" border="0">
          <tr>
	          <td width="23%"><span class="txt_datos_azul"><?php echo $x_caso_status; ?></span></td>
	          <td width="6%">&nbsp;</td>
	          <td width="20%" class="txt_negro_medio">Prioridad:</td>
	          <td width="4%">&nbsp;</td>
	          <td width="47%" class="txt_datos_azul"><?php echo $x_caso_prioridad; ?></td>
          </tr>
        </table></td>
  </tr>
	  <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
	  <tr>
	    <td class="txt_negro_medio" >Cuenta:</td>
	    <td  ><span class="txt_datos_azul"><?php echo $x_cuenta; ?></span></td>
	    </tr>
    <tr>
	    <td class="txt_negro_medio" >&nbsp;</td>
	    <td  >&nbsp;</td>
  </tr>
    <tr>
	  <td class="txt_negro_medio" >Fecha de registro:</td>
	  <td  ><span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_registro,7); ?></span></td>
	  </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td  >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Origen: </td>
        <td  ><span class="txt_datos_azul"><?php echo htmlentities($x_origen_caso); ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td class="txt_negro_medio" >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Solicitud:</td>
        <td class="txt_negro_medio" ><span class="txt_datos_azul"><?php echo htmlentities($x_folio); ?></span></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td class="txt_negro_medio" >&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Cr&eacute;dito:</td>
        <td class="txt_negro_medio" ><table width="100%" border="0">
          <tr>
            <td width="23%"><span class="txt_datos_azul"><?php echo htmlentities($x_credito_num); ?></span></td>
            <td width="6%">&nbsp;</td>
            <td width="20%" class="txt_negro_medio">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td width="47%" class="txt_datos_azul">
            
            <?php if(($x_crm_caso_status_id == 1) && ($_SESSION["crm_UserRolID"] == 1 || $_SESSION["crm_UserRolID"] == 3)){ ?>
            
            <a href="crm_casoedit.php?key=<?php echo $x_crm_caso_id; ?>">
            <input type="button" name="x_cerrar_caso" id="x_cerrar_caso" value="Cerrar Caso" onmouseover="javascript: this.style.cursor='pointer'" />
            </a>           
            
            
            
            <?php } ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php } ?>    


<?php if($x_tab_id == 2){ ?>
<table align='center' width='700' border='0' cellspacing='0' cellpadding='0'>
	<tr>
	  <td width="193">&nbsp;</td>
	  <td width="414"></td>
	  <td width="93"></td>
  </tr>
	<tr>
	  <td align="left" valign="top" class="txt_negro_medio">&nbsp;Detalle de Bit&aacute;cora:</td>
	  <td class="txt_datos_azul"><textarea name="x_bitacora" id="x_bitacora" cols="60" rows="10"><?php echo $x_bitacora; ?></textarea></td>
	  <td></td>
  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>
      <a href="imprimir_bitacora.php?key=<?php echo $x_crm_caso_id; ?>" target="_blank">
      <input type="button" value="Imprimir Bitacora" name="x_imprimir_bitacora" />
      </a>
      </td>
	  <td></td>
  </tr>
</table>
<?php } ?>    




<?php if($x_tab_id == 3){ ?>

<?php
	$sSql = "SELECT * FROM crm_tarea join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id ";
	$sDbWhere .= "(crm_tarea.crm_caso_id = $x_crm_caso_id) AND ";			
	if($_SESSION["crm_UserRolID"] != 1){		
			if($_SESSION["crm_UserRolID"] == 3 || $_SESSION["crm_UserRolID"] == 7){
				
				if($_SESSION["crm_UserID"] == 18){
		$sDbWhere .= "(crm_tarea.destino = ".$_SESSION["crm_UserID"].") AND ";	
		}else if ($_SESSION["crm_UserID"] == 6682 ){
			// si es marifer que le muestre las de colima... todas....				
$x_listado_creditos_gestor = "";
$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id in (16, 4812) ";
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
		//$sDbWhere .= "((crm_caso.credito_id in ($x_listado_creditos_gestor)) || (crm_tarea.destino  in (16, 4812) )   )AND ";		
			
			}else{
		//GESTORES DE COBRANZA
//mostramos los los casos que pertenecen a cada gestor
$x_listado_creditos_gestor = "";
$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$_SESSION["crm_UserID"]." ";
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 
		
#$sDbWhere .= "(crm_caso.credito_id in ($x_listado_creditos_gestor)) AND ";		
//$sDbWhere .= "((crm_caso.credito_id in ($x_listado_creditos_gestor)) || (crm_tarea.destino = ".$_SESSION["crm_UserID"].")   ) AND ";
			
		}
		}else{
			
			if($_SESSION["crm_UserRolID"] != 12){				
			//$sDbWhere .= "(crm_tarea.destino = ".$_SESSION["crm_UserID"].") AND ";	
			}
				
			}
		
			
	}
	$sDbWhere .= "(crm_tarea.crm_tarea_status_id in (1,2)) AND ";			
/* TEMPORAL PARA PRUEBAS	
	$sDbWhere .= "(crm_tarea.fecha_ejecuta <= '".ConvertDateToMysqlFormat($currdate)."') AND ";	
*/	
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND	
	$sSql .= " WHERE ".$sDbWhere." order by crm_tarea_id ";


	$rswrktar = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rswrktar) > 0) {	
?>	
<p align="left" class="txt_negro_medio">&nbsp;&nbsp;De clic sobre el n&uacute;mero de tarea para ver su detalle.</p>
        <table width="100%" cellpadding="1" cellspacing="1" align="center" style=" padding-left:10px" >
<tr bgcolor="#DEEBEF">
            <td width="50" align="left" valign="middle" class="txt_negro_medio">No</td>          
			<td width="113" align="left" valign="middle" class="txt_negro_medio">Estado</td>
<td width="113" align="left" valign="middle" class="txt_negro_medio">Prioridad</td>
            <td width="123" align="left" valign="middle" class="txt_negro_medio">Tipo</td>
            <td width="152" align="left" valign="middle" class="txt_negro_medio">Asunto</td>
            <td width="108" align="left" valign="middle" class="txt_negro_medio">Vence</td>
            <td width="128" align="left" valign="middle" class="txt_negro_medio">Asignada a</td>
          </tr>        
<?php	
	
		while ($rowtar = @phpmkr_fetch_array($rswrktar)) {
			$sKey = $rowtar["crm_caso_id"];
			$x_crm_tarea_id = $rowtar["crm_tarea_id"];
			$x_crm_tarea_tipo_id = $rowtar["crm_tarea_tipo_id"];
			$x_crm_tarea_prioridad_id = $rowtar["crm_tarea_prioridad_id"];
			$x_fecha_registro = $rowtar["fecha_registro"];
			$x_hora_registro = $rowtar["hora_registro"];
			$x_fecha_ejecuta = $rowtar["fecha_ejecuta"];
			$x_hora_ejecuta = $rowtar["hora_ejecuta"];
			$x_fecha_recordatorio = $rowtar["fecha_recordatorio"];
			$x_hora_recordatorio = $rowtar["hora_recordatorio"];
			$x_origen_rol = $rowtar["origen_rol"];
			$x_destino_rol = $rowtar["destino_rol"];
			$x_origen = $rowtar["origen"];
			$x_destino = $rowtar["destino"];
			$x_observaciones = $rowtar["observaciones"];
			$x_crm_tarea_status_id = $rowtar["crm_tarea_status_id"];
			$x_fecha_status = $rowtar["fecha_status"];			
			$x_asunto = $rowtar["asunto"];
			$x_descripcion = $rowtar["descripcion"];


			$sSqlWrk = "SELECT *  FROM `crm_tarea_status`";
			$sTmp = $x_crm_tarea_status_id;
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (`crm_tarea_status_id` = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$x_tarea_status_desc = $rowwrk["descripcion"];
			}
			@phpmkr_free_result($rswrk);


			$sSqlWrk = "SELECT *  FROM `crm_tarea_tipo`";
			$sTmp = $x_crm_tarea_tipo_id;
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (`crm_tarea_tipo_id` = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$x_tarea_tipo_desc = $rowwrk["descripcion"];
			}
			@phpmkr_free_result($rswrk);


			$sSqlWrk = "SELECT *  FROM `crm_tarea_prioridad`";
			$sTmp = $x_crm_tarea_prioridad_id;
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (`crm_tarea_prioridad_id` = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$x_tarea_prioridad_desc = $rowwrk["descripcion"];
			}
			@phpmkr_free_result($rswrk);

			$sSqlWrk = "SELECT *  FROM `usuario`";
			$sTmp = $x_origen;
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$x_origen = htmlentities($rowwrk["nombre"]);
			}
			@phpmkr_free_result($rswrk);



			$sSqlWrk = "SELECT *  FROM `usuario`";
			$sTmp = $x_destino;
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$x_destino = htmlentities($rowwrk["nombre"]);
			}
			@phpmkr_free_result($rswrk);


/*
<a href="crm_tareaedit.php?key=<?php echo $x_crm_tarea_id; ?>">
    <input type="button" name="x_nueva" id="x_nueva" value="Modificar" onmouseover="javascript: this.style.cursor='pointer'" />
    </a> 



		<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio' align='center'><a href='javascript: tareaview($x_crm_tarea_id);' title='Ver Detalle'>$x_crm_tarea_id</a></td>			  
*/
			echo "
			  <tr>
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio' align='center'><a href=\"crm_tareaedit_v1.php?key=$x_crm_tarea_id\">
				$x_crm_tarea_id</a></td>			  
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio'>$x_tarea_status_desc</td>
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio'>$x_tarea_prioridad_desc</td>				
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio'>$x_tarea_tipo_desc</td>
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio' >$x_descripcion</td>
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio' >".FormatDateTime($x_fecha_ejecuta,7)."</td>
				<td style=\"border-bottom: solid 1px #CCC\" class='txt_negro_medio'>$x_destino</td>
			  </tr>";
		}
		
		echo "<tr><td colspan=\"7\">&nbsp;<td></tr>";
		echo "<tr><td colspan=\"7\">&nbsp;<td></tr>";
		echo "</table>";
		phpmkr_free_result($rswrktar);
	}

?>
<?php } ?>


<?php if($x_tab_id == 4){ ?>
<?php
//Validar que no haya taeas anteriores pendentes
$sSql = "SELECT count(*) as tareas_pend FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id and crm_tarea_id < $x_crm_tarea_id and crm_tarea_status_id < 3 ";	
$rstp = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$rowtp = phpmkr_fetch_array($rstp);

$x_tareas_pendientes = $rowtp["tareas_pend"];
phpmkr_free_result($rstp);									
?>
<?php if($x_crm_caso_tipo_id  < 3) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td width="15%" class="txt_negro_medio"> Tipo de Tarea</td>
        <td width="3%" align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td width="23%" class="txt_datos_azul">&nbsp;<?php echo $x_tarea_tipo_desc; ?></td>
        <td width="2%">&nbsp;</td>
        <td width="14%" class="txt_negro_medio">Prioridad</td>
        <td width="3%" align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td width="40%" class="txt_datos_azul"><?php echo $x_tarea_prioridad_desc; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Estado:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_tarea_status_desc; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Asunto:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_asunto; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Descripci&oacute;n:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_descripcion; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Vence:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_ejecuta,7) . "  " . $x_hora_ejecuta; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Responsable</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_destino; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Observaciones:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td rowspan="2" align="left" valign="top" class="txt_datos_azul"><?php echo $x_observaciones; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Solicitud:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">
        <?php if($x_tareas_pendientes == 0){ ?>
        <a href="php_solicitudedit.php?solicitud_id=<?=$x_solicitud_id;?>&win=2">
		<?php echo htmlentities($x_folio); ?>
        </a>
        <?php } ?>        
        </td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Cr&eacute;dito:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">
        <?php if($x_tareas_pendientes == 0){ ?>
        <a href="credito_view.php?key=<?=$x_credito_id;?>&key2=<?=$x_crm_caso_id?>">
		<?php echo htmlentities($x_credito_num); ?>
        </a>
        <?php } ?>        
		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td colspan="5" align="left" valign="top" class="txt_datos_azul">
        <?php
		
		
		if ($x_tareas_pendientes == 0) {		
		?>
        
        <?php if($x_crm_tarea_status_id < 3){ ?>
        <?php if(($_SESSION["crm_UserRolID"] == 1) || ($_SESSION["crm_UserID"] == $x_destino_id)){ ?>        
        
		<?php if($x_crm_tarea_tipo_id  == 1) { ?>        
        De clic en el numero de solicitud, para ingresar y completar el checklist.
    	<?php }else{ ?>    
        
<a href="crm_tareaedit_v1.php?key=<?php echo $x_crm_tarea_id; ?>">
    <input type="button" name="x_nueva" id="x_nueva" value="Modificar" onmouseover="javascript: this.style.cursor='pointer'" />
    </a>        

    	<?php } ?>            
    
    	<?php } ?>    
    	<?php } ?>
    	<?php } ?>        
        
        
        </td>
      </tr>
    </table>

<?php }else{ //CARTERA VENCIDA?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td width="15%" class="txt_negro_medio"> Tipo de Tarea</td>
        <td width="3%" align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td width="23%" class="txt_datos_azul">&nbsp;<?php echo $x_tarea_tipo_desc; ?></td>
        <td width="2%">&nbsp;</td>
        <td width="14%" class="txt_negro_medio">Prioridad</td>
        <td width="3%" align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td width="40%" class="txt_datos_azul"><?php echo $x_tarea_prioridad_desc; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Estado:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_tarea_status_desc; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Asunto:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_asunto; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Descripci&oacute;n:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_descripcion; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Vence:</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_ejecuta,7) . "  " . $x_hora_ejecuta; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Responsable</td>
        <td align="center" valign="middle" class="table_frm_required">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_destino; ?></td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Observaciones:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td rowspan="2" align="left" valign="top" class="txt_datos_azul"><?php echo $x_observaciones; ?></td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">Solicitud:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">
        <?php if($x_tareas_pendientes == 0){ ?>        
        <a href="php_solicitudedit.php?solicitud_id=<?=$x_solicitud_id;?>&win=2">
		<?php echo htmlentities($x_folio); ?>
        </a>
        <?php } ?>
        </td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Cr&eacute;dito:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">
        <?php if($x_tareas_pendientes == 0){ ?>        
        <a href="credito_view.php?key=<?=$x_credito_id;?>&key2=<?=$x_crm_caso_id?>">
		<?php echo htmlentities($x_credito_num); ?>
        </a>
        <?php } ?>
		</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">Promotor</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul"><?php echo $x_promotor; ?></td>
      </tr>
      <tr>
        <td colspan="7" align="left" valign="top" class="txt_negro_medio">

<?php if($x_crm_tarea_tipo_id == 5){ //aqui promesas de pago?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
<tr>
  <td width="15%" class="txt_negro_medio">Promesa de Pago</td>
  <td width="3%">&nbsp;</td>
  <td width="23%" class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_pp,7) ; ?></td>
  <td width="2%">&nbsp;</td>
  <td width="14%" class="txt_negro_medio">pp por:</td>
    <td width="3%">&nbsp;
    </td>
    <td width="40%" class="txt_datos_azul">
      <?php
	switch ($x_pp_por)
	{
		case 1: // titular
		echo "Titular";
		break;
		case 2: // titular
		echo "Aval";
		break;
		case 1: // titular
		echo "Proxim Venc.";
		break;
	}
	?>
    </td>
</tr>
<tr>
  <td class="txt_negro_medio">Tel Tituliar</td>
  <td>&nbsp;</td>
  <td class="txt_datos_azul">
  <?php
  	if($x_tel_titular == 1){
		echo "SI";	
	}
  	if($x_tel_titular == 2){	
		echo "NO";		
	}
  ?>
  </td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Tel Aval</td>
  <td>&nbsp;</td>
  <td class="txt_datos_azul">
  <?php
  	if($x_tel_aval == 1){
		echo "SI";	
	}
  	if($x_tel_aval == 2){
		echo "NO";		
	}
  ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>

<?php } ?>


<?php if($x_crm_tarea_tipo_id > 7 && $x_crm_tarea_tipo_id < 12){ //cartas ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
<tr>
  <td width="15%" class="txt_negro_medio">Fecha de Entrega</td>
  <td width="3%">&nbsp;</td>
  <td width="23%" class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_entrega,7) ; ?></td>
  <td width="2%">&nbsp;</td>
  <td width="14%" class="txt_negro_medio"></td>
  <td width="3%">&nbsp;
    </td>
  <td width="40%" class="txt_datos_azul">&nbsp;
    </td>
</tr>
</table>

<?php } ?>


<?php if($x_crm_tarea_tipo_id == 7){ //confirmacion de pago ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
<tr>
  <td width="15%" class="txt_negro_medio">Pago completo</td>
  <td width="3%">&nbsp;</td>
  <td width="23%" class="txt_datos_azul"><?php
	switch ($x_pago_completo)
	{
		case 1: // titular
		echo "SI";
		break;
		case 2: // titular
		echo "Incompleto";
		break;
		case 1: // titular
		echo "No Pago";
		break;
	}
	?></td>
  <td width="2%">&nbsp;</td>
  <td width="14%" class="txt_negro_medio"></td>
  <td width="3%">&nbsp;
    </td>
  <td width="40%" class="txt_datos_azul">&nbsp;
    </td>
</tr>
</table>

<?php } ?>


<?php if($x_crm_tarea_tipo_id == 13){ //confirmacion de pago ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
<tr>
  <td width="15%" class="txt_negro_medio">Resultado Demanda</td>
  <td width="3%">&nbsp;</td>
  <td width="23%" class="txt_datos_azul"><?php
  	if($x_resultado_demanda == 1){
		echo "SI";	
	}
  	if($x_resultado_demanda == 2){	
		echo "NO";		
	}
  ?></td>
  <td width="2%">&nbsp;</td>
  <td width="14%" class="txt_negro_medio"></td>
  <td width="3%">&nbsp;
    </td>
  <td width="40%" class="txt_datos_azul">&nbsp;
    </td>
</tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding-left:10px">
<tr>
  <td width="15%" class="txt_negro_medio">Incobrable</td>
  <td width="3%">&nbsp;</td>
  <td width="29%" class="txt_datos_azul">
  <?php
  	if($x_incobrable == 1){
		echo "Incobrable";		
	}
  ?>
  </td>
  <td width="1%">&nbsp;</td>
  <td width="15%" class="txt_negro_medio"></td>
  <td width="3%">&nbsp;
    </td>
  <td width="34%" class="txt_datos_azul">&nbsp;
    </td>
</tr>
</table>

<?php } ?>



        
        </td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td class="txt_datos_azul">&nbsp;</td>
      </tr>
      <tr>
        <td class="txt_negro_medio">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td colspan="5" align="left" valign="top" class="txt_datos_azul">
        <?php
		
		if ($x_tareas_pendientes == 0) {		
		?>
        
        <?php if($x_crm_tarea_status_id < 3){ ?>
        <?php if(($_SESSION["crm_UserRolID"] == 1) || ($_SESSION["crm_UserID"] == $x_destino_id)){ ?>        
        
<a href="crm_tareaedit_v1.php?key=<?php echo $x_crm_tarea_id; ?>">
    <input type="button" name="x_nueva" id="x_nueva" value="Modificar" onmouseover="javascript: this.style.cursor='pointer'" />
    </a>        

    	<?php } ?>    
    	<?php } ?>

    	<?php } ?>        
        </td>
      </tr>
    </table>










<?php } ?>
<?php } ?>

<?php if($x_tab_id == 5){ ?>
<?php } ?>

<?php if($x_tab_id == 6){ ?>
<?php } ?>


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
	global $x_crm_caso_id;
	global $x_tab_id;	
	global $x_crm_tarea_id;		

//CLIENTE
if($x_tab_id == 1){

	$sSql = "SELECT * FROM crm_caso WHERE crm_caso_id = $x_crm_caso_id";	
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row2 = phpmkr_fetch_array($rs2);	
	$GLOBALS["x_crm_caso_tipo_id"] = $row2["crm_caso_tipo_id"];
	$GLOBALS["x_crm_caso_status_id"] = $row2["crm_caso_status_id"];
	$GLOBALS["x_crm_caso_prioridad_id"] = $row2["crm_caso_prioridad_id"];
	$GLOBALS["x_cuenta_id"] = $row2["cuenta_id"];
	$GLOBALS["x_fecha_registro"] = $row2["fecha_registro"];
	$GLOBALS["x_origen"] = $row2["origen"];
	$GLOBALS["x_responsable"] = $row2["responsable"];
	$GLOBALS["x_bitacora"] = $row2["bitacora"];
	$GLOBALS["x_fecha_status"] = $row2["fecha_status"];
	$GLOBALS["x_solicitud_id"] = $row2["solicitud_id"];
	$GLOBALS["x_credito_id"] = $row2["credito_id"];	

	$sSqlWrk = "SELECT descripcion  FROM crm_caso_tipo";
	$sTmp = $GLOBALS["x_crm_caso_tipo_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_caso_tipo_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_tipo_caso_desc"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);	
	$sSqlWrk = "SELECT *  FROM `crm_caso_status`";
	$sTmp = $GLOBALS["x_crm_caso_status_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_caso_status_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_caso_status"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `crm_caso_prioridad`";
	$sTmp = $GLOBALS["x_crm_caso_prioridad_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_caso_prioridad_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_caso_prioridad"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `cliente`";
	$sTmp = $GLOBALS["x_cuenta_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`cliente_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_cuenta"] = htmlentities($rowwrk["nombre_completo"]) . " " . htmlentities($rowwrk["apellido_paterno"]) . " " . htmlentities($rowwrk["apellido_materno"]);
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `usuario`";
	$sTmp = $GLOBALS["x_origen"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_origen_caso"] = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `usuario`";
	$sTmp = $GLOBALS["x_responsable"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_destino_caso"] = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);




	if(!empty($GLOBALS["x_solicitud_id"])){
		$sSqlWrk = "SELECT folio FROM solicitud";
		$sTmp = $GLOBALS["x_solicitud_id"];
		$sTmp = addslashes($sTmp);
		$sSqlWrk .= " WHERE (solicitud_id = " . $sTmp . ")";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$GLOBALS["x_credito_num"] = "";
			$GLOBALS["x_folio"] = $rowwrk["folio"];			
		}
		@phpmkr_free_result($rswrk);
	}else{
		if(!empty($GLOBALS["x_credito_id"])){
			$sSqlWrk = "SELECT credito_num FROM credito";
			$sTmp = $GLOBALS["x_credito_id"];
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (credito_id = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$GLOBALS["x_credito_num"] = $rowwrk["credito_num"];
				$GLOBALS["x_folio"] = "";			
			}
			@phpmkr_free_result($rswrk);
		}
	}



}


//DIR PART
if($x_tab_id == 2){

	$sSql = "SELECT * FROM crm_caso WHERE crm_caso_id = $x_crm_caso_id";	
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row2 = phpmkr_fetch_array($rs2);
	
	if(!empty($row2["credito_id"])){

		$sSqlWrk = "
		SELECT 
			comentario_int
		FROM 
			credito_comment
		WHERE 
			credito_id = ".$row2["credito_id"]."
		LIMIT 1
		";

	}else{

		$sSqlWrk = "
		SELECT 
			comentario_int
		FROM 
			solicitud_comment
		WHERE 
			solicitud_id = ".$row2["solicitud_id"]."
		LIMIT 1
		";

	}

	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$GLOBALS["x_bitacora"] = $datawrk["comentario_int"];
	@phpmkr_free_result($rswrk);

/*
	$sSql = "SELECT bitacora FROM crm_caso WHERE crm_caso_id = $x_crm_caso_id";	
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row2 = phpmkr_fetch_array($rs2);
	
	$GLOBALS["x_bitacora"] = $row2["bitacora"];

*/


}

// DIR NEG
if($x_tab_id == 3){
}


//SOLICITUD
if($x_tab_id == 4){


	$sSql = "SELECT * FROM crm_tarea where crm_tarea_id = $x_crm_caso_id";
	$rswrktar = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowtar = phpmkr_fetch_array($rswrktar);
	
	$GLOBALS["x_crm_caso_id"] = $rowtar["crm_caso_id"];
	$GLOBALS["x_crm_tarea_id"] = $rowtar["crm_tarea_id"];	
	$GLOBALS["x_crm_tarea_tipo_id"] = $rowtar["crm_tarea_tipo_id"];
	$GLOBALS["x_crm_tarea_prioridad_id"] = $rowtar["crm_tarea_prioridad_id"];
	$GLOBALS["x_fecha_registro"] = $rowtar["fecha_registro"];
	$GLOBALS["x_hora_registro"] = $rowtar["hora_registro"];
	$GLOBALS["x_fecha_ejecuta"] = $rowtar["fecha_ejecuta"];
	$GLOBALS["x_hora_ejecuta"] = $rowtar["hora_ejecuta"];
	$GLOBALS["x_fecha_recordatorio"] = $rowtar["fecha_recordatorio"];
	$GLOBALS["x_hora_recordatorio"] = $rowtar["hora_recordatorio"];
	$GLOBALS["x_origen_rol"] = $rowtar["origen_rol"];
	$GLOBALS["x_destino_rol"] = $rowtar["destino_rol"];
	$GLOBALS["x_origen"] = $rowtar["origen"];
	$GLOBALS["x_destino"] = $rowtar["destino"];	
	$GLOBALS["x_observaciones"] = $rowtar["observaciones"];
	$GLOBALS["x_crm_tarea_status_id"] = $rowtar["crm_tarea_status_id"];	
	$GLOBALS["x_fecha_status"] = $rowtar["fecha_status"];
	$GLOBALS["x_asunto"] = $rowtar["asunto"];
	$GLOBALS["x_descripcion"] = $rowtar["descripcion"];
	@phpmkr_free_result($rswrktar);


	$sSql = "SELECT * FROM crm_caso WHERE crm_caso_id = ".$GLOBALS["x_crm_caso_id"];	
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row2 = phpmkr_fetch_array($rs2);
	
	$GLOBALS["x_crm_caso_tipo_id"] = $row2["crm_caso_tipo_id"];
	$GLOBALS["x_crm_caso_status_id"] = $row2["crm_caso_status_id"];
	$GLOBALS["x_crm_caso_prioridad_id"] = $row2["crm_caso_prioridad_id"];
	$GLOBALS["x_cuenta_id"] = $row2["cuenta_id"];
	$GLOBALS["x_fecha_registro"] = $row2["fecha_registro"];
	$GLOBALS["x_origen"] = $row2["origen"];
	$GLOBALS["x_responsable"] = $row2["responsable"];
	$GLOBALS["x_bitacora"] = $row2["bitacora"];
	$GLOBALS["x_fecha_status"] = $row2["fecha_status"];
	$GLOBALS["x_solicitud_id"] = $row2["solicitud_id"];
	$GLOBALS["x_credito_id"] = $row2["credito_id"];	


	$sSqlWrk = "SELECT *  FROM `crm_tarea_status`";
	$sTmp = $GLOBALS["x_crm_tarea_status_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_tarea_status_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_tarea_status_desc"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `crm_tarea_tipo`";
	$sTmp = $GLOBALS["x_crm_tarea_tipo_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_tarea_tipo_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_tarea_tipo_desc"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);


	$sSqlWrk = "SELECT *  FROM `crm_tarea_prioridad`";
	$sTmp = $GLOBALS["x_crm_tarea_prioridad_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`crm_tarea_prioridad_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_tarea_prioridad_desc"] = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *  FROM `usuario`";
	$sTmp = $GLOBALS["x_origen"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_origen"] = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);


	$GLOBALS["x_destino_id"] = $GLOBALS["x_destino"];

	$sSqlWrk = "SELECT *  FROM `usuario`";
	$sTmp = $GLOBALS["x_destino"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`usuario_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_destino"] = htmlentities($rowwrk["nombre"]);
	}
	@phpmkr_free_result($rswrk);



	$sSqlWrk = "SELECT solicitud_id, credito_id  FROM crm_caso";
	$sTmp = $GLOBALS["x_crm_caso_id"];
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (crm_caso_id = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$GLOBALS["x_solicitud_id"] = $rowwrk["solicitud_id"];
		$GLOBALS["x_credito_id"] = $rowwrk["credito_id"];		
	}
	@phpmkr_free_result($rswrk);



	if(!empty($GLOBALS["x_solicitud_id"])){
		$sSqlWrk = "SELECT folio FROM solicitud";
		$sTmp = $GLOBALS["x_solicitud_id"];
		$sTmp = addslashes($sTmp);
		$sSqlWrk .= " WHERE (solicitud_id = " . $sTmp . ")";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$GLOBALS["x_credito_num"] = "";
			$GLOBALS["x_folio"] = $rowwrk["folio"];			
		}
		@phpmkr_free_result($rswrk);
	}else{
		if(!empty($GLOBALS["x_credito_id"])){
			$sSqlWrk = "SELECT credito_num FROM credito";
			$sTmp = $GLOBALS["x_credito_id"];
			$sTmp = addslashes($sTmp);
			$sSqlWrk .= " WHERE (credito_id = " . $sTmp . ")";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
				$GLOBALS["x_credito_num"] = $rowwrk["credito_num"];
				$GLOBALS["x_folio"] = "";			
			}
			@phpmkr_free_result($rswrk);
		}
	}



		if($GLOBALS["x_crm_caso_tipo_id"] == 3){

			$sSqlWrk = "
			SELECT 
				*
			FROM 
				crm_tarea_cv
			WHERE 
				crm_tarea_id = ".$GLOBALS["x_crm_tarea_id"]."
			";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
			$datawrk = phpmkr_fetch_array($rswrk);
			$GLOBALS["x_fecha_pp"] = $datawrk["promesa_pago"];											
			$GLOBALS["x_pp_por"] = $datawrk["pp_por"];														
			$GLOBALS["x_tel_titular"] = $datawrk["tel_titular"];
			$GLOBALS["x_tel_aval"] = $datawrk["tel_aval"];			
			$GLOBALS["x_fecha_entrega"] = $datawrk["fecha_entrega"];			
			$GLOBALS["x_pago_completo"] = $datawrk["pago_completo"];			
			$GLOBALS["x_resultado_demanda"] = $datawrk["resultado_demanda"];						
			$GLOBALS["x_incobrable"] = $datawrk["incobrable"];									

			phpmkr_free_result($rswrk);


		
		}

		$sSqlWrk = "SELECT promotor.nombre_completo FROM promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$GLOBALS["x_promotor"] = $rowwrk["nombre_completo"];
		@phpmkr_free_result($rswrk);





}

//CREDITO
if($x_tab_id == 6){

}




phpmkr_free_result($rs);
phpmkr_free_result($rs2);	
phpmkr_free_result($rs3);		
phpmkr_free_result($rs4);			
phpmkr_free_result($rs5);				
phpmkr_free_result($rs6);					
phpmkr_free_result($rs6_2);						
phpmkr_free_result($rs7);						
phpmkr_free_result($rs8);
phpmkr_free_result($rs9);								
phpmkr_free_result($rs10);									

return $bLoadData;
}
?>

