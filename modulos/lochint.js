showLoc(this,'txtHint3', 'x_localidad_id')

var xmlHttpLoc

function showLoc(munId,ctlName,localidadname, mismoval)
{
//document.getElementById(ctlName).innerHTML="";
municipio = munId.value;

xmlHttpLoc=GetXmlHttpObject();
if (xmlHttpLoc==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="getLoc.php";
url=url+"?q1="+municipio;
url=url+"&q2="+localidadname;
url=url+"&sid="+Math.random();
//xmlHttpLoc.onreadystatechange=stateChanged;

xmlHttpLoc.onreadystatechange=function(){
     if (xmlHttpLoc.readyState==4 || xmlHttpLoc.readyState=="complete"){
            document.getElementById(ctlName).innerHTML=xmlHttpLoc.responseText;
        }
     };

xmlHttpLoc.open("GET",url,true);
xmlHttpLoc.send(null);
} 


function showLoc2(munId,ctlName,localidadname, mismoval,estado)
{
document.getElementById(ctlName).innerHTML="";
municipio = munId.value;
edo = document.getElementById(mismoval).value;

xmlHttpLoc=GetXmlHttpObject();
if (xmlHttpLoc==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="getLoc_new.php";
url=url+"?q1="+municipio;
url=url+"&q2="+localidadname;
url=url+"&sid="+Math.random();
url=url+"&qe="+edo;
//xmlHttpLoc.onreadystatechange=stateChanged;

xmlHttpLoc.onreadystatechange=function(){
     if (xmlHttpLoc.readyState==4 || xmlHttpLoc.readyState=="complete"){
            document.getElementById(ctlName).innerHTML=xmlHttpLoc.responseText;
        }
     };

xmlHttpLoc.open("GET",url,true);
xmlHttpLoc.send(null);
} 
/*
function stateChanged() 
{ 

if (xmlHttpLoc.readyState==4)
{ 
		document.getElementById("txtHint1").innerHTML=xmlHttpLoc.responseText;
}
}
*/

function GetXmlHttpObject()
{
var xmlHttpLoc=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttpLoc=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttpLoc=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttpLoc=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttpLoc;
}
