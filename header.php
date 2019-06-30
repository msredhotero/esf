<?php 

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>e - SF &gt;  FINANCIERA CREA</title>
<?php if (@$sExport == "") { ?>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<? } ?>
<script type="text/javascript" language="JavaScript1.2" src="menu/stlib.js"></script>
</head>
<body>
<?php if (@$sExport == "") { ?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
	<td align="center" valign="middle" style="border-bottom: solid 1px #CC0000">
	<img src="images/logo.gif" width="115" height="93">
	</td>
	<td align="left" valign="middle" style="border-bottom: solid 1px #CC0000">
	<span class="phpmaker">&nbsp;&nbsp;<font size="2"><b>Sistema Financiero Web </b></font></span>
	</td>	
</tr>
<tr>
	<!-- left column -->
	<td width="20%" height="100%" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
<?php if (@$_SESSION["php_project_esf_status"] == "login") { ?>
			<tr><td align="left"><span class="phpmaker">En sesion:</span></td></tr>
			<tr><td align="left" style="border-bottom: solid 1px #CC0000"><span class="phpmaker"><b><?php echo $_SESSION["php_project_esf_status_UserName"]; ?></b></span></td></tr>
			<tr><td align="center" style="border-bottom: solid 1px #CC0000"><span class="phpmaker"><b>Fecha: <?php echo $currdate; ?></b></span></td></tr>
			<tr><td><span class="phpmaker">


<!--- MENU -->
		<?php
		$x_menu = "";		
		switch ($_SESSION["php_project_esf_status_UserRolID"])
		{
			case "1": // Administrador
				$x_menu = "menu_admin.js";					
				break;
			case "2": // Coord. Credito
				$x_menu = "menu_coordcredito.js";					
				break;
			case "3": // Coord. Cobranza
				$x_menu = "menu_coordcobranza.js";					
				break;
			case "4": // Contabili interna
				$x_menu = "menu_contabilidad.js";					
				break;
			case "5": // Despacho Cobranza
				$x_menu = "menu_despcobranza.js";					
				break;
			case "6": // Desapacho Contable
				$x_menu = "menu_despcontable.js";					
				break;
			case "7": // Promotor
				$x_menu = "menu_promotores.js";					
				break;
			case "9": // Promotor
				$x_menu = "menu_admin.js";					
				break;		
				
			case "10": // Promotor
				$x_menu = "menu_externo.js";					
				break;		
								
			case "11": // Promotor
				$x_menu = "menu_pronafim.js";					
				break;		
				
			case "12": // Responsable de sucursal
				$x_menu = "menu_responsable_sucursal.js";					
				break;	
			case "13": // Rol juridico
				$x_menu = "menu_juridico.js";					
				break;								
			case "15": // Rol juridico
				$x_menu = "menu_cobranza_temprana.js";					
				break;		
				
			case "16": // Rol juridico
				$x_menu = "menu_gestores.js";					
				break;	
				
			case "17": // Rol juridico
				$x_menu = "menu_gerente_de_sucursal.js";					
				break;	
			case "18": // Rol juridico
				$x_menu = "menu_fp.js";					
				break;					
			case "20": // ofocial de cumplimeinto
				$x_menu = "menu_pld.js";					
				break;		
		}
		if($x_menu == ""){
			header("location: login.php");
		}
		?>
		<script type="text/javascript" language="JavaScript1.2" src="menu/<?php echo $x_menu; ?>"></script>
			</span></td></tr>
<?php } ?>
		</table>
	</td>
	<!-- right column -->
	<td width="80%" valign="top">
<?php } ?>
<?php } ?>