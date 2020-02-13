<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0
?>

<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("header.php") ?>
<script type="text/javascript" src="bootstrap3/js/jquery.min.js"></script>
<link href="bootstrap3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="bootstrap3/js/bootstrap.min.js"></script>


<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
?>
<p><span class="phpmaker">Ingrese el Nombre Completo para buscar en las listas negras</span></p>


<div class="row" style="padding-left:5%;">
   <form class="form" id="frmBusqueda">

      <div class="row">


         <div class="form-group col-md-6">
            <label class="control-label" style="text-align:left" for="torneo">Busqueda</label>
            <div class="input-group col-md-12">
               <input type="text" name="busqueda" id="busqueda" class="form-control">
            </div>
         </div>

         <div class="form-group col-md-6">
            <ul class="list-inline" style="margin-top:15px;">
               <li>
                  <button id="buscar" class="btn btn-primary" style="margin-left:0px;" type="button">Buscar</button>
               </li>
            </ul>
         </div>

         <div class="form-group col-md-12">
            <div class="cuerpoBox" id="resultados">

            </div>
         </div>

      </div>
   </form>
</div>

<script>
	$(document).ready(function(){


		$('#buscar').click(function() {
			$.ajax({
				data:  {
					nombrecompleto: $('#busqueda').val(),
					accion: 'verificarListaNegraOfacsimple'},
				url:   'ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
               $('#resultados').html('');
				},
				success:  function (response) {
					$('#resultados').html(response.datos);

				}
			});
		});

	});
</script>

<br>
<?php include ("footer.php") ?>
