<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../error.php');
} else {
include ('../../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$baseHTML->setContentHeader ('Contrato ', 'Home/Contrato');


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


       
         <div class="row  ">
          <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            
         
       

                

                <div class="alert  bg-lightblue alert-dismissible">
                  <button type="button" class="close " data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h6 ><i class="icon fas fa-info"></i> Atencion!<h6>
                Para solicitar un crédito por favor realice los 5 pasos siguientes
                </div>
                </div>
           

         </div> 
         <div class="row">
          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box bg-info elevation-3">
              <div class="inner">
                <h2 class=" "><b>1</b></h2>

                <p><h6 class="lead"><b>Registra tus datos</b></h6></p>
              </div>
              <div class="icon ">
                <i class="fas fa-user-plus"></i>
              </div>
              <a href="cliente/" class="small-box-footer ">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>


          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box bg-info elevation-3">
              <div class="inner">
                <h2 class=" "><b>2</b></h2>

                <p><h6 class="lead"><b>Sube tus documentos</b></h6></p>
              </div>
              <div class="icon ">
                <i class="fas fa-upload"></i>
              </div>
              <a href="cliente/documentos/" class="small-box-footer ">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>             
        </div>
        <!-- /.row -->




        <div class="row pt-5">
          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box bg-grayligth elevation-3 bt-1">
              <div class="inner">
                <h2 class="text-info "><b>3</b></h2>

                <p><h6 class="text-info"><b>Descarga tu paquete</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-file-download"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>


          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box bg-grayligth elevation-3">
              <div class="inner">
                <h2 class="text-info "><b>4</b></h2>

                <p><h6 class="text-info"><b>Obtén tu NIP</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-key"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>    

          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box bg-grayligth elevation-3 a">
              <div class="inner">
                <h2 class="text-info "><b>5</b></h2>

                <p><h6 class=" text-info"><b>Firma tu contrato</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-file-signature"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>         
        </div>
        <!-- /.row -->





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