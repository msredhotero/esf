<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}

include ("db.php");
include ("phpmkrfn.php");
include("utilerias/datefunc.php");
include("amount2txt.php");


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate_original = $currdate;	
$currdate = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),true);


// Get keys
$x_formato_id = @$_GET["key1"];
$x_credito_id = @$_GET["key2"];
$x_formato2_id = @$_GET["key3"];

if (($x_credito_id == "") || ((is_null($x_credito_id)))) {
	ob_end_clean(); 
	echo "NO se localizaron los datos.";
	exit();
}

if($x_formato_id < 5){ //mora 1
	switch ($x_formato2_id)
	{
		case 1: // titular
			$x_formato_docto_id = 6;
			break;
		case 2: // aval
			$x_formato_docto_id = 7;		
			break;
		case 3: // asociados
			$x_formato_docto_id = 8;		
			break;			
	}
}else{ // mora 2
	switch ($x_formato2_id)
	{
		case 1: // titular
			$x_formato_docto_id = 9;		
			break;
		case 2: // aval
			$x_formato_docto_id = 10;		
			break;
		case 3: // asociados
			$x_formato_docto_id = 11;		
			break;			
	}
}


// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


//CREDITO
$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_solicitud_id = $row["solicitud_id"];
$x_tarjeta_numero = $row["tarjeta_num"];
$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
phpmkr_free_result($rs);
$x_fecha_otorgamiento = FechaLetras_normal(strtotime($x_fecha_otorgamiento),false);


//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_aval = $row["nombre_completo"];
phpmkr_free_result($rs);


//fecha limite pago (actual + 8 dias
$temptime = strtotime(ConvertDateToMysqlFormat($currdate_original));	
$temptime = DateAdd('w',8,$temptime);
$fecha_act = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
//Validar domingos
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_act);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_act = strftime('%Y-%m-%d',$temptime);
}
$fecha_act = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($fecha_act)),false);


//vencimiento
$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate_original)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$x_total_importe = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_dias_venc_ant = 0;
$x_contador = 0;

while($rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_importe = $rowwrk["importe"];
	$x_interes = $rowwrk["interes"];
	$x_interes_moratorio = $rowwrk["interes_moratorio"];
	$x_dias_venc = $rowwrk["dias_venc"];	

	if($x_dias_venc > $x_dias_venc_ant){
		$x_dias_venc_ant = $x_dias_venc;
		$x_fecha_venc = $rowwrk["fecha_vencimiento"];				
	}
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_total = $x_total_total + ($x_importe + $x_interes + $x_interes_moratorio);
	$x_contador++;
}
@phpmkr_free_result($rswrk);

$x_fecha_venc = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_venc)),false);;

$x_moratorio_letras = covertirNumLetras($x_total_moratorios);
$x_moratorios = "$".FormatNumber($x_total_moratorios,2,0,0,1)." (-$x_moratorio_letras-) ";

$x_total_letras = covertirNumLetras($x_total_total);
$x_total_pagar = "$".FormatNumber($x_total_total,2,0,0,1)." (-$x_total_letras-) ";


//pagos vigentes pendientes
$sSql = "SELECT count(*) as vig_pen FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_vigentes = $row["vig_pen"];
phpmkr_free_result($rs);

if($x_vigentes > 0){
	$x_parrafo_1 = "";
	$x_parrafo_2 = " para ponerse al corriente es de ".$x_total_pagar.".";	
}else{
	$x_parrafo_1 = "Le recordamos que el cr&eacute;dito se encuentra totalmente vencido desde el ".$fecha_act.".";
	$x_parrafo_2 = " para liquidar su deuda es de ".$x_total_pagar.".";		
}


//FORMATO
$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato_docto_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_contenido = $row["contenido"];
phpmkr_free_result($rs);

$x_contenido_back = $x_contenido;
$x_contenido_gral = "";

//ASOCIADOS
$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_inciso on solicitud_inciso.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_inciso.cliente_id Where credito.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
while($rowwrk = phpmkr_fetch_array($rswrk)) {

	if($rowwrk["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
	$x_titular = $x_saludo." ".$rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];						
	$x_usuario_id = $rowwrk["usuario_id"];


	//USUARIO
	$sSql = "SELECT * FROM usuario where usuario_id = $x_usuario_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_usuario = $row["usuario"];
	$x_clave = $row["clave"];
	phpmkr_free_result($rs);

	$x_contenido = str_replace("\$x_fecha_actual",$currdate,$x_contenido);
	$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
	$x_contenido = str_replace("\$x_titular",htmlentities($x_titular),$x_contenido);
	$x_contenido = str_replace("\$x_fecha_otorgamiento",$x_fecha_otorgamiento,$x_contenido);
	$x_contenido = str_replace("\$x_parrafo_1",$x_parrafo_1,$x_contenido);
	$x_contenido = str_replace("\$x_parrafo_2",$x_parrafo_2,$x_contenido);
	$x_contenido = str_replace("\$x_fecha_limite_pago",$fecha_act,$x_contenido);
	$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
	$x_contenido = str_replace("\$x_tarjeta_num",htmlentities($x_tarjeta_numero),$x_contenido);
	$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
	$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);

	$x_contenido_gral .= $x_contenido . "<p>&nbsp;</p><br>";
	
	$x_contenido = $x_contenido_back;

}
@phpmkr_free_result($rswrk);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carta de Mora</title>
</head>
<body bgcolor="#FFFFFF">
<?php 
echo $x_contenido_gral; 
?>	  
</body>
</html>
<?php
phpmkr_db_close($conn);
?>