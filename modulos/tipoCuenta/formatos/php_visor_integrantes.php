<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$x_credito_id = @$_GET["key"];
if(empty($x_credito_id)){
	$x_credito_id = @$_POST["x_credito_id"];
	if(empty($x_credito_id)){
		echo "No se locaizaron los comentarios del credito.";
		exit();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<style>
.body{
	margin-top:0px;
}
</style>
</head>
<body>
<form name="frm_visor" method="post" action="php_comentarios_visor.php">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>" />
<p align="left">
<span>
<?php 
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
	$sSqlWrk = "SELECT count(*) as comentarios FROM credito_comment Where credito_id = $x_credito_id ";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_comentarios = $rowwrk["comentarios"];
	@phpmkr_free_result($rswrk);
	if($x_comentarios > 0 ){
		echo "<a href='php_credito_commentedit.php?key=$x_credito_id' target='_blank'>Ver</a>";
	}else{
		echo "<a href='php_credito_commentadd.php?key=$x_credito_id' target='_blank'>Agregar</a>";
	}
?>        
</span>
</p>
</form>
</body>
</html>
