var xmlHttp

function GuardaDireccionAntigua(cliente_id)
{
	var id = cliente_id.value;
	//alert ("entra"+id);

cliente = id;

xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="guarda_direccion_antigua.php";
url=url+"?q1="+cliente_id;


url=url+"&sid="+Math.random();
//xmlHttp.onreadystatechange=stateChanged;

xmlHttp.onreadystatechange=function(){
     if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
            document.getElementById("x_guarda_direccion_a").innerHTML=xmlHttp.responseText;
        }
     };

xmlHttp.open("GET",url,true);
xmlHttp.send(null);
} 

/*
function stateChanged() 
{ 

if (xmlHttp.readyState==4)
{ 
		document.getElementById("txtHint1").innerHTML=xmlHttp.responseText;
}
}
*/

function GetXmlHttpObject()
{
var xmlHttp=null;
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
