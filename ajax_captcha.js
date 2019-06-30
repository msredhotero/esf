   var http_request = false;
   function makeRequest(url, parameters) {
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      http_request.onreadystatechange = alertContents;
      http_request.open('GET', url + parameters, true);
      http_request.send(null);
   }

   function alertContents() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);
            //document.getElementById('enviadata').innerHTML = result; 
			//Get a reference to CAPTCHA image
			img = document.getElementById('imgCaptcha'); 
			//Change the image
			img.src = 'create_image.php?' + Math.random(); // Search for new image
			document.getElementById('txtsscode').value=''; //Reset input Captcha  after succes return 			
            result = http_request.responseText;
			if(result == 0){
				//alert("Código de seguridad invalido, intente nuevamente.");	
				document.frmdata.submit();
			}else{
				document.frmdata.submit();
			}
			
         } else {
            alert('There was a problem with the request.');
         }
      }
   }
   
   function get(obj,regtipo) {
      var getstr = "?" +
			"&txtsscode=" + encodeURI( document.getElementById("txtsscode").value)+'&tipo='+regtipo;

      makeRequest('get.php', getstr);
   }

// IMAGE REFRESHING
function refreshimg()
{
	//Get a reference to CAPTCHA image
	img = document.getElementById('imgCaptcha'); 
	//Change the image
	img.src = 'create_image.php?' + Math.random();
}