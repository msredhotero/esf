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
function process(tipoSolicitud)
{
	this.tipoSol =  tipoSolicitud;
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	if(tipoSol == 1) 
	
	var url="tipoCuenta/formatos/formatoIndividualP.php";
	//var url="tipoCuenta/formatos/formatoIndividual.php";	
	else if(tipoSol == 2)
		var url="tipoCuenta/formatos/formatoCreditoSolidario.php";
		else if(tipoSol == 3)
			var url="tipoCuenta/formatos/formatoAdquisicionMaquinaria.php";
			else if(tipoSol == 4)
				var url="tipoCuenta/formatos/formatoPYME.php";
				else
				 var url = "tipoCuenta/formatos/formatoBlanco.php";	
				
	url=url+"?sid="+Math.random();
	
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
	document.getElementById("capaTipoCredito").innerHTML=xmlHttp.responseText;      
    } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp.statusText);
    }
  }
}


function getDataBasic(){
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	var dato1 = document.getElementById("");
	
	
	
	}