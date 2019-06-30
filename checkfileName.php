<?php

$file = basename( $_GET['file'] ); 
if( file_exists( "upload/$file" ) ) 
    echo "YES"; 
else 
    echo "NO"; 
	
?>