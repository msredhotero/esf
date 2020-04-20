<?php

require 'class_include.php';
session_start();

$serviciosUsuario = new ServiciosUsuarios();
$serviciosReferencias = new ServiciosReferencias();
$serviciosFunciones 	= new Servicios();

if (!isset($_GET['token'])) {
	$ui = 'asda23asd23asd';
} else {
	$ui = $_GET['token'];
}

$resActivacion = $serviciosUsuario->traerActivacionusuariosPorTokenFechas($ui);
$rowActivacion = $resActivacion->fetch_array(MYSQLI_ASSOC);


if ($resActivacion->num_rows > 0) {


	$idusuario = $rowActivacion['refusuarios'];
	$resUsuario = $serviciosUsuario->traerUsuarioId($idusuario);  
  $rowUser = $resUsuario->fetch_array(MYSQLI_ASSOC);
	// verifico que el usuario no este activo ya
	if ($rowUser['activo'] == 'Si') {
		$arResultado['leyenda'] = 'Usted ya fue dado de alta y esta activo.';
		$arResultado['activo'] = 1;
	} else {
		$arResultado['usuario'] = $rowUser['nombre'];		
		$arResultado['activo'] = 1;
	}

	

	//pongo al usuario $activo
	$resUsuario = $serviciosUsuario->activarUsuario($idusuario);

	// concreto la activacion
	//$resConcretar = $serviciosUsuario->eliminarActivacionusuarios(mysql_result($resActivacion,0,0));


} else {

	$resToken = $serviciosUsuario->traerActivacionusuariosPorToken($ui);  
  $rowToken = $resToken->fetch_array(MYSQLI_ASSOC);
 
	if ($resToken->num_rows > 0) {

		$arResultado['leyenda'] = 'La vigencia para darse de alta ha caducado, haga click <a href="extenderToken.php?token='.$ui.'"> &nbsp; AQUI &nbsp; </a> para prolongar la activación';
		$arResultado['activo'] = 0;
	} else {
		$arResultado['leyenda'] = 'Esta clave de Activación es inexistente';
		$arResultado['activo'] = 0;
	}

}



?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Fiananciera CREA</title> 
 
  <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap_4/dist/css/bootstrap.css" rel="stylesheet">
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />   
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">


  <!-- ALERTAS -->  
  <meta name="theme-color" content="#563d7c">
  <style>

      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }


        .jumbotron {
  padding-top: 3rem;
  padding-bottom: 3rem;
  margin-bottom: 0;
  background-color: #fff;
}
@media (min-width: 768px) {
  .jumbotron {
    padding-top: 6rem;
    padding-bottom: 6rem;
  }
}

.jumbotron p:last-child {
  margin-bottom: 0;
}

.jumbotron h1 {
  font-weight: 300;
}

.jumbotron .container {
  max-width: 40rem;
}

footer {
  padding-top: 3rem;
  padding-bottom: 3rem;
}

footer p {
  margin-bottom: .25rem;
}
      }
    </style>

    <!-- Custom styles for this template -->
    <!--<link href="css/stylejj.css" rel="stylesheet">
   -->
    
  </head>
  <body class="bg-light">
    <header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">Acerca de nosotros</h4>
          <p class="text-muted text-justify">Financiera CREA es una empresa constituida en abril de 2007 como una SOFOM ENR, dedicada durante sus primeros años, al otorgamiento de créditos productivos y que se mantiene en constante crecimiento.<br>

      Al día de hoy dedicada al otorgamiento de créditos individuales destinados a invertir en capital de trabajo.<br>

      Somos una empresa comprometida con la sociedad mexicana. Brindamos oportunidades de crecimiento a través de apoyos financieros a personas comprometidas con el trabajo, el esfuerzo, y con fuertes intenciones de crecer su patrimonio.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <address>
            <h4 class="text-white">Contactanos</h4>
          </address>
          <h6 class="text-white">Unidad especializada de atención al cliente:</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white">(55) 51350259</a></li>
            <li><a href="#" class="text-white">(01800) 8376133</a></li>
            <li><a href="#" class="text-white">Enviar correo</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-danger shadow-sm">
    <div class="container d-flex justify-content-between">
      <a href="#" class="navbar-brand d-flex align-items-center">
        
        <strong>Financiera CREA</strong>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>

<main role="main" class="mx-auto row-cols-1 row-cols-sm-3 row-cols-md-3  row-cols-lg-2 row-cols-xl-2 ">  
	<?php if($arResultado['activo']){ ?>
	<div class="mx-auto pt-5">
		<div class="jumbotron">
			<h3 >Usuario activado!</h3>
			<p class="lead">Su usuario fue activado exitosamente</p>
			<p> <?php echo $arResultado['leyenda'] ;?></p>
			<hr class="my-4">
			<div class="d-flex justify-content-center pb-3">         
       			<a href="index.html" class="alert-link">Inicia sesión</a>
      		</div>
		</div>
	</div> 
	<?php }else{ ?>
		<div class="mx-auto pt-5">
		<div class="jumbotron">
			<h3  class="text-danger">Error en la activación</h3>
			<p class="lead">Su usuario no se activo </p>
			<hr class="my-4">
			<div class="d-flex justify-content-center pb-3">         
       			<?php echo $arResultado['leyenda'] ;?>
      		</div>
		</div>
	</div> 

	<?php } ?>

</main>
<br><br>
<footer class="text-muted bg-white">
  <div class="container">
    <p class="float-right">
      <a href="#">Financieracrea.com</a>
    </p>   
    <p>&copy; 2020 Financiera CREA</p>
  </div>
</footer>
<!-- Jquery Core Js -->
<script src="plugins/jquery/jquery.min.js"></script>
<!--Our bootstrap.bundle.js and bootstrap.bundle.min.js include Popper, but not jQuery.--> 
<script src="plugins/bootstrap_4/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="plugins/BValidator/dist/js/bootstrapValidator.js"></script> 
</body>
</html>