
// JavaScript Document

var xmlHttpf

function checkFile(fileName,componentName, submitButton)
{

var file = fileName;
//alert(file);
var component = componentName;
//alert(component);
var submitbotton = submitButton;
//alert(submitbotton);




xmlHttpf = GetXmlHttpObjectf();
if (xmlHttpf==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="checkfileName.php";
url=url+"?file="+file;


url=url+"&sid="+Math.random();
//xmlHttpf.onreadystatechange=stateChanged;

xmlHttpf.onreadystatechange=function(){
     if (xmlHttpf.readyState==4 || xmlHttpf.readyState=="complete"){
		 
		 if( xmlHttpf.responseText == "YES" ) {
       				 alert( "Ya existe un archivo con ese nombre, renombre el archivo y carguelo nuevamente." ); 
		
		 document.getElementById(component).value = "";
		 document.getElementById(submitbotton).disabled = true; 		 
		 } else{
			document.getElementById(submitbotton).disabled = false;  
			 
			 }
		 
		 
        }
     };

xmlHttpf.open("GET",url,true);
xmlHttpf.send(null);
} 

/*
function stateChanged() 
{ 

if (xmlHttpf.readyState==4)
{ 
		document.getElementById("txtHint1").innerHTML=xmlHttpf.responseText;
}
}
*/

function GetXmlHttpObjectf()
{
var xmlHttpf=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttpf=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttpf=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttpf=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttpf;
}
