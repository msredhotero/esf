<?php

require 'class_include.php';
session_start();
$serviciosUsuario = new ServiciosUsuarios();
$ui = $_GET['token'];
$resActivacion = $serviciosUsuario->traerActivacionusuariosPorToken($ui);
$cadResultado = '';
$cadError = '';
$rowActivacion = $resActivacion->fetch_array(MYSQLI_ASSOC);

if ($resActivacion->num_rows > 0) {
	$idusuario = $rowActivacion['usuario_id'];

	// prolongo la activacion
	$resConcretar = $serviciosUsuario->modificarActivacionusuariosRenovada($idusuario,$ui,'','');
  $rowConcretar = $resActivacion->fetch_array(MYSQLI_ASSOC);

	$cadResultado = 'Vuelva intentar activar su usuario haciendo click <a href="activacionUsuario.php?token='.$ui.'">AQUI </a>!!';
} else {
  $cadError = 1;
	$cadResultado = '<b>Esta clave de activación es inexistente</b>';
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
    <header id="header">
  
    </header>

<main role="main" class="mx-auto row-cols-1 row-cols-sm-3 row-cols-md-3  row-cols-lg-2 row-cols-xl-2 ">  
    
    <div class="mx-auto pt-5">
        <div class="jumbotron">
            <h3 >Activacion de usuario</h3>
            <?php if (!$cadError) { ?>
            <p class="lead">Se extendió el tiempo para poder activar el usuario que registró
            <?php } ?>
            </p>
           
            <hr class="my-4">
            <div class="d-flex justify-content-center pb-3">         
                <p class="text-danger"> <?php echo $cadResultado; ?></p>
            </div>
        </div>
    </div> 
    

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
<script type="text/javascript">
  
  $(document).ready(function(){

    $('#header').load('header.html'); 
  });
</script>

 
</body>
</html>
