<?php


include ('../includes/funcionesReferencias.php');

$serviciosReferencias		= new ServiciosReferencias();

$accion = $_POST['accion'];

$resV['error'] = '';
$resV['mensaje'] = '';

date_default_timezone_set('America/Mexico_City');



switch ($accion) {
   case 'verificarListaNegraOfac':
      verificarListaNegraOfac($serviciosReferencias);
   break;
}

function verificarListaNegraOfac($serviciosReferencias) {
   $apellidopaterno     =     $_POST['apellidopaterno'];
   $apellidomaterno     =     $_POST['apellidomaterno'];
   $nombre              =     $_POST['nombre'];
   $proveedor           =     $_POST['proveedor'];
   $beneficiario        =     $_POST['beneficiario'];

   $resV['datoscliente'] = '';
   $resV['datosproveedor'] = '';
   $resV['datosbeneficiario'] = '';
   $resV['error'] = false;

   $cadResultado = '';

   $cliente = str_replace(' ', '', trim($apellidopaterno.$apellidomaterno.$nombre));
   $proveedor = str_replace(' ', '', trim($proveedor));
   $beneficiario = str_replace(' ', '', trim($beneficiario));

   $res = $serviciosReferencias->verificarListaNegraOfac($cliente,$proveedor,$beneficiario);

   if ($res['cliente']) {
      $resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' NO se encuentra en las listas negras \n\r';
   } else {
      $resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' SI se encuentra en las listas negras \n\r';
      $resV['error'] = true;
   }

   if ($res['proveedor']) {
      $resV['datosproveedor'] = 'El proveedor '.$_POST['proveedor'].' NO se encuentra en las listas negras';
   } else {
      $resV['datosproveedor'] = 'El proveedor '.$_POST['proveedor'].' SI se encuentra en las listas negras';
      $resV['error'] = true;
   }

   if ($res['beneficiario']) {
      $resV['datosbeneficiario'] = 'El beneficiario '.$_POST['beneficiario'].' NO se encuentra en las listas negras';
   } else {
      $resV['datosbeneficiario'] = 'El beneficiario '.$_POST['beneficiario'].' SI se encuentra en las listas negras';
      $resV['error'] = true;
   }

   header('Content-type: application/json');
   echo json_encode($resV);


}




?>
