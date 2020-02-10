<?php include("../db.php");?>
<?php include ("../phpmkrfn.php") ?>
<?php
//header('Content-Type: text/html; charset=ISO-8859-1');
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_delegacion_id = $_GET["q1"];
$x_localidad_name = $_GET["q2"];
$x_localidad_id = $_GET["q2"];
$x_estado_id = $_GET["qe"];

if($x_delegacion_id > 0) {
$x_delegacion_idList = "<select name=\"$x_localidad_name\" class=\"texto_normal\" >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion_s FROM inegi_localidad where municipio_id = $x_delegacion_id and estado_id = ".$x_estado_id." order by descripcion_s ";
//echo $sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["localidad_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion_s"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";

echo $x_delegacion_idList;
}else{
 echo "No localizado";
}
?>