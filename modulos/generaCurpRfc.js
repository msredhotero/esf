// JavaScript Document
var xmlHttpc

function generaCurpRfc(edoId,ctlName1,ctlName2)
{
	
nombre = document.getElementById("x_nombre").value;
paterno = document.getElementById("x_apellido_parterno").value;
materno = document.getElementById("x_apellido_materno").value;
fecha = document.getElementById("x_fecha_nacimiento").value;
sexo = document.getElementById("x_sexo").value;
estado = document.getElementById("x_entidad_nacimiento").value;
//estado = edoId.value ;

//var Date = '24/02/2009';
var elem = fecha.split('/');
dia = elem[0];
mes = elem[1];
anio = elem[2];

document.getElementById(ctlName1).innerHTML="";
document.getElementById(ctlName2).innerHTML="";


xmlHttpc=GetXmlHttpObject();
if (xmlHttpc==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 

var url="generaCurpRfc.php";
url=url+"?q1="+nombre;
url=url+"&q2="+paterno;
url=url+"&q3="+materno;
url=url+"&q4="+dia;
url=url+"&q5="+mes;
url=url+"&q6="+anio;
url=url+"&q7="+estado;
url=url+"&q8="+sexo;

url=url+"&sid="+Math.random();
//xmlHttpc.onreadystatechange=stateChanged;

xmlHttpc.onreadystatechange=function(){
     if (xmlHttpc.readyState==4 || xmlHttpc.readyState=="complete"){
		 
		var respuesta =  xmlHttpc.responseText.split('-');
            document.getElementById(ctlName1).innerHTML=respuesta[0];
			document.getElementById(ctlName2).innerHTML=respuesta[1];
        }
     };

xmlHttpc.open("GET",url,true);
xmlHttpc.send(null);
} 

/*
function stateChanged() 
{ 

if (xmlHttpc.readyState==4)
{ 
		document.getElementById("txtHint1").innerHTML=xmlHttpc.responseText;
}
}
*/

function GetXmlHttpObject()
{
var xmlHttpc=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttpc=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttpc=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttpc=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttpc;
}
