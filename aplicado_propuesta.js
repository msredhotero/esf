// JavaScript Document
//guardar la referencia al objeto XMLHttpRequest 
var xmlHttp10 = createXmlHttpRequestObject(); 

// creacion del objeto   XMLHttpRequest
function createXmlHttpRequestObject() 
{	
  // guardar la referencia al objeto XMLHttpRequest 
 var xmlHttp10;
 
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp10=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp10=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp10=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp10;

    
}

// Hacer la peticion  HTTP asincrona usando el objeto  XMLHttpRequest
function ConvenioAplicado(convenio_propuesta_id) 	


{
	this.convenio_propuesta_id =  convenio_propuesta_id;
	
	
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp10.readyState == 4 || xmlHttp10.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	var url = "aplicado_propuesta.php?x_convenio_propuesta_id="+convenio_propuesta_id+"";	
				
	url=url+"&sid="+Math.random();
	
    // generar la peticion del servidor
    xmlHttp10.open("GET",url, true);  
    //preparar la funcion  para procesar la repuesta
    xmlHttp10.onreadystatechange = handleServerResponse;
    // ejecutar peticion al servidor
    xmlHttp10.send(null);
  }
  else
    // si el objeto esta ocupado, intentar neuvamente despues de un segundo 
    setTimeout('process()', 1000);
}

//  funcion que se ejecuta cunado se recibe la respuesta del servidor
function handleServerResponse() 
{
  // verifica si la transaccion esta completa
  if (xmlHttp10.readyState == 4) 
  {
    // y si es correcta
    if (xmlHttp10.status == 200){ 
	 //explode en java script
	var respuesta = xmlHttp10.responseText;
	var res = respuesta.split("---");
	var r1 = res[0];
	var r2 = res[1];
	
	document.getElementById("capaConvenioAplicado_"+convenio_propuesta_id+"").value = "";
	document.getElementById("capaConvenioAplicado_"+convenio_propuesta_id+"").innerHTML=r1;
	
	document.getElementById("status_descripcion_"+convenio_propuesta_id+"").value = "";
	document.getElementById("status_descripcion_"+convenio_propuesta_id+"").innerHTML=r2;
   } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp10.statusText);
    }
  }
}
