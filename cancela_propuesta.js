// JavaScript Document
//guardar la referencia al objeto XMLHttpRequest 
var xmlHttp11 = createXmlHttpRequestObject(); 

// creacion del objeto   XMLHttpRequest
function createXmlHttpRequestObject() 
{	
  // guardar la referencia al objeto XMLHttpRequest 
 var xmlHttp11;
 
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp11=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp11=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp11=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp11;

    
}

// Hacer la peticion  HTTP asincrona usando el objeto  XMLHttpRequest
function CancelaPropuesta(convenio_propuesta_id) 	


{
	this.convenio_propuesta_id =  convenio_propuesta_id;
	alert("Cancela propuestas");
	
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp11.readyState == 4 || xmlHttp11.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	var url = "cancela_propuesta.php?x_convenio_propuesta_id="+convenio_propuesta_id+"";	
				
	url=url+"&sid="+Math.random();
	
    // generar la peticion del servidor
    xmlHttp11.open("GET",url, true);  
    //preparar la funcion  para procesar la repuesta
    xmlHttp11.onreadystatechange = handleServerResponse;
    // ejecutar peticion al servidor
    xmlHttp11.send(null);
  }
  else
    // si el objeto esta ocupado, intentar neuvamente despues de un segundo 
    setTimeout('process()', 1000);
}

//  funcion que se ejecuta cunado se recibe la respuesta del servidor
function handleServerResponse() 
{
  // verifica si la transaccion esta completa
  if (xmlHttp11.readyState == 4) 
  {
    // y si es correcta
    if (xmlHttp11.status == 200){ 
	 //explode en java script
	
	var respuesta = xmlHttp11.responseText;
	var res = respuesta.split("---");
	var r1 = res[0];
	var r2 = res[1];
	
	document.getElementById("capaCancelaPropuesta_"+convenio_propuesta_id+"").value = "";
	document.getElementById("capaCancelaPropuesta_"+convenio_propuesta_id+"").innerHTML=r1;
	
	document.getElementById("status_descripcion_"+convenio_propuesta_id+"").value = "";
	document.getElementById("status_descripcion_"+convenio_propuesta_id+"").innerHTML=r2;
   } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp11.statusText);
    }
  }
}

