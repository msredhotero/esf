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
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_status_id = Null; 
$ox_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
		$sAction = "I"; // Display blank record
}else{

	// Get fields from form

	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_creditolist.php");
			exit();
		}
		break;
	case "A": // Add
		switch ($x_credito_tipo_id)
		{
			case "1": // Get a record to display
				header("Location: php_solicitudadd.php");
				exit();
				break;
			case "2": // Get a record to display
				header("Location: php_solicitud_caadd.php");
				exit();
				break;
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

function EW_checkMyForm() {
EW_this = document.creditoadd;
validada = true;
if (EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Seleccione un tipo de credito."))
		validada = false;
}

if (validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

}

//-->
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
<p><span class="phpmaker">NUEVA SOLICITUD<br>
  <br>
    <a href="php_solicitudlist.php">Ir a la lista</a></span></p>
<form name="creditoadd" id="creditoadd" action="php_solicitudadd_sel.php" method="post">
<p>
<input type="hidden" name="a_add" value="A">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table class="ewTable">
	<tr>
	  <td class="ewTableHeaderThin" width="161">Tipo de Cr&eacute;dito</td>
	  <td width="827" class="ewTableAltRow"><span>
        <?php
$x_solicitud_idList = "<select name=\"x_credito_tipo_id\" >";
$x_solicitud_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo` ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_solicitud_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["credito_tipo_id"] == @$x_credito_tipo_id) {
			$x_solicitud_idList .= "' selected";
		}
		$x_solicitud_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_solicitud_idList .= "</select>";
echo $x_solicitud_idList;
?>
      </span></td>
	  </tr>
</table>
<p>

<input type="button" name="Action" value="Seleccionar" onclick="EW_checkMyForm();">

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
	global $x_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_id) : $x_credito_id;
	$sWhere .= "(`credito_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
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
	global $x_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_credito_id == "") || (is_null($x_credito_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_id) : $x_credito_id;			
		$sWhereChk .= "(`credito_id` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

	// Field credito_tipo_id
	$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;

	// Field credito_status_id
	$theValue = ($GLOBALS["x_credito_status_id"] != "") ? intval($GLOBALS["x_credito_status_id"]) : "NULL";
	$fieldList["`credito_status_id`"] = $theValue;

	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_otrogamiento`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;


	// Field tasa
	$theValue = ($GLOBALS["x_tasa"] != "") ? " '" . doubleval($GLOBALS["x_tasa"]) . "'" : "NULL";
	$fieldList["`tasa`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo"] != "") ? intval($GLOBALS["x_plazo"]) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
	$fieldList["`fecha_vencimiento`"] = $theValue;

	// Field tasa_moratoria
	$theValue = ($GLOBALS["x_tasa_moratoria"] != "") ? " '" . doubleval($GLOBALS["x_tasa_moratoria"]) . "'" : "NULL";
	$fieldList["`tasa_moratoria`"] = $theValue;

	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `credito` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	$x_credito_id = mysql_insert_id();
	
	
//GENERA VENCIMIENTOS

	include("utilerias/datefunc.php");

	$sSql = "SELECT valor FROM plazo where plazo_id = ".$GLOBALS["x_plazo"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_plazo = $row["valor"];
	phpmkr_free_result($rs);		

	$sSql = "SELECT valor FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_forma_pago = $row["valor"];
	phpmkr_free_result($rs);		


	$x_num_pagos = $x_plazo * $x_forma_pago;

	$GLOBALS["x_importe"] = str_replace(",","",$GLOBALS["x_importe"]);
	$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]));	
	$x_interes = 0;
	$x_pago_act = 1;
	while($x_pago_act < $x_num_pagos + 1){
		switch ($GLOBALS["x_forma_pago_id"])
		{
			case "1": // semanal
				$temptime = DateAdd('w',7,$temptime);				
				break;
			case "2": // quincenal
				$temptime = DateAdd('w',14,$temptime);
				break;
			case "3": // mensual
				$temptime = DateAdd('w',28,$temptime);				
				break;				
		}

		$fecha_act = strftime('%Y-%m-%d',$temptime);			

		$x_interes_act = (1/pow((1+doubleval($GLOBALS["x_tasa"]  / 100 )),$x_pago_act));

		$x_interes = $x_interes + $x_interes_act;
	
		$sSql = "insert into vencimiento values(0,$x_credito_id, $x_pago_act,1, '$fecha_act', 0, 0, 0, 0)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$temptime = strtotime($fecha_act);
		$x_pago_act++;	
	}		

	$x_total_venc = round($GLOBALS["x_importe"] / $x_interes);
	$x_capital_venc = ($GLOBALS["x_importe"] / $x_num_pagos);
	$x_interes_venc = $x_total_venc - $x_capital_venc;

	$sSql = "update vencimiento set importe = $x_capital_venc, interes = $x_interes_venc, total_venc = $x_total_venc where credito_id = $x_credito_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	


	$sSql = "update credito set fecha_vencimiento = '$fecha_act' where credito_id = $x_credito_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	$sSql = "update solicitud set solicitud_status_id = 6 where solicitud_id =  ".$GLOBALS["x_solicitud_id"];
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	phpmkr_query('commit;', $conn);	 
	return true;
}
?>

