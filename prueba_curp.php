<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<? 

/** 
 * @author Victor Arturo Hernandez Avila 
 * @mail arturo[dot]webrek[at]gmail[dot]com 
 * @copyright 2009 
 */ 
  $x_curp = getCurp("ortiz", "anzurez", "zulma", 15, 06,1984,"m","ms" );
  echo "<br>la curp es".$x_curp;
set_time_limit(0); 
function getCurp($primerApellido, $segundoApellido, $nombre, $diaNacimiento, $mesNaciemiento, $anioNacimiento, $sexo, $entidadNacimiento){ 
$primerApellido = urlencode($primerApellido); 
$segundoApellido = urlencode($segundoApellido); 
$nombre = urlencode($nombre); 
$aContext = array( 
    'http' => array( 
        'header'=>"Accept-language: es-es,es;q=0.8,en-us;q=0.5,en;q=0.3\r\n" . 
             # "Proxy-Connection: keep-alive\r\n" . 
              "Host: consultas.curp.gob.mx\r\n" . 
              "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; es-ES; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 (.NET CLR 3.5.30729)\r\n" . 
              "Keep-Alive: 300\r\n" . 
              "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" 
              //, 'proxy' => 'tcp://proxy:puerto', //Si utilizas algun proxy para salir a internet descomenta esta linea y por la direccion de tu proxy y el puerto 
              //'request_fulluri' => True //Tambien esta si utilizas algun proxy 

        ), 
    ); 


if(  function_exists(stream_context_create) ){
	echo "existe";
	}else {
		echo "no existe";
		}
$cxContext = stream_context_create($aContext); 
echo "str".$cxContext."<br>";
$url = "http://consultas.curp.gob.mx/CurpSP/curp1.do?strPrimerApellido=$primerApellido&strSegundoAplido=$segundoApellido&strNombre=$nombre&strdia=$diaNacimiento&strmes=$mesNaciemiento&stranio=$anioNacimiento&sSexoA=$sexo&sEntidadA=$entidadNacimiento&rdbBD=myoracle&strTipo=A&entfija=DF&depfija=04"; 

/*
if(  function_exists(file_get_contents) ){
	echo "existe file .............";
	}else {
		echo "no existe";
		}*/
echo "URL---------".$url;


    $file = file_get_contents($url,false, NULL); 
    preg_match_all("/var strCurp=\"(.*)\"/", $file, $curp); 
$curp = $curp[1][0]; 
    if($curp){ 
        return $curp; 
    }else{ 
        $curp = "Curp no encontrado."; 
        return $curp; 
    } 
} 
?> 
<script type="text/javascript"> 
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); 
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E")); 
</script> 
<script type="text/javascript"> 
try { 
var pageTracker = _gat._getTracker("UA-15073642-1"); 
pageTracker._setDomainName("none"); 
pageTracker._setAllowLinker(true); 
pageTracker._trackPageview(); 
} catch(err) {}
</script> 


</body>
</html>