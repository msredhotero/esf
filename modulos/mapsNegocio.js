var xmlHttpMap

function cargaMapa()
{




xmlHttpMap=GetXmlHttpObject();
if (xmlHttpMap==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="cargaMapaNegocio.php";


url=url+"?sid="+Math.random();
//xmlHttpMap.onreadystatechange=stateChanged;

xmlHttpMap.onreadystatechange=function(){
     if (xmlHttpMap.readyState==4 || xmlHttpMap.readyState=="complete"){
            document.getElementById("mapaNegocio").innerHTML=xmlHttpMap.responseText;
        }
     };

xmlHttpMap.open("GET",url,true);
xmlHttpMap.send(null);
} 

/*
function stateChanged() 
{ 

if (xmlHttpMap.readyState==4)
{ 
		document.getElementById("txtHint1").innerHTML=xmlHttpMap.responseText;
}
}
*/

function GetXmlHttpObject()
{
var xmlHttpMap=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttpMap=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttpMap=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttpMap=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttpMap;
}
