// JavaScript Document
//guardar la referencia al objeto XMLHttpRequest 
var xmlHttp2 = createXmlHttpRequestObject(); 

// creacion del objeto   XMLHttpRequest
function createXmlHttpRequestObject() 
{	
  // guardar la referencia al objeto XMLHttpRequest 
 var xmlHttp2;
 
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp2=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp2=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp2=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp2;

    
}

// Hacer la peticion  HTTP asincrona usando el objeto  XMLHttpRequest
function ConvenioOrden(convenio_orden_id) 	


{
	this.convenio_orden_id =  convenio_orden_id;
	
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp2.readyState == 4 || xmlHttp2.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	var url = "concilia_convenio_orden.php?x_convenio_orden_id="+convenio_orden_id+"";	
				
	url=url+"&sid="+Math.random();
	
    // generar la peticion del servidor
    xmlHttp2.open("GET",url, true);  
    //preparar la funcion  para procesar la repuesta
    xmlHttp2.onreadystatechange = handleServerResponse;
    // ejecutar peticion al servidor
    xmlHttp2.send(null);
  }
  else
    // si el objeto esta ocupado, intentar neuvamente despues de un segundo 
    setTimeout('process()', 1000);
}

//  funcion que se ejecuta cunado se recibe la respuesta del servidor
function handleServerResponse() 
{
  // verifica si la transaccion esta completa
  if (xmlHttp2.readyState == 4) 
  {
    // y si es correcta
    if (xmlHttp2.status == 200){ 
	 //explode en java script
	document.getElementById("capaConvenioOrden_"+convenio_orden_id+"").value = "";
	document.getElementById("capaConvenioOrden_"+convenio_orden_id+"").innerHTML=xmlHttp2.responseText;
   } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp2.statusText);
    }
  }
}

