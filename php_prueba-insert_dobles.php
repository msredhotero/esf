
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php 
$x_folio_siguiente_nota = 1;
$x_credito_id = 1;
$x_fecha_folio = date("Y-m-d");
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_hoy = date("Y-m-d");
//$x_hoy = "2014-01-31";

$sqlLastDay = "SELECT LAST_DAY (\"$x_hoy\") AS ultimo_dia_mes ";
$rsLastDay = phpmkr_query($sqlLastDay,$conn) or die ("Error en dia".phpmkr_error().$sqlLastDay);
$rowLastDay =  phpmkr_fetch_array($rsLastDay);
$x_ultimo_dia_mes = $rowLastDay["ultimo_dia_mes"];

$x_fecha_mes = explode("-",$x_ultimo_dia_mes);
$x_anio = $x_fecha_mes[0];
$x_mes = $x_fecha_mes[1];
$x_dia = "01";
$x_dia_fin = $x_fecha_mes[2];
$x_primer_dia_mes = $x_anio."-".$x_mes."-".$x_dia;
// seleccionamos todos los creditos con pagos para el mes de fecturacion
// que esten activos o que esten liquidados
$x_todayy = date("Y-m-d");

//$sSql = "SELECT credito.credito_id  FROM vencimiento, credito  WHERE credito.credito_id = vencimiento.credito_id and vencimiento.fecha_vencimiento >= \"$x_primer_dia_mes\"  and vencimiento.fecha_vencimiento <= \"$x_ultimo_dia_mes\" and credito.credito_id not in(1489)  and (credito.credito_status_id = 1 || credito.credito_status_id = 3) GROUP BY credito_id ";

//NOTAS DE CREDITO
#status_id = 1 = entra  como nota de credito
$sSql = " SELECT * FROM condonacion WHERE fecha_registro  >= \"$x_primer_dia_mes\" and fecha_registro <= \"$x_ultimo_dia_mes\" and status_id = 1 GROUP BY credito_id ";
$rs = phpmkr_query($sSql,$conn)or die ("Erro al seleccionar".phpmkr_error()."sql:".$sSql);
while($row = phpmkr_fetch_array($rs)){
$x_credito_id =  $row["credito_id"];

$sqlinsertFolio = "INSERT INTO `folio_nota_credito` (`folio_nota_credito_id`, `numero`,`credito_id`, `fecha`) ";
$sqlinsertFolio .=" VALUES (NULL, $x_folio_siguiente_nota, $x_credito_id, \"$x_fecha_folio\") " ;
$rsInsertFolio = phpmkr_query($sqlinsertFolio,$conn) or die("error al insertar en folio".phpmkr_error()."sql ".$sqlinsertFolio);
echo $sqlinsertFolio;

}
?>