<?php session_start(); ?>
<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link href="../crm.css" rel="stylesheet" type="text/css" />
<link rel="STYLESHEET" type="text/css" href="tabs/dhtmlxtabbar.css">
<script  src="tabs/dhtmlxcommon.js"></script>
<script  src="tabs/dhtmlxtabbar.js"></script>

</head>
<body>


<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB,PORT);



$x_crm_caso_id = $_GET["key"];

// hacemos las busqueda de la ultima tarea y redirecinamos
$sSql = "SELECT * FROM crm_tarea join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id ";
$sSql .= " WHERE (crm_tarea.crm_caso_id = $x_crm_caso_id) ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
$RSTARES = phpmkr_query($sSql,$conn) or die ("Error al seleccionar las tareas ".phpmkr_error());
$rowTarea = phpmkr_fetch_array($RSTARES);
$x_trea_id = $rowTarea["crm_tarea_id"];
$x_status_ultima =  $rowTarea["crm_tarea_status_id"];
echo "la ultia tarea s ".$x_trea_id;

 phpmkr_db_close($conn);
			ob_end_clean();
			if($x_status_ultima == 1 || $x_status_ultima == 2){
			header("Location: crm_tareaedit_v1.php?key=$x_trea_id");
			}else{
				header("Location: casos_view_mensaje_no.php?key=$x_trea_id");
				}
			exit();
 
if(empty($x_crm_caso_id)){
	echo "No se ha espcificado el Numero de Caso.";
	exit();
}

?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td class="table_frm_tit">&nbsp;
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="7%" class="table_frm_supizq">&nbsp;</td>
    <td width="87%" align="right" class="table_frm_sup">&nbsp;</td>
    <td width="6%" class="table_frm_supder">&nbsp;</td>
  </tr>
  <tr>
    <td height="175" class="table_frm_izq">&nbsp;</td>
    <td align="left" valign="top" class="table_frm_centro">



<div id="b_tabbar" style="width:612; height:150;"></div>


<script>

			tabbar2=new dhtmlXTabBar("b_tabbar","top");
            tabbar2.setImagePath("tabs/imgs/");
			tabbar2.setStyle("modern");
			tabbar2.setHrefMode("ajax-html");
			tabbar2.setAlign("left"); //left,rigth,top,bottom
			tabbar2.setMargin("1");
			tabbar2.setOffset("20");
			//tabbar2.setSize("200px","300px",true)
			tabbar2.enableAutoSize(true,true);
//			tabbar2.enableAutoReSize(true);

            //tabbar2.loadXML("tabs1.xml");


			tabbar2.addTab("a1","Datos Generales","130px");
			tabbar2.addTab("a2","Bitacora","130px");			
			tabbar2.addTab("a3","Tareas","130px");
			tabbar2.addTab("a4","Detalle de Tarea","130px");

			tabbar2.disableTab("a4",true);


			tabbar2.setContentHref("a1","casos_view_det.php?key=<?=$x_crm_caso_id?>&key2=1");
			tabbar2.setContentHref("a2","casos_view_det.php?key=<?=$x_crm_caso_id?>&key2=2");
			tabbar2.setContentHref("a3","casos_view_det.php?key=<?=$x_crm_caso_id?>&key2=3");
/*			
			tabbar2.setContentHref("a4","cuentas_view_det.php?key=<?=$x_crm_caso_id?>&key2=4");
			
*/

			tabbar2.setTabActive("a3");


			function tareaview(tareaid){
				tabbar2.setLabel("a4",'Detalle de Tarea');					
				tabbar2.setContentHref("a4","casos_view_det.php?key="+tareaid+"&key2=4");				
				tabbar2.enableTab("a4");
				tabbar2.setTabActive("a4");				
			}


</script>



    &nbsp;</td>
    <td class='table_frm_der'>&nbsp;</td>
  </tr>
  <tr>
    <td class='table_frm_infizq'>&nbsp;</td>
    <td class='table_frm_inf'>&nbsp;</td>
    <td class='table_frm_infder'>&nbsp;</td>
  </tr>
</table>

</body>
</html>