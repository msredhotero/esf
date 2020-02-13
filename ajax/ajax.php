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
   case 'verificarListaNegraOfacsimple':
      verificarListaNegraOfacsimple($serviciosReferencias);
   break;
}

function verificarListaNegraOfacsimple($serviciosReferencias) {
   $nombrecompleto =     $_POST['nombrecompleto'];

   if ($nombrecompleto == '') {
      $resV['datos'] = 'Debe ingresar un valor';
   } else {
      $resV['datos'] = 'No se encontraron datos';

      $res = $serviciosReferencias->verificarListaNegraOfacsimple($nombrecompleto);

      if ($res==1) {
         $resV['datos']= 'El cliente '.$nombrecompleto.' NO fue encuentrado/a en listas negras ';
      } else {
         $resV['datos']= 'El cliente '.$nombrecompleto.' SI fue encuentrado/a en listas negras ';
      }
   }


   header('Content-type: application/json');
   echo json_encode($resV);
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
   $clientereverso = str_replace(' ', '', trim($nombre.$apellidopaterno.$apellidomaterno));
   $proveedor = str_replace(' ', '', trim($proveedor));
   $beneficiario = str_replace(' ', '', trim($beneficiario));

   $res = $serviciosReferencias->verificarListaNegraOfac($cliente,$proveedor,$beneficiario,$clientereverso);

   if ($res['cliente']==1) {
      $resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' NO fue encuentrado/a en listas negras ';
   } else {
      $resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' SI fue encuentrado/a en listas negras ';
      $resV['error'] = true;
   }

   if ($res['proveedor']==1) {
      $resV['datosproveedor'] = 'El proveedor '.$_POST['proveedor'].' NO fue encuentrado/a en listas negras ';
   } else {
      $resV['datosproveedor'] = 'El proveedor '.$_POST['proveedor'].' SI fue encuentrado/a en listas negras ';
      $resV['error'] = true;
   }

   if ($res['beneficiario']==1) {
      $resV['datosbeneficiario'] = 'El beneficiario '.$_POST['beneficiario'].' NO fue encuentrado/a en listas negras ';
   } else {
      $resV['datosbeneficiario'] = 'El beneficiario '.$_POST['beneficiario'].' SI fue encuentrado/a en listas negras ';
      $resV['error'] = true;
   }

   header('Content-type: application/json');
   echo json_encode($resV);


}




?>