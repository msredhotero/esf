// JavaScript Document
//guardar la referencia al objeto XMLHttpRequest 
var xmlHttp = createXmlHttpRequestObject(); 

// creacion del objeto   XMLHttpRequest
function createXmlHttpRequestObject() 
{	
  // guardar la referencia al objeto XMLHttpRequest 
 var xmlHttp;
 
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;

    
}

// Hacer la peticion  HTTP asincrona usando el objeto  XMLHttpRequest
function ConciliaGarantia(garantia_id) 	


{
	this.garantia_id =  garantia_id;
	
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	var url = "concilia_garantia_liquida.php?x_garantia_liquida_id="+garantia_id+"";	
				
	url=url+"&sid="+Math.random();
	
    // generar la peticion del servidor
    xmlHttp.open("GET",url, true);  
    //preparar la funcion  para procesar la repuesta
    xmlHttp.onreadystatechange = handleServerResponse;
    // ejecutar peticion al servidor
    xmlHttp.send(null);
  }
  else
    // si el objeto esta ocupado, intentar neuvamente despues de un segundo 
    setTimeout('process()', 1000);
}

//  funcion que se ejecuta cunado se recibe la respuesta del servidor
function handleServerResponse() 
{
  // verifica si la transaccion esta completa
  if (xmlHttp.readyState == 4) 
  {
    // y si es correcta
    if (xmlHttp.status == 200){ 
	 //explode en java script
	document.getElementById("capaGarantiaConciliada_"+garantia_id+"").value = "";
	document.getElementById("capaGarantiaConciliada_"+garantia_id+"").innerHTML=xmlHttp.responseText;
   } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp.statusText);
    }
  }
}

