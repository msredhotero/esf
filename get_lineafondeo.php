<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_fondeo_empresa_id = $_GET["q1"];
$x_fondeo_credito_id = $_GET["q2"];

if($x_fondeo_empresa_id > 1) {
$x_delegacion_idList = "<select name=\"x_fondeo_credito_id\" >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_credito_id, credito_num, importe FROM fondeo_credito where fondeo_empresa_id = $x_fondeo_empresa_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_credito_id"] == @$x_fondeo_credito_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= "> Num: " . $datawrk["credito_num"] . " Importe: " . FormatNumber($datawrk["importe"],2,0,0,1) . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
$x_delegacion_idList .= "</select>";
echo "Linea C.: ".$x_delegacion_idList;
}else{
 echo "<input type=\"hidden\" name=\"x_fondeo_credito_id\" value=\"6\" />";
}
?>