<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>

<?php 
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_tipo_membresia = $_GET["x_tipo_membresia"];


$x_estado_civil_idList = "<select name=\"x_precio\" id=\"x_precio\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";				
				$sSqlWrk = "SELECT `membresia_precio_id`, `descripcion` FROM `membresia_precio` ";	
				if($x_tipo_membresia == 1){
					$sSqlWrk .= "WHERE  membresia_precio_id = 1 ";
					}else{
						$sSqlWrk .= "WHERE  membresia_precio_id > 1";
						}
							
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["membresia_precio_id"] == @$x_status) {
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