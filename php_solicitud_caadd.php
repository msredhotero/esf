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
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_status_id = Null; 
$ox_solicitud_status_id = Null;
$x_folio = Null; 
$ox_folio = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_importe_solicitado = Null; 
$ox_importe_solicitado = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_contrato = Null; 
$ox_contrato = Null;
$x_pagare = Null; 
$ox_pagare = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_solicitud_id = @$_GET["solicitud_id"];
if (empty($x_solicitud_id)) {
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
	$x_solicitud_id = 0;
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_grupo_nombre = @$_POST["x_grupo_nombre"];	
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_comentario_promotor = @$_POST["x_comentario_promotor"];
	$x_comentario_comite = @$_POST["x_comentario_comite"];	



}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "LOS DATOS PRINCIPALES DE LA SOLICITUD HAN SIDO REGISTRADOS, AHORA PUEDE REGSTRAR A LOS ASOCIADOS..";
			/*
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
			exit();
			*/
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

function validaimporte(){

EW_this = document.solicitud_caadd;
if(EW_this.x_importe_solicitado.value < 3000){
	alert("El importe es incorrecto valor minimo 3000");
	EW_this.x_importe_solicitado.focus();
}
}


function EW_checkMyForm() {
EW_this = document.solicitud_caadd;
validada = true;

/*
if (EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
*/
if (validada == true && EW_this.x_grupo_nombre && !EW_hasValue(EW_this.x_grupo_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_grupo_nombre, "TEXT", "Indique el nombre del grupo."))
		validada = false;
}

if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el plazo solicitado."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}


if(validada == true){
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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker"><a href="php_solicitudlist.php">Regresar a la Lista</a></span></p>
<form name="solicitud_caadd" id="solicitud_caadd" action="php_solicitud_caadd.php" method="post" >
<p>
<input type="hidden" name="a_add" value="A">


<input type="hidden" name="x_credito_tipo_id" value="2">


<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>


<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top">
	<table width="674" border="0" cellspacing="0" cellpadding="0">
	
	<tr>
	  <td class="texto_normal">Nombre del Grupo:</td>
	  <td colspan="4"><input name="x_grupo_nombre" type="text" id="x_grupo_nombre" value="<?php echo htmlspecialchars(@$x_grupo_nombre) ?>" size="50" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Promotor:</td>
	  <td colspan="4"><span class="phpmaker">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";		
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == @$x_promotor_id) {
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
	  </span></td>
	  </tr>
	
	<tr>
	  <td width="159" class="texto_normal">Tipo de Crédito: </td>
	  <td colspan="2" class="texto_normal_bold">ASOCIADO</td>
	  <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
	  <td width="164">
	  <span class="texto_normal">
	  <b>
	  <?php echo $currdate; ?>	  </b>	  </span>
	  <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Importe solicitado: </span></td>
	  <td width="111"><div align="left">
	    <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php echo htmlspecialchars(@$x_importe_solicitado) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" onblur="validaimporte()" />
	  </div></td>
	  <td width="10">&nbsp;</td>
	  <td><div align="right"><span class="texto_normal">Plazo deseado (Meses):</span></div></td>
	  <td><span class="texto_normal">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_plazo_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `plazo_id`, `descripcion` FROM `plazo` order by valor";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["plazo_id"] == @$x_plazo_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
	  </span></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
	  <td><span class="texto_normal">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
	  </span></td>
	  </tr>
	</table>	</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Asociados</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal">
	<?php if($x_solicitud_id > 0){ ?>
	<iframe name="asociados" src="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>" style="margin-left:2px; width:700px; height:600px; border:0 " allowtransparency="true" frameborder="0"></iframe>
	<?php }else{ ?>
	Primero registre los datos genrales de la solcitud para poder incluir a los asociados.
	<?php } ?>
	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Promotor</span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_promotor" cols="60" rows="5"><?php echo $x_comentario_promotor; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Comite de Cr&eacute;dito </span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo $x_comentario_comite; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Términos y condiciones </div></td>
    </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="34">&nbsp;</td>
        <td width="600">&nbsp;</td>
        <td width="66">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left" class="texto_small">Por este conducto autorizo expresamente a Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R., para que por conducto de sus funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento e historia&iacute; crediticio, asi como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga, en las Sociedades de Informaci&oacute;n Crediticia que estime conveniente. As&iacute; mismo, declaro que conozco la naturaleza y alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. har&aacute; de ta&iacute; informaci&oacute;n y de que &eacute;sta podr&aacute; realzar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que esta autorizaci&oacute;n se encuentre vigente por un periodo de 3 a&ntilde;os contados a partir de la fecha de su expedici&oacute;n y
            en todo caso durante el tiempo que mantengamos una relaci&oacute;n jur&iacute;dica. Estoy conciente y acepto que este documento quede bajo propiedad de Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. para efectos de control y cumplimiento del art. 28 de la Ley para regular a las Sociedades e informaci&oacute;n Cr&eacute;diticia. <br />
            <br />
            De acuerdo al Cap&iacute;tulo II, Secci&oacute;n Primera, Art&iacute;culo 3, cl&aacute;usula cuatro de la Ley para la Transparencia y Ordenamiento de los Servicios Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto M&uacute;ltiple No Reguladas; por &eacute;ste medio expreso mi consentimiento que a trav&eacute;s del personal facultado de &quot;Microfinanciera Crece SOFOM ENR&quot;, he sido enterado del Costo Anual Total del cr&eacute;dito que estoy interesado en celebrar. Tambi&eacute;n he sido enterado de la tasa de inter&eacute;s moratoria que se cobrar&aacute; en caso de presentar atraso(s) en alguno(s) de los vencimientos del pr&eacute;stamo. Tambi&eacute;n de acuerdo al Cap&iacute;tulo IV, Secci&oacute;n Primera, Art&iacute;culo 23 de la misma; estoy de acuerdo
            en consultar mi estado de cuenta a trav&eacute;s de internet mediante la p&aacute;gina www.financieracrece.com con el usuario y contrase&ntilde;a que &quot;Microfinanciera Crece SOFOM ENR&quot; a trav&eacute;s de su personal facultado me hagan saber toda vez que se firme el pagar&eacute; correspondiente al cr&eacute;dito que estoy interesado en pactar.</div>		</td>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55"><div align="center">
              <input name="x_acepto" type="checkbox" class="texto_normal" value="1" />
            </div></td>
            <td width="245" class="texto_normal">Acepto estos Términos y condiciones.</td>
            </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center">
	<?php if($x_solicitud_id > 0){ ?>	
	&nbsp;
	<?php }else{ ?>
      <input type="button" value="Registrar Solicitud" class="boton_medium" onclick="EW_checkMyForm()" />
	<?php } ?>	  
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php
//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_solicitud_id;


	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

//SOLICITUD


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_grupo_nombre"]) : $GLOBALS["x_grupo_nombre"];
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`grupo_nombre`"] = $theValue;

	// Field credito_tipo_id
	$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $GLOBALS["x_contrato"][0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $GLOBALS["x_pagare"][0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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
	$x_solicitud_id = mysql_insert_id();

//FOLIO	
	$currentdate_fol = getdate(time());
	$x_solicitud_fol = str_pad($x_solicitud_id, 5, "0", STR_PAD_LEFT);
	$x_dia_fol = str_pad($currentdate_fol["mday"], 2, "0", STR_PAD_LEFT);
	$x_mes_fol = str_pad($currentdate_fol["mon"], 2, "0", STR_PAD_LEFT);
	$x_year_fol = str_pad($currentdate_fol["year"], 2, "0", STR_PAD_LEFT);			
	
	$x_folio = "FA$x_solicitud_fol".$x_dia_fol.$x_mes_fol.$x_year_fol;	
	$sSql = "update solicitud set folio = '$x_folio' where solicitud_id = $x_solicitud_id";
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
