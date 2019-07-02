<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php
//header('Content-Type: text/html; charset=ISO-8859-1');
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_entidad_id = $_GET["q1"];
$x_delegacion_name = $_GET["q2"];
$x_delegacion_id = $_GET["q3"];
$x_estado = $_GET["qe"];
if($x_delegacion_name == "x_delegacion_id"){
		if($x_entidad_id > 0) {
$x_delegacion_idList = "<select name=\"$x_delegacion_name\" class=\"texto_normal\"  onchange=\"showLoc2(this,'txtHint3', 'x_localidad_id','x_entidad_domicilio')\"   >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT municipio_id, descripcion_s FROM inegi_municipio where estado_id = $x_estado";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["municipio_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion_s"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";
echo "Del/Mun: ".$x_delegacion_idList;
}else{
 echo "No localizado";
}

}else if($x_delegacion_name == "x_delegacion_id2"){
	if($x_entidad_id > 0) {
	$x_delegacion_idList = "<select name=\"$x_delegacion_name\" class=\"texto_normal\"  onchange=\"showLoc2(this,'txtHint4', 'x_localidad_id2','x_entidad_negocio')\"   >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT municipio_id, descripcion_s FROM inegi_municipio where estado_id = $x_estado";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["municipio_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion_s"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";
echo "Del/Mun: ".$x_delegacion_idList;
}else{
 echo "No localizado";
}
	}
?>
