var xmlHttp

function showHint(paisid,ctlName,delegacionname, mismoval)
{
document.getElementById(ctlName).innerHTML="";
paisid = paisid.value;

xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="getpaisedo.php";
url=url+"?q1="+paisid;
url=url+"&q2="+delegacionname;
if(mismoval > 0){
	url=url+"&q3="+mismoval;
}
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


function showHint2(paisid,ctlName,delegacionname, mismoval, estado)
{
	
document.getElementById(ctlName).innerHTML="";
paisid = paisid.value;
edo = document.getElementById(mismoval).value;


xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="getpaisedo_new.php";
url=url+"?q1="+paisid;
url=url+"&q2="+delegacionname;
url=url+"&qe="+edo;
if(mismoval > 0){
	url=url+"&q3="+mismoval;
}
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
