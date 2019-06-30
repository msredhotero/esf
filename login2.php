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
if (@$_POST["submit"] <> "") {
	$bValidPwd = false;

	// Setup variables
	$sUserId = @$_POST["userid"];
	$sPassWd = @$_POST["passwd"];
	if (!($bValidPwd)) {
			$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
			$sUserId = (!get_magic_quotes_gpc()) ? addslashes($sUserId) : $sUserId;
			$sSql = "SELECT * FROM `usuario`";
			$sSql .= " WHERE `usuario` = '" . $sUserId . "'";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			if (phpmkr_num_rows($rs) > 0) {
			$row = phpmkr_fetch_array($rs);
				if (strtoupper($row["clave"]) == strtoupper($sPassWd)) {
					$_SESSION["php_project_esf_status_UserID"] = $row["usuario_id"];
					$_SESSION["php_project_esf_status_UserRolID"] = $row["usuario_rol_id"];					
					$_SESSION["php_project_esf_status_UserName"] = $row["nombre"];					
					$_SESSION["php_project_esf_SysAdmin"] = 0; // non System Administrator
					
					if($_SESSION["php_project_esf_status_UserRolID"] == 7){
						$sSql = "select promotor_id from promotor where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
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
						
						$sSql = "select responsable_sucursal_id, sucursal_idll from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						echo $sSql;
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_ResponsableID"] = $row2["responsable_sucursal_id"];	
							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						
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
		if (@$_POST["rememberme"] <> "") {
			setCookie("php_project_esf_userid", $sUserId, time()+365*24*60*60); // change cookie expiry time here
		}
		$_SESSION["php_project_esf_status"] = "login";
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
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
	if (!EW_hasValue(EW_this.userid, "TEXT" )) {
		if  (!EW_onError(EW_this, EW_this.userid, "TEXT", "Por favor ingrese su Cuenta."))
			return false;
	}
	if (!EW_hasValue(EW_this.passwd, "PASSWORD" )) {
		if (!EW_onError(EW_this, EW_this.passwd, "PASSWORD", "Por favor ingrese su Clave."))
			return false;
	}
	return true;
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
<form action="login2.php" method="post" onSubmit="return EW_checkMyForm(this);">
<table border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td><span class="phpmaker">Cuenta</span></td>
		<td><span class="phpmaker"><input type="text" name="userid" size="20" value="<?php echo @$_COOKIE["php_project_esf_userid"]; ?>"></span></td>
	</tr>
	<tr>
		<td><span class="phpmaker">Clave</span></td>
		<td><span class="phpmaker"><input type="password" name="passwd" size="20"></span></td>
	</tr>
	
	<tr>
		<td colspan="2" align="center"><span class="phpmaker"><input type="submit" name="submit" value="Ingresar">
		</span></td>
	</tr>
</table>
</form>
<br>
<p><span class="phpmaker">
</span></p>
<?php include ("footer.php") ?>
