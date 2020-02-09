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
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);	
//echo "entra a login<br>";
if ((isset($_POST["userid"])) && ($_POST["userid"] != "") ){
	$bValidPwd = false;
#echo "post viene lleno";
	// Setup variables
	$sUserId = @$_POST["userid"];
	$sPassWd = @$_POST["passwd"];
	#echo "user".$sUserId."<br>";
	#echo "password".$sPassWd."<br>";

	if (!($bValidPwd)) {
		#echo "entra primer if<br>";
			$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
			$sUserId = (!get_magic_quotes_gpc()) ? addslashes($sUserId) : $sUserId;
			$sSql = "SELECT * FROM `usuario`";
			$sSql .= " WHERE `usuario` = '" . $sUserId . "'";
				#echo "sql:".$sSql."-";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			if (phpmkr_num_rows($rs) > 0) {
			$row = phpmkr_fetch_array($rs);
				if (strtoupper($row["clave"]) == strtoupper($sPassWd)) {
					$_SESSION["php_project_esf_status_UserID"] = $row["usuario_id"];
					$_SESSION["php_project_esf_status_UserRolID"] = $row["usuario_rol_id"];					
					$_SESSION["php_project_esf_status_UserName"] = $row["nombre"];					
					$_SESSION["crm_UserRolID"] = $row["usuario_rol_id"];
					$_SESSION["php_project_esf_SysAdmin"] = 0; // non System Administrator
					echo "la clave corresponde <br>";
					if($_SESSION["php_project_esf_status_UserRolID"] == 7){
						echo "entro en promotor";
						$sSql = "select promotor_id from promotor where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						echo $sSql;
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_PromotorID"] = $row2["promotor_id"];							
							$bValidPwd = true;
						}
						phpmkr_free_result($rs2);
					}else{
						$bValidPwd = true;
					}
					
					if($_SESSION["php_project_esf_status_UserRolID"] == 12){
						$sSql = "select * from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_ResponsableID"] = $row2["responsable_sucursal_promotor_id"];	
							$_SESSION["php_project_esf_status_SucursalID"] = $row2["sucursal_id"];							
							$bValidPwd = true;
						}
						phpmkr_free_result($rs2);
					}else{
						$bValidPwd = true;
					}
					
						if($_SESSION["php_project_esf_status_UserRolID"] == 17){
						$sSql = "select * from gerente_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_GerenteID"] = $row2["gerente_sucursal_id"];	
							$_SESSION["php_project_esf_status_SucursalID"] = $row2["sucursal_id"];							
							$bValidPwd = true;
						}
						phpmkr_free_result($rs2);
					}else{
						$bValidPwd = true;
					}
					
					if($_SESSION["php_project_esf_status_UserRolID"] == 16){
						$sSql = "select responsable_sucursal_id from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_GestorID"] = $row2["usuario_id"];	
													
							$bValidPwd = true;
						}
						phpmkr_free_result($rs2);
					}else{
						$bValidPwd = true;
					}
					
					
				}
			}
	phpmkr_free_result($rs);
	phpmkr_db_close($conn);
	}
	if ($bValidPwd) {

		// Write cookies
		$_SESSION["php_project_esf_status"] = "login";
		echo  $_SESSION["php_project_esf_status"];
		if (@$_POST["rememberme"] <> "") {
		#	setCookie("php_project_esf_userid", $sUserId, time()+365*24*60*60); // change cookie expiry time here
			setCookie("php_project_esf_userid", $sUserId, time()+3600); // change cookie expiry time here  600/60 = 10 min
			setCookie("php_project_esf_userid", $_SESSION["php_project_esf_status"], time()+3600); // change cookie expiry time here  600/60 = 10 min
		}
		$_SESSION["php_project_esf_status"] = "login";
		echo $_SESSION["php_project_esf_status"];
		ob_end_clean();
		header("Location: index.php");
		exit();
	} else {
		$_SESSION["ewmsg"] = "Datos incorrectos, verifique.";
	}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script language="JavaScript" type="text/javascript" src="ajax_captcha.js"></script>
<script type="text/javascript">
<!--
function EW_checkMyForm() {
	EW_this = document.frmdata;	
	vdata = true;	
	
	if (!EW_hasValue(EW_this.userid, "TEXT" )) {
		if  (!EW_onError(EW_this, EW_this.userid, "TEXT", "Por favor ingrese su Cuenta."))
			vdata = false;
	}
	if (vdata && !EW_hasValue(EW_this.passwd, "PASSWORD" )) {
		if (!EW_onError(EW_this, EW_this.passwd, "PASSWORD", "Por favor ingrese su Clave."))
			vdata = false;
	}
	if (vdata && EW_this.txtsscode && !EW_hasValue(EW_this.txtsscode, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.txtsscode, "TEXT", "Ingrese el código de seguridad."))
			vdata = false;
	}
	

	if(vdata){
		get(document.getElementById('frmdata'),2);
	}
}

//-->
</script>
<p><span class="phpmaker">Inicio de Sesion</span></p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<form name="frmdata" id="frmdata" action="login.php" method="post" >
<table border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td><span class="phpmaker">Cuenta</span></td>
		<td><span class="phpmaker"><input type="text" name="userid" size="20" value="<?php echo @$_COOKIE["php_project_esf_userid"]; ?>"></span></td>
	</tr>
	<tr>
	  <td><span class="phpmaker">Clave</span></td>
	  <td><span class="phpmaker">
	    <input type="password" name="passwd" size="20" />
	  </span></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td><img id="imgCaptcha" src="create_image.php" onclick="setTimeout('refreshimg()', 300); return false;" alt="Clic para cambiar c&oacute;digo"/></td>
	  </tr>
	<tr>
		<td>C&oacute;digo</td>
		<td><input id="txtsscode" name="txtsscode" size="10" maxlength="10" /></td>
	</tr>
	<tr>
	  <td colspan="2" align="center"><span class="phpmaker">
	    <input type="button" name="enviar" value="Ingresar" onclick="EW_checkMyForm()" onmouseover="javascript: this.style.cursor='pointer'"  />
	    </span></td>
	  </tr>
</table>
</form>
<br>
<p><span class="phpmaker">
</span></p>
<?php include ("footer.php") ?>
