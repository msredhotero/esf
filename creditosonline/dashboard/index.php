<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../error.php');
} else {
include ('../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$usuario = new Usuario();
$nombre_usuario = ucwords($usuario->getNombre());

$baseHTML->setContentHeader ('Home ', 'Home');


}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> CRM | Financiera CREA </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Font Awesome -->  
  <!-- Ionicons -->  
  <!-- Theme style -->  
  <!-- Google Font: Source Sans Pro -->
  <?php echo $baseHTML->getCssAdminLTE(); ?>
 
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed control-sidebar-push ">
<div class="wrapper">
  <!-- Navbar -->
  
  <!-- Navbar -->
  <?php echo $baseHTML->getNavBar(); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container --> 
  <?php echo $baseHTML->getSideBar(); ?>
  <!-- / sidebar Container -->





  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <?php echo $baseHTML->getContentHeader (); ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

		

     <div class="card card-danger card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-home"></i>
              Inicio
            </h3>
          </div>
          <div class="card-body p-5">
            <h3 class="lead"><b>Bienvenido a Financiera CREA!</b></h3>
            <p>
            <div>
              <div>
                Estimado(a)  <?php echo $nombre_usuario ;?>
                <p>
              </div>
              <p>
              Para iniciar el trámite de solicitud de <b>crédito en línea</b> por favor diríjase al menú principal que se encuentra del lado izquierdo de la pantalla, seleccione la opción <span class="text-primary">Contrato</span> y  siga las indicaciones ahí dispuestas.
              
            </div>
          </div>
      </div>
       
         





      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   
  <?php echo $baseHTML->getFooter(); ?> 

  <!-- Control Sidebar --> 
  <?php echo $baseHTML->getControlSideBar(); ?> 
  <!-- /.control-sidebar -->
 

</div>
<!-- ./wrapper -->
<!-- jQuery -->
<!-- Bootstrap 4 -->
<!-- AdminLTE App -->
 <?php echo $baseHTML->getJsAdminLTE(); ?> 


<script type="text/javascript">
$(document).ready(function () {
  //bsCustomFileInput.init();
});
</script>
</body>
</html>