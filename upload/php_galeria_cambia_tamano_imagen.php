<?php

 function cambiaImagen($x_imagen_origen){
	 $x_detst = $x_imagen_origen;
	$x_img_origen = imagecreatefromjpeg($x_imagen_origen);  // creamos una imagen 
	$x_ancho_origen  = imagesx($x_img_origen);// sacamos el ancho
	$x_alto_origen = imagesy($x_img_origen);// sacamos el alto
	
	
	$x_ancho_limite = 659;
	$x_alto_limite = 449;
	if($x_ancho_origen > $x_ancho_limite ||  $x_alto_origen > $x_alto_limite ){
	if( $x_ancho_origen > $x_alto_origen){ // es una imagen horizontal
	
		$x_ancho_origen = $x_ancho_limite;
		$x_alto_origen = $x_ancho_limite * imagesy($x_img_origen)/ imagesx($x_img_origen);

			
		}else{// es una imagen vertical
			$x_alto_origen = $x_alto_limite;
			$x_ancho_origen = $x_alto_limite * imagesx($x_img_origen) / imagesy($x_img_origen);

			} // es una imagen horizontal
				 
	 $x_image_destino = imagecreatetruecolor($x_ancho_origen, $x_alto_origen ); // se crea la image segun las dimeciones calculadas
	 imagecopyresized($x_image_destino, $x_img_origen,0,0,0,0, $x_ancho_origen,$x_alto_origen, imagesx($x_img_origen), imagesy($x_img_origen));	 
	 imagejpeg($x_image_destino,$x_imagen_origen);// se guarda  la nueva imagen con el nombre de la imagen anterior para sobreescribirla
	}
	
	
	 }// fin de la funcion


?>