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
function AceptaConvenioCredito(credito_id)
{
	this.credito_id =  credito_id;
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	var url = "acepta_convenio.php?x_credito_id="+credito_id+"";	
				
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
	/*var newdiv = document.createElement("div");
	newdiv.innerHTML = xmlHttp.responseText;
	var container = document.getElementById("capaTipoCredito");
	container.appendChild(newdiv); */	
	
	 var respuesta = xmlHttp.responseText.split("-");
	 var boton_uno = respuesta[0];
	 var boton_dos = respuesta[1];
	 var boton_tres = respuesta[2];
	 var boton_cuatro = respuesta[3];
	 //explode en java script
	//document.getElementById("x_boton_uno").innerHTML=boton_uno ;
	//document.getElementById("x_boton_dos").innerHTML=boton_dos;
	document.getElementById("x_boton_tres").innerHTML=boton_tres;
	document.getElementById("x_boton_cuatro").innerHTML=boton_cuatro ;    
    } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp.statusText);
    }
  }
}