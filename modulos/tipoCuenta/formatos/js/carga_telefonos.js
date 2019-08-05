// JavaScript Document
// JavaScript Document
//guardar la referencia al objeto XMLHttpRequest 
var xmlHttp = createXmlHttpRequestObject1(); 

// creacion del objeto   XMLHttpRequest
function createXmlHttpRequestObject1() 
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
function process_tel(id_campo, tipoCampo)
{
	
	this.id = id_campo;
	this.tipoCampo = tipoCampo;
	//this.tipoSol =  tipoSolicitud;
	
  // procesar la peticion si el objeto  xmlHttp no esta ocupado
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
  {
    //var url="tipoCuenta/formatos/NUEW.html";
	//tipo de solicitud 
	if(tipoCampo != 0) 
	
	var url="tipoCuenta/formatos/carga_telefonos.php?x_id="+id+"&x_tipo_campo="+tipoCampo;
	
				
	url=url+"&sid="+Math.random();
	
    // generar la peticion del servidor
    xmlHttp.open("GET",url, true);  
    //preparar la funcion  para procesar la repuesta
    xmlHttp.onreadystatechange = handleServerResponse_n;
    // ejecutar peticion al servidor
    xmlHttp.send(null);
  }
  else
    // si el objeto esta ocupado, intentar neuvamente despues de un segundo 
    setTimeout('process_tel()', 1000);
}

//  funcion que se ejecuta cunado se recibe la respuesta del servidor
function handleServerResponse_n() 
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
	
	if(tipoCampo == 1){
	document.getElementById("x_telefono_casa_"+id).innerHTML=xmlHttp.responseText; 
	}else if(tipoCampo == 2){
		document.getElementById("telefono_celular_"+id).innerHTML=xmlHttp.responseText; 
		
		}
    } 
    // el status es diferente de 200
    else 
    {
      alert("Existe un problema en el acceso al servidor: " + xmlHttp.statusText);
    }
  }
}

