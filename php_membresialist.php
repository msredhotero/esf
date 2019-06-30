<?php session_start(); ?>
<?php ob_start(); ?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

// Initialize common variables
$x_membresia_id = Null;
$x_fecha_registro = Null;
$x_monto = Null;
$x_status = Null;
$x_sucursal_id = Null;
$x_fecha_expiracion = Null;
$x_nombre = Null;
$x_apellido_paterno = Null;
$x_apellido_materno = Null;
$x_numero_cliente = Null;
$x_calle = Null;
$x_numero = Null;
$x_colonia = Null;
$x_estado_id = Null;
$x_delegacion_id = Null;
$x_fecha_nacimiento = Null;
$x_promotor_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=membresia.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMaster = "";
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

$filter = array();

$filter['x_credito_tipo_id'] = 100;
$filter['x_nombre_srch'] = '';
$filter['x_apepat_srch'] = '';
$filter['x_apemat_srch'] = '';
$filter['x_crenum_srch'] = '';
$filter['x_clinum_srch'] = '';
$filter['x_cresta_srch'] = '';
$filter['x_entidad_srch'] = '';
$filter['x_delegacion_srch'] = '';
$filter['x_sucursal_srch'] = '';
$filter['x_promo_srch'] = ''; 
$filter['x_vendedor_srch'] = ''; 


if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}
	
}


if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}	
	
	
}


if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret    = array();
            foreach((array)$data as $k => $v) {
                $k    = urlencode($k);
                if(is_int($k) && $prefix != null) {
                    $k    = $prefix.$k;
                };
                if(!empty($key)) {
                    $k    = $key."[".$k."]";
                };

                if(is_array($v) || is_object($v)) {
                    array_push($ret,http_build_query($v,"",$sep,$k));
                }
                else {
                    array_push($ret,$k."=".urlencode($v));
                };
            };

        if(empty($sep)) {
            $sep = ini_get("arg_separator.output");
        };

        return    implode($sep, $ret);
    };
};


// Handle Reset Command
ResetCmd();

// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}

// Build SQL
$sSql = "SELECT * FROM `membresia`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>

<script src="paisedohint.js"></script> 
<script src="lochint.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">TABLE: membresia
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_membresialist.php?export=excel">Export to Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_membresiaadd.php">Agregar</a></span></td>
	</tr>
</table>
<p>
<?php } ?>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<?php if ($sExport == "") { ?>
<form action="php_membresialist.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php
$_QS = http_build_query($filter);
// Display page numbers
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	if ($nTotalRecs > $nDisplayRecs) {

		// Find out if there should be Backward or Forward Buttons on the TABLE.
		if ($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_membresialist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
		<?php
		}
		if ($isPrev || (!$rsEof)) {
			$x = 1;
			$y = 1;
			$dx1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nDisplayRecs*$nRecRange+1;
			$dy1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nRecRange+1;
			if (($dx1+$nDisplayRecs*$nRecRange-1) > $nTotalRecs) {
				$dx2 = intval($nTotalRecs/$nDisplayRecs)*$nDisplayRecs+1;
				$dy2 = intval($nTotalRecs/$nDisplayRecs)+1;
			} else {
				$dx2 = $dx1+$nDisplayRecs*$nRecRange-1;
				$dy2 = $dy1+$nRecRange-1;
			}
			while ($x <= $nTotalRecs) {
				if (($x >= $dx1) && ($x <= $dx2)) {
					if ($nStartRec == $x) { ?>
		<b><?php echo $y; ?></b>
					<?php } else { ?>
		<a href="php_membresialist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_membresialist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_membresialist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_membresialist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
							<?php }
					}
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				} else {
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				}
			}
		}

		// Next link
		if (!$rsEof) {
			$NextStart = $nStartRec + $nDisplayRecs;
			$isMore = True;  ?>
		<a href="php_membresialist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
		<?php } else {
			$isMore = False;
		} ?>
		<br>
<?php	}
	if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	Registros <?php echo  $nStartRec;  ?> al <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
membresia id
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("membresia_id"); ?>" style="color: #FFFFFF;">membresia id<?php if (@$_SESSION["membresia_x_membresia_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_membresia_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("fecha_registro"); ?>" style="color: #FFFFFF;">Tipo<?php if (@$_SESSION["membresia_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Precio
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("fecha_registro"); ?>" style="color: #FFFFFF;">Precio<?php if (@$_SESSION["membresia_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>        
        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha registro
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("fecha_registro"); ?>" style="color: #FFFFFF;">fecha registro<?php if (@$_SESSION["membresia_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
monto
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("monto"); ?>" style="color: #FFFFFF;">monto<?php if (@$_SESSION["membresia_x_monto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_monto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
status
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("status"); ?>" style="color: #FFFFFF;">status<?php if (@$_SESSION["membresia_x_status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
sucursal id
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("sucursal_id"); ?>" style="color: #FFFFFF;">sucursal id<?php if (@$_SESSION["membresia_x_sucursal_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_sucursal_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha expiracion
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("fecha_expiracion"); ?>" style="color: #FFFFFF;">fecha expiracion<?php if (@$_SESSION["membresia_x_fecha_expiracion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_fecha_expiracion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
numero cliente
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("numero_cliente"); ?>" style="color: #FFFFFF;">numero cliente<?php if (@$_SESSION["membresia_x_numero_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_numero_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
estado id
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("estado_id"); ?>" style="color: #FFFFFF;">estado id<?php if (@$_SESSION["membresia_x_estado_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_estado_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
delegacion id
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("delegacion_id"); ?>" style="color: #FFFFFF;">delegacion id<?php if (@$_SESSION["membresia_x_delegacion_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_delegacion_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha nacimiento
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("fecha_nacimiento"); ?>" style="color: #FFFFFF;">fecha nacimiento<?php if (@$_SESSION["membresia_x_fecha_nacimiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_fecha_nacimiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
promotor id
<?php }else{ ?>
	<a href="php_membresialist.php?order=<?php echo urlencode("promotor_id"); ?>" style="color: #FFFFFF;">promotor id<?php if (@$_SESSION["membresia_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["membresia_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php } ?>
	</tr>
<?php } ?>
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}
$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual = $nRecActual + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

		// Load Key for record
		$sKey = $row["membresia_id"];
		$x_membresia_id = $row["membresia_id"];
		$x_fecha_registro = $row["fecha_registro"];
		$x_monto = $row["monto"];
		$x_status = $row["status"];
		$x_sucursal_id = $row["sucursal_id"];
		$x_fecha_expiracion = $row["fecha_expiracion"];
		$x_nombre = $row["nombre"];
		$x_apellido_paterno = $row["apellido_paterno"];
		$x_apellido_materno = $row["apellido_materno"];
		$x_numero_cliente = $row["numero_cliente"];
		$x_calle = $row["calle"];
		$x_numero = $row["numero"];
		$x_colonia = $row["colonia"];
		$x_estado_id = $row["estado_id"];
		$x_delegacion_id = $row["delegacion_id"];
		$x_fecha_nacimiento = $row["fecha_nacimiento"];
		$x_promotor_id = $row["promotor_id"];
		$x_precio = $row["precio_id"];
		$x_tipo = $row["tipo_id"];
		$x_tipo_descripcion = "";
		$x_status_descripcion  = "";
		$x_sucursal = "";	
		$x_entidad = "";	
		$x_delegacion = "";
		$x_promotor = "";
		if($x_status > 0){
		$sqlStatus = "SELECT descripcion  FROM membresia_status WHERE membresia_status_id = $x_status";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 1".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_status_descripcion =  $rowstatus["descripcion"];	
		}
		
		if($x_precio > 0){
		$sqlStatus = "SELECT descripcion  FROM membresia_precio WHERE membresia_precio_id = $x_precio";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 1".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_descripcion_descripcion =  $rowstatus["descripcion"];
		$x_precio = 0;	
		}
		if($x_sucursal_id > 0){
		$sqlStatus = "SELECT nombre  FROM sucursal WHERE sucursal_id = $x_sucursal_id";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 2".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_sucursal =  $rowstatus["nombre"];
		}
				
		if($x_estado_id > 0){
		$sqlStatus = "SELECT nombre  FROM entidad WHERE entidad_id = $x_estado_id";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 3".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_entidad =  $rowstatus["nombre"];
		}
		
		if($x_delegacion_id > 0){
		$sqlStatus = "SELECT descripcion  FROM delegacion WHERE delegacion_id = $x_delegacion_id";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 4".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_delegacion =  $rowstatus["descripcion"];
		}
		
		
		if($x_promotor_id > 0){
		$sqlStatus = "SELECT nombre_completo  FROM promotor WHERE promotor_id = $x_promotor_id";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 5".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_promotor =  $rowstatus["nombre_completo"];
		}
		if($x_tipo > 0){
		$sqlStatus = "SELECT descripcion  FROM membresia_tipo WHERE membresia_tipo_id = $x_tipo";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die ("Error al seleccionar el status de la membresia 5".phpmkr_error()."sql:".$sqlStatus);
		$rowstatus = phpmkr_fetch_array($rsStatus);
		$x_tipo_descripcion =  $rowstatus["descripcion"];
		}
				
		$x_hoy = date("Y-m-d");
		$x_dias_vencida = 0;
		$x_renovar_membresia = 0;
		
		 if ($x_fecha_expiracion < $x_hoy ){
			 // la membresia ya vencio
			 // contamos cuantos dias tiene vencido y si tiene mas de un mes ya no puede renovar la membresia
			 $sqlFechas = " SELECT DATEDIFF('$x_hoy','$x_fecha_expiracion') as dias_vencida ";
			 $rsFechas = phpmkr_query($sqlFechas,$conn)or die("Error al seleccionar los dias de vecida para la menbresia".phpmkr_error()."Sql:".$sqlFechas);
			 $rowFechas =  phpmkr_fetch_array($rsFechas);
			 $x_dias_vencida = $rowFechas["dias_vencida"];
			 	 
			 }
			
		if($x_dias_vencida < 30){
			$x_renovar_membresia = 1;
			}
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- membresia_id -->
		<td><span>
<?php echo $x_membresia_id; ?>
</span></td>
		<!-- fecha_registro -->
		<td><span>
<?php echo $x_tipo_descripcion; ?>
</span></td>
<td><span>
<?php echo $x_descripcion_descripcion; ?>
</span></td>

 
<td><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
</span></td>
		<!-- monto -->
		<td><span>
<?php echo $x_monto; ?>
</span></td>
		<!-- status -->
		<td><span>
<?php echo $x_status_descripcion; ?>
</span></td>
		<!-- sucursal_id -->
		<td><span>
<?php echo $x_sucursal; ?>
</span></td>
		<!-- fecha_expiracion -->
		<td><span>
<?php echo FormatDateTime($x_fecha_expiracion,5); ?>
</span></td>
		<!-- numero_cliente -->
		<td><span>
<?php echo $x_numero_cliente; ?>
</span></td>
		<!-- estado_id -->
		<td><span>
<?php echo $x_entidad; ?>
</span></td>
		<!-- delegacion_id -->
		<td><span>
<?php echo $x_delegacion; ?>
</span></td>
		<!-- fecha_nacimiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_nacimiento,5); ?>
</span></td>
		<!-- promotor_id -->
		<td><span>
<?php echo $x_promotor; ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_membresiaview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Ver</a></span></td>
<td><span class="phpmaker"><?php if ($x_hoy <= $x_fecha_registro ){?><a href="<?php if ((!is_null($sKey))) { echo "php_membresiaedit.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Editar</a><?php }?></span></td>
<td><?php if($x_status == 2 && $x_dias_vencida <= 30){?><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_membresia_renova.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Renovar.</a></span><?php }?></td>
<td><?php if ($x_tipo == 2){?>
<span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_membresia_cambia_precio.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">cambiar precio.</a></span><?php }?>
</td>
<?php } ?>
	</tr>
<?php
	}
}
?>
</table>
</form>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field membresia_id
		if ($sOrder == "membresia_id") {
			$sSortField = "`membresia_id`";
			$sLastSort = @$_SESSION["membresia_x_membresia_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_membresia_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_membresia_id_Sort"] <> "") { @$_SESSION["membresia_x_membresia_id_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["membresia_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_fecha_registro_Sort"] <> "") { @$_SESSION["membresia_x_fecha_registro_Sort"] = ""; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["membresia_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_monto_Sort"] <> "") { @$_SESSION["membresia_x_monto_Sort"] = ""; }
		}

		// Field status
		if ($sOrder == "status") {
			$sSortField = "`status`";
			$sLastSort = @$_SESSION["membresia_x_status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_status_Sort"] <> "") { @$_SESSION["membresia_x_status_Sort"] = ""; }
		}

		// Field sucursal_id
		if ($sOrder == "sucursal_id") {
			$sSortField = "`sucursal_id`";
			$sLastSort = @$_SESSION["membresia_x_sucursal_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_sucursal_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_sucursal_id_Sort"] <> "") { @$_SESSION["membresia_x_sucursal_id_Sort"] = ""; }
		}

		// Field fecha_expiracion
		if ($sOrder == "fecha_expiracion") {
			$sSortField = "`fecha_expiracion`";
			$sLastSort = @$_SESSION["membresia_x_fecha_expiracion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_fecha_expiracion_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_fecha_expiracion_Sort"] <> "") { @$_SESSION["membresia_x_fecha_expiracion_Sort"] = ""; }
		}

		// Field numero_cliente
		if ($sOrder == "numero_cliente") {
			$sSortField = "`numero_cliente`";
			$sLastSort = @$_SESSION["membresia_x_numero_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_numero_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_numero_cliente_Sort"] <> "") { @$_SESSION["membresia_x_numero_cliente_Sort"] = ""; }
		}

		// Field estado_id
		if ($sOrder == "estado_id") {
			$sSortField = "`estado_id`";
			$sLastSort = @$_SESSION["membresia_x_estado_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_estado_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_estado_id_Sort"] <> "") { @$_SESSION["membresia_x_estado_id_Sort"] = ""; }
		}

		// Field delegacion_id
		if ($sOrder == "delegacion_id") {
			$sSortField = "`delegacion_id`";
			$sLastSort = @$_SESSION["membresia_x_delegacion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_delegacion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_delegacion_id_Sort"] <> "") { @$_SESSION["membresia_x_delegacion_id_Sort"] = ""; }
		}

		// Field fecha_nacimiento
		if ($sOrder == "fecha_nacimiento") {
			$sSortField = "`fecha_nacimiento`";
			$sLastSort = @$_SESSION["membresia_x_fecha_nacimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_fecha_nacimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_fecha_nacimiento_Sort"] <> "") { @$_SESSION["membresia_x_fecha_nacimiento_Sort"] = ""; }
		}

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["membresia_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["membresia_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["membresia_x_promotor_id_Sort"] <> "") { @$_SESSION["membresia_x_promotor_id_Sort"] = ""; }
		}
		$_SESSION["membresia_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["membresia_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["membresia_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["membresia_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["membresia_REC"] = $nStartRec;
	}
	elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["membresia_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["membresia_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["membresia_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["membresia_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["membresia_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["membresia_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["membresia_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["membresia_OrderBy"] = $sOrderBy;
			if (@$_SESSION["membresia_x_membresia_id_Sort"] <> "") { $_SESSION["membresia_x_membresia_id_Sort"] = ""; }
			if (@$_SESSION["membresia_x_fecha_registro_Sort"] <> "") { $_SESSION["membresia_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["membresia_x_monto_Sort"] <> "") { $_SESSION["membresia_x_monto_Sort"] = ""; }
			if (@$_SESSION["membresia_x_status_Sort"] <> "") { $_SESSION["membresia_x_status_Sort"] = ""; }
			if (@$_SESSION["membresia_x_sucursal_id_Sort"] <> "") { $_SESSION["membresia_x_sucursal_id_Sort"] = ""; }
			if (@$_SESSION["membresia_x_fecha_expiracion_Sort"] <> "") { $_SESSION["membresia_x_fecha_expiracion_Sort"] = ""; }
			if (@$_SESSION["membresia_x_numero_cliente_Sort"] <> "") { $_SESSION["membresia_x_numero_cliente_Sort"] = ""; }
			if (@$_SESSION["membresia_x_estado_id_Sort"] <> "") { $_SESSION["membresia_x_estado_id_Sort"] = ""; }
			if (@$_SESSION["membresia_x_delegacion_id_Sort"] <> "") { $_SESSION["membresia_x_delegacion_id_Sort"] = ""; }
			if (@$_SESSION["membresia_x_fecha_nacimiento_Sort"] <> "") { $_SESSION["membresia_x_fecha_nacimiento_Sort"] = ""; }
			if (@$_SESSION["membresia_x_promotor_id_Sort"] <> "") { $_SESSION["membresia_x_promotor_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["membresia_REC"] = $nStartRec;
	}
}
?>
