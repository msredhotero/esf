var xmlHttp

function cargalineas(fondeoid,ctlName,lineaid)
{

document.getElementById(ctlName).innerHTML="";
fondeoid = fondeoid.value;

xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="get_lineafondeo.php";
url=url+"?q1="+fondeoid;
url=url+"&q2="+lineaid;
url=url+"&sid="+Math.random();
//xmlHttp.onreadystatechange=stateChanged;

xmlHttp.onreadystatechange=function(){
     if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
            document.getElementById(ctlName).innerHTML=xmlHttp.responseText;
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
