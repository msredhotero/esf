   
      <?php /* Introducimos el nombre del key provisto por hacienda y su respectivo password, al igual que el nombre del archivo de salida PEM */
  
      shell_exec(' pkcs8 -inform DER -in gfo090825gv3_1103162027.key -out gfo090825gv3_1103162027.key.pem -passin pass:aose790113');
	  
	  //pkcs8 -inform DER -in gfo090825gv3_1103162027.key -out gfo090825gv3_1103162027.key.pem -passin pass:aose790113
	  
	  ?>