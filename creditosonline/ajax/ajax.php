<?php
/*include ('../includes/class/Conexion.inc.php');
include ('../includes/class/Query.class.php');
include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesNotificaciones.php');
include ('../includes/funcionesMensajes.php');
include ('../includes/validadores.php');
include ('../includes/funcionesSolicitudes.php');*/

include'../class_include.php';


$dbQuery = new Query();
$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias		= new ServiciosReferencias();
$serviciosNotificaciones	= new ServiciosNotificaciones();
$serviciosMensajes			= new ServiciosMensajes();
$serviciosValidador         = new serviciosValidador();
$serviciosSolicitudes       = new ServiciosSolicitudes();


$accion = $_POST['accion'];


$resV['error'] = '';
$resV['mensaje'] = '';
$datosPost = array();
$datosPost = $_POST;

date_default_timezone_set('America/Mexico_City');
#print_r($_POST);

switch ($accion) {
    case 'login':
        validarAcceso($serviciosUsuarios);
        break;
    case 'register':
        RegistrarUsuario($serviciosUsuarios);
        break;
    case 'solicitarCambioClave':
    	solicitarCambioClaveUsuario($serviciosUsuarios);
    	break;
    case 'actualizarClave':
    	cambiarClaveUsuario($serviciosUsuarios);
    	break;	    	 
	case 'insertarSolContGlobal':
		insertaNuevaSolicitudGlobal($serviciosSolicitudes);
		break;
	case 'editarSolContGlobal':
		editaSolicitudGlobal($serviciosSolicitudes);
		break;
	case 'editarDocumentos':
		guardaDocumentosSolicitudGlobal($serviciosSolicitudes);
		break;	
	default:
		print	"No teien accion". $accion. "**";	

}
/* Fin */
#print_r($_POST);

function validarAcceso($serviciosUsuarios) {
	$email		=	$_POST['usuario'];
	$pass		=	$_POST['clave'];
	//$idempresa  =	$_POST['idempresa'];
	echo $serviciosUsuarios->login($email,$pass);
}

function registrarUsuario($serviciosUsuarios){

	$usuario = $_POST["usuario"];
	$password = $_POST["clave"];
	$rol = 8; // cliente
	$email = $_POST["usuario"];
	$nombrecompleto = $_POST["nombre"];
	$res = $serviciosUsuarios->insertarUsuario($usuario,$password,$rol,$email,$nombrecompleto);
	echo $res;
}

function solicitarCambioClaveUsuario($serviciosUsuarios){
	$usuario = $_POST["usuario"];
	$res = $serviciosUsuarios->cambioClaveUsuario($usuario);
	echo $res;
}


function cambiarClaveUsuario($serviciosUsuarios){
	$token = $_POST['token'];
	$password = $_POST['clave'];
	$res = $serviciosUsuarios->actualizaPassword($token, $password);

	echo $res;
}


function insertaNuevaSolicitudGlobal($serviciosSolicitudes){
	// insertamos los datos de la nueva solicitud en la base
	$res = $serviciosSolicitudes->insertarSolicitudGlobal();
	echo $res;	
	//return $res["error"];
	}

function editaSolicitudGlobal($serviciosSolicitudes){
	// insertamos los datos de la nueva solicitud en la base
	
	$res = $serviciosSolicitudes->editarSolicitudGlobal();
	echo $res;	
	//return $res["error"];
	}	

function guardaDocumentosSolicitudGlobal($serviciosSolicitudes){
	$res = $serviciosSolicitudes->subirDocumentosSolicitudGlobal();	
	echo $res;
}	


function devolverImagen($nroInput) {

	if( $_FILES['archivo'.$nroInput]['name'] != null && $_FILES['archivo'.$nroInput]['size'] > 0 ){
	// Nivel de errores
	  error_reporting(E_ALL);
	  $altura = 100;
	  // Constantes
	  # Altura de el thumbnail en píxeles
	  //define("ALTURA", 100);
	  # Nombre del archivo temporal del thumbnail
	  //define("NAMETHUMB", "/tmp/thumbtemp"); //Esto en servidores Linux, en Windows podría ser:
	  //define("NAMETHUMB", "c:/windows/temp/thumbtemp"); //y te olvidas de los problemas de permisos
	  $NAMETHUMB = "c:/windows/temp/thumbtemp";
	  # Servidor de base de datos
	  //define("DBHOST", "localhost");
	  # nombre de la base de datos
	  //define("DBNAME", "portalinmobiliario");
	  # Usuario de base de datos
	  //define("DBUSER", "root");
	  # Password de base de datos
	  //define("DBPASSWORD", "");
	  // Mime types permitidos
	  $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  // Variables de la foto
	  $name = $_FILES["archivo".$nroInput]["name"];
	  $type = $_FILES["archivo".$nroInput]["type"];
	  $tmp_name = $_FILES["archivo".$nroInput]["tmp_name"];
	  $size = $_FILES["archivo".$nroInput]["size"];
	  // Verificamos si el archivo es una imagen válida
	  if(!in_array($type, $mimetypes))
		die("El archivo que subiste no es una imagen válida");
	  // Creando el thumbnail
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  $img = imagecreatefromjpeg($tmp_name);
		  break;
		case $mimetypes[2]:
		  $img = imagecreatefromgif($tmp_name);
		  break;
		case $mimetypes[3]:
		  $img = imagecreatefrompng($tmp_name);
		  break;
	  }

	  $datos = getimagesize($tmp_name);

	  $ratio = ($datos[1]/$altura);
	  $ancho = round($datos[0]/$ratio);
	  $thumb = imagecreatetruecolor($ancho, $altura);
	  imagecopyresized($thumb, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  imagejpeg($thumb, $NAMETHUMB);
			  break;
		case $mimetypes[2]:
		  imagegif($thumb, $NAMETHUMB);
		  break;
		case $mimetypes[3]:
		  imagepng($thumb, $NAMETHUMB);
		  break;
	  }
	  // Extrae los contenidos de las fotos
	  # contenido de la foto original
	  $fp = fopen($tmp_name, "rb");
	  $tfoto = fread($fp, filesize($tmp_name));
	  $tfoto = addslashes($tfoto);
	  fclose($fp);
	  # contenido del thumbnail
	  $fp = fopen($NAMETHUMB, "rb");
	  $tthumb = fread($fp, filesize($NAMETHUMB));
	  $tthumb = addslashes($tthumb);
	  fclose($fp);
	  // Borra archivos temporales si es que existen
	  //@unlink($tmp_name);
	  //@unlink(NAMETHUMB);
	} else {
		$tfoto = '';
		$type = '';
	}
	$tfoto = utf8_decode($tfoto);
	return array('tfoto' => $tfoto, 'type' => $type);
}


?>
