<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

$x_galeria_fotografica_id = $_GET["x_galeria_fotografica_id"];
$id = $x_galeria_fotografica_id;
//echo "id".$id."<br>";

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);



$sqlS = "SELECT  *  from galeria_fotografica  WHERE galeria_fotografica_id = $id ";
	
	$rsS = phpmkr_query($sqlS, $conn) or die("Error al seleccionar IFE". phpmkr_error()."sql:".$sqlS);
$row = mysql_fetch_assoc($rsS);
$x_cadena_fotografica = "";
//print_r($row);
echo "<br><br>";
$x_nombre_galeria = $row["nombre_galeria"];
$x_tipo_galeria = $row["tipo_galeria_id"];
if($x_tipo_galeria == 1){
	$x_tipo_galeria = "CLIENTE";
	}else{
		$x_tipo_galeria = "AVAL";
		}
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
array_shift($row);
//print_r($row);
$x_slide =1;
foreach($row as $campo => $valor){
	
	$nombre_galeria = str_replace ("_" ," ", $campo);
	$nombre_pic = str_replace("_"," ", $valor);
	if(strlen($valor) >5){
		// forma parte de la cadena
		//echo "<br>".$campo."----> ".$valor." --<br>";
		$x_cadena_fotografica .= "<div class=\"slidePanel panel_".$x_slide."\" caption=\"".$x_slide."\">
						<div class=\"innerPanel\">
							<img src=\"".ewUploadPath(0) .$valor."\" alt=\"".$nombre_pic."\" title=\"".$nombre_galeria."\" />
						</div>
					</div>";
			
		$x_slide ++;
		}
	
	}


	
	
	
?>
<?php include ("header.php") ?>

<title>jQuery Slideshow for variable sized information panels </title>
<link rel="stylesheet" media="all" type="text/css" href="stu12/stu12.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="stu12/stu12.js"></script>
<table align="center" width="90%">
<tr>
  <td> <a href="php_galeria_fotograficalist.php">Regresar a la lista</a>&nbsp;<a href="php_galeriaviewJQ.php?x_galeria_fotografica_id=<?php echo $id?>">Ver la galeria completa </a> &nbsp;<a href="php_galeriaview.php?x_galeria_fotografica_id=<?php echo $id?>"> Ver con la galeria anterior</a></td></tr>
<tr><td>	<h2>GALERIA FOTOGR&Aacute;FICA <?php echo $x_nombre_galeria;?></h2></td></tr>
 <tr><td>   <h3><?php echo $x_tipo_galeria;?></h3></td></tr></table>
	<ul class="iStu12">
		<li class="prev"></li>
		<li class="images">
			<div class="slide">
					
					<?php echo  $x_cadena_fotografica;?>
					
			</div> <!--slide class-->
		</li>
		<li class="next"></li>
		<li class="caption"></li>
	</ul>

	

<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
