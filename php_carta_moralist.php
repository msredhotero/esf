<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
$ewCurSec = 0; // Initialise

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}

$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=cartasmora.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

if($_POST["x_pagos_vencidos"]){
	$_SESSION["x_pagos_vencidos"] = $_POST["x_pagos_vencidos"];
}else{
	if(empty($_SESSION["x_pagos_vencidos"])){
		$_SESSION["x_pagos_vencidos"] = 2;
	}
}

// seleccionamos los datos de la scursal por si el rol con el que se logue ael usuario es de un responsable de sucursal.
 if ($_SESSION["php_project_esf_status_UserRolID"] == 12){
	 // el usuario es un encargado de suscursal
	 // se agrega una condicion al where para que en listado solo se vean los credito de la sucursal.
	 
	 // se selecciona a todos los promotores que pertenecen a la sucursal del  encargado
	 $sqlEncargadoSucursal = "SELECT * FROM encargado_sucursal WHERE encargado_sucursal_id = ". $_SESSION["php_project_esf_status_ResponsableID"]."";
	// $rsEncargadoSucursal = phpmkr_query($sqlEncargadoSucursal, $conn) or die ("Error al seleccionar los datos del encrgado de sucursal". phpmkr_error()."sql:".$sqlEncargadoSucursal);
	 //$rowEncargadoSucursal = phpmkr_fetch_array($rsEncargadoSucursal);
	 $x_sucursal_id = $rowEncargadoSucursal["sucursal_id"];
	 
		$x_suc_id = $_SESSION["php_project_esf_SucursalID"];
		
		
		if($_SESSION["php_project_esf_status_UserRolID"] == 12){
						echo "entro en encargado";
					
						$sSqls = "select responsable_sucursal_id, sucursal_id from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						echo $sSql;
						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_ResponsableID"] = $row2["responsable_sucursal_id"];	
							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						
							$bValidPwd = true;
						}
		}
 
 // seleccionamos todos los promotores que correspondan a esa sucursal
	 $sqlListaPromotores = "SELECT * FROM promotor  WHERE  sucursal_id = ". $_SESSION["php_project_esf_SucursalID"]."";
	 echo   $sqlListaPromotores."<br>";
	 $rsPromotores = phpmkr_query($sqlListaPromotores, $conn) or die ("Error al selccioar los promotores de la sucursal". phpmkr_error()."sql:".$sqlListaPromotores);
	 while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
		 $x_promotores .= $rowPromotores["promotor_id"].", ";	 
		 }
		 $x_promotores = trim($x_promotores, ", ");
		 if(empty($filter["x_promo_srch"]))
		 $sDbWhereEncargado = " (solicitud.promotor_id IN ($x_promotores)  ) ";
 
 
 }

if(!empty($sDbWhereEncargado)){
	$sDbWhereEncargado = "AND ".$sDbWhereEncargado ."";
	}
// Build SQL
$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id = 3) $sDbWhereEncargado ";

	if($_POST["x_promotor_id_filtro"]){
		$_SESSION["x_promotor_id_filtro"] = $_POST["x_promotor_id_filtro"];
	}

	if($_SESSION["php_project_esf_status_PromotorID"] > 0 ){
		$_SESSION["x_promotor_id_filtro"] = $_SESSION["php_project_esf_status_PromotorID"];	
	}

	if($_SESSION["x_promotor_id_filtro"] > 0){
	$sSql .= " AND solicitud.promotor_id = ".$_SESSION["x_promotor_id_filtro"];
	}

$sSql .= " group by vencimiento.credito_id having count(*) = ".$_SESSION["x_pagos_vencidos"]." order by credito.credito_num+0";




//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;

	if (validada && EW_this.x_dias_ini && !EW_hasValue(EW_this.x_dias_ini, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "Indique el numero de dias de inicio."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_ini && !EW_checkinteger(EW_this.x_dias_ini.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "El numero de dias de inicio es incorrecto, por favor verifiquelo."))
			validada = false;
	}

	if (validada && EW_this.x_dias_fin && !EW_hasValue(EW_this.x_dias_fin, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "Indique el numero de dias de fin."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_fin && !EW_checkinteger(EW_this.x_dias_fin.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "El numero de dias de fin es incorrecto, por favor verifiquelo."))
			validada = false;
	}


	if(validada == true){
		EW_this.submit();
	}
}
//-->
</script>

<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFFF'; // row color
var rowaltcolor = '#F5F5F5'; // row alternate color
var rowmovercolor = '#FFCCFF'; // row mouse over color
var rowselectedcolor = '#CCFFFF'; // row selected color
var roweditcolor = '#FFFF99'; // row edit color

//-->
</script>
<?php
// Set up Record Set


$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
?>
<p><span class="phpmaker">
CARTAS DE MORA (Pagos Vencidos: <?php echo $_SESSION["x_pagos_vencidos"]; ?> )
<br /><br />
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_carta_moralist.php?export=excel&x_pagos_vencidos=<?php echo $x_pagos_vencidos; ?>">Exportar a Excel</a>
<?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_carta_moralist.php" name="filtro" id="filtro" method="post">
<table class="phpmaker">
	<tr>
		<td><span class="phpmaker">
		Promotor:
		</span>
		</td>
		<td><span class="phpmaker">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id_filtro\">";
		$x_estado_civil_idList .= "<option value='0'>Todos</option>";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12){
		$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where sucursal_id = ".$_SESSION["php_project_esf_SucursalID"]."";
		$x_estado_civil_idList .= "<option value='0'>Todos</option>";
		
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";
			
				
			//$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";		
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == @$_SESSION["x_promotor_id_filtro"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>
		</span>
		</td>
		<td><span class="phpmaker">
		Pagos Vencidos:
		</span>
		</td>
		<td><span>
        <select name="x_pagos_vencidos" >
        <option value="2">2</option>
        <option value="4">4</option>        
        <option value="4">6</option>        
        <option value="4">8</option>                        
        </select>
		</span>		
		</td>
		<td><span class="phpmaker">
		<input type="button"  value="Filtrar" name="filtro" onclick="filtrar();"  />
		</span>
		</td>		
	</tr>
</table>
</form>
<?php } ?>

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td width="93" valign="top"><span>
Credito Num.
		</span></td>
		<td width="217" valign="top"><span>
Promotor
		</span></td>
		<td width="228" valign="top"><span>        
Cliente
</span></td>		
		<td width="89" valign="top"><span>
Carta Titular
		</span></td>
		<td width="97" valign="top"><span>
Carta Aval
		</span></td>		
		<td width="114" valign="top"><span>
Carta Asociados
		</span></td>
	</tr>
<?php

while ($row = @phpmkr_fetch_array($rs)){
	$nRecCount = $nRecCount + 1;
	$nRecActual++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 1) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

	$x_credito_id = $row["credito_id"];


	$sSqlWrk = "select credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];	
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];	
	$x_solicitud_id = $rowwrk["solicitud_id"];	
	@phpmkr_free_result($rswrk);
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<td align="right"><span>
<?php echo $x_credito_num; ?>
</span></td>
		<td align="left"><span>
<?php 
		$sSqlWrk = "SELECT promotor.nombre_completo FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id Where credito.credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor = $rowwrk["nombre_completo"];								
		}else{
			$x_promotor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_promotor; 

?>
</span></td>
		<td align="left"><span>
<?php 
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
		}else{
			$sSqlWrk = "SELECT solicitud.grupo_nombre as cliente_nombre FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id Where credito.credito_id = $x_credito_id ";
		}

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];								
		}else{
			$x_cliente = "";										
		}
		@phpmkr_free_result($rswrk);
		echo $x_cliente;
?>
</span></td>
		<td align="center"><span>
<a href="php_carta_mora.php?key1=<?php echo $_SESSION["x_pagos_vencidos"]; ?>&key2=<?php echo $x_credito_id; ?>&key3=1" target="_blank">Imprimir</a>
</span></td>
		<td align="center"><span>
<a href="php_carta_mora.php?key1=<?php echo $_SESSION["x_pagos_vencidos"]; ?>&key2=<?php echo $x_credito_id; ?>&key3=2" target="_blank">Imprimir</a>
</span></td>
		<td align="center"><span>
<?php if($x_credito_tipo_id == 2){  ?>
<a href="php_carta_mora_asoc.php?key1=<?php echo $_SESSION["x_pagos_vencidos"]; ?>&key2=<?php echo $x_credito_id; ?>&key3=3" target="_blank">Imprimir</a>
<?php } ?>
</span></td>   
	</tr>
<?php
}
?>
</table>
<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>

