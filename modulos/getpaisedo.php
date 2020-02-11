<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php
header('Content-Type: text/html; charset=ISO-8859-1');
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_entidad_id = $_GET["q1"];
$x_delegacion_name = $_GET["q2"];
$x_delegacion_id = $_GET["q2"];


if($x_delegacion_name == "x_delegacion_id"){
		if($x_entidad_id > 0) {
$x_delegacion_idList = "<select name=\"$x_delegacion_name\" class=\"texto_normal\"  onchange=\"showLoc(this,'txtHint3', 'x_localidad_id')\"   >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";
echo "Alcaldia: ".$x_delegacion_idList;
}else{
 echo "No localizado";
}

}else if($x_delegacion_name == "x_delegacion_id2"){
	if($x_entidad_id > 0) {
	$x_delegacion_idList = "<select name=\"$x_delegacion_name\" class=\"texto_normal\"  onchange=\"showLoc(this,'txtHint4', 'x_localidad_id2')\"   >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";
echo "Alcaldia: ".$x_delegacion_idList;
}else{
 echo "No localizado";
}



	}
?>
