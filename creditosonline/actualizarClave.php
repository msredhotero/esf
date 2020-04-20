<?php 
$tk = $_GET['token'];
if(empty($tk)){
  $tk = 'TOKENINVALIDO';
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
<div class="container bg-light d-none d-sm-block">  
  <section class="w-100 justify-content-center p-3">
  <div class="row justify-content-center text-center">
       <!--  <img src='imagenes/arbolCreabgliht.png' class="rounded"> -->
      </div>
</section>

</div>
<main role="main">  
  <section class="bg-light ">
    <div class="container col-12 col-sm-10 col-md-6  col-lg-4 col-xl-4 py-sm-2 ">
      <div class="card rounded shadow-sm "> 
            
        <h2 class="text-center  text-muted pt-3 "><small>Actualizar contraseña</small></h2>         
        <hr>
        <div class="body">
           <form id="clave_form" class="clave_form" method="POST">
            <div class="text-muted">
              
            </div>

            <div class="form-group px-2 pt-4">
            <h6 id="usuarioHelp" class="form-text ">Usuario</h6>
            <div class="input-group flex-nowrap">              
              <?php echo $usuario;?>            
            </div>
            <span class="text-info h5"><small></small></span>
          </div>  
           
          <div class="form-group px-2">
            <h6 id="usuarioHelp" class="form-text ">Contraseña nueva</h6>
            <div class="input-group flex-nowrap">              
              <input type="password" id='clave' name='clave' value="" class="form-control" placeholder="Contraseña" aria-label="password" aria-describedby="addon-wrapping">
            </div>  
            <div class="text-info h5"><small>Por lo menos 6 caracteres</small></div>          
          </div>

          <div class="form-group px-2">
            <h6 id="usuarioHelp" class="form-text ">Repetir contraseña nueva</h6>
            <div class="input-group flex-nowrap">              
              <input type="password" id='clave2' name='clave2' value="" class="form-control" placeholder="Contraseña" aria-label="password" aria-describedby="addon-wrapping">
            </div>  
            <div class="text-info h5"><small>Ingresa la misma contraseña</small></div>          
          </div>

          <div class="form-group">
            <input type="hidden" name="accion" id="accion" value="actualizarClave" > 
            <input type="hidden" name="token" id="token" value="<?php echo $tk ;?>" >       
          </div>

         
           
         <div class="form-group d-flex justify-content-center pt-3">
          <input class="btn btn-primary float-none"  type="submit" id="cambiarPassword" value="Actualizar">
         </div>
            

           </form>

          <hr>

        </div>
        
      <div class="d-flex justify-content-center pt-2">
         <h6 class="text-muted d-block " id="mensg_incia_sesion"> No deseo cambiar mi contraseña</h6>
      </div>  
      <div class="d-flex justify-content-center pb-3">         
        <a href="index.html" class="alert-link">Inicia sesión</a>
      </div>

      
     
      
          
       
    </div>
      </div>
      
      
    </div>
  </section>
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
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->

<!-- Jquery Core Js -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap_4/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="plugins/BValidator/dist/js/bootstrapValidator.js"></script>
 
<!-- SweetAlert Plugin Js -->
<script src="plugins/sweetalert/sweetalert.min.js"></script>



<script type="text/javascript">

  $(document).ready(function(){
    $('#header').load('header.html');

    $('body').keyup(function(e) {
                if(e.keyCode == 13) {
                    $("#login").click();
                }
            });  


    $('.clave_form').bootstrapValidator({ 
  live: 'enabled',
  message: 'Valor invalido',
  feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
  submitButton: '$user_fact_form button[type="submit"]',
 
 
  fields: { 
            clave: {
                message: 'Contraseña Invalida',
                validators: {
                    notEmpty: {
                        message: 'Requerido'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'Contraseña Invalida'
                    },
                     identical: {
                        field: 'clave2',
                        message: 'Repita esta contraseña en el campo siguiente'
                    },                 
                }
            },

            clave2: {
                message: 'Contraseña Invalida',
                validators: {
                    notEmpty: {
                        message: 'Requerido'
                    },
                    identical: {
                        field: 'clave',
                        message: 'Las contraseñas no coinciden'
                    },
                    different: {
                        field: 'usuario',
                        message: 'La contraseña debe ser diferente al usuario'
                    },
                    different: {
                        field: 'nombre',
                        message: 'La contraseña debe ser diferente al nombre'
                    }             
                }
            }
                      
        }
})
.on('error.form.bv', function(e) {      
      console.log('error.form.bv');
      // You can get the form instance and then access API
      var $form = $(e.target);
      console.log($form.data('bootstrapValidator').getInvalidFields());
    })

.on('success.form.bv', function(e, data) {
    // Prevent form submission
    e.preventDefault();
    actualizarClave();
});


function actualizarClave(){
  
  var formData = new FormData($(".clave_form")[0]);
  var message = "";
 $.ajax({
    url:   'ajax/ajax.php',
    data: formData,
    type:  'post',   
    processData: false,
    contentType: false,
     beforeSend: function () {
      $("#load").html('<img src="imagenes/load13.gif" width="50" height="50" />');
    },
    success:  function (response) {
      if (isNaN(response)) {
        swal({
          title: "Respuesta",
          text: "Error al actualizar la contraseña "+response,
          type: "error",
          timer: 5000,
          showConfirmButton: false
        });
      } else {
        swal({
          title: "Respuesta",
          text: "Contraseña actualizada, ya puede iniciar sesión",
          type: "success",
          timer: 5000,
          showConfirmButton: false
        });      
        //url = "registroExitoso.html";
        setTimeout(function(){
          location.reload();
        },5000); 

        
      }
    }
  }); 
}
});/* fin del document ready */


    </script>
</body>
</html>       