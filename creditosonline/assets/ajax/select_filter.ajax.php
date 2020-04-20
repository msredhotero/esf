<?php 
header('Content-Type: application/json');

include '../../class_include.php';

$rqs = json_decode(file_get_contents('php://input'));
 //echo '<pre>'.var_export($rqs, true).'</pre>';

$a_ajax = array();

$usuario = new Usuario();
$query = new Query();

$gen_options = new Options('', '');
$filtros = array();
$cat_nombre = $rqs->cat_nombre;
$id_cat = $rqs->id_cat;
$descripcion = (isset($rqs->descripcion))?$rqs->descripcion:'descripcion';

if(isset($rqs->filtros)){
	foreach ($rqs->filtros as $filtro) {
// 		echo '<pre>'.var_export($filtro, true).'</pre>';
		$filtros[] = get_object_vars($filtro);
	}
}

 #echo '<pre>'.var_export($filtros, true).'</pre>';
 #echo '<pre>'.$_REQUEST['filtros'].'</pre>';

$config = array(
	'cat_nombre'=>$cat_nombre,
	'filtros'=>$filtros,
	'id_cat'=>$id_cat,
	'descripcion'=>$descripcion
);

$options = $gen_options->get_options($config);

$a_ajax = array(
	'options'=>$options,
	'spc'=>implode(',', $gen_options->get_data_spc()),
);

echo json_encode($a_ajax);

$mysqli->close();
?>