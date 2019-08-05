<?php

//***********************************************************
//*
//* hitech-password.php - a PHP Message board script
//* (c) Hitech Scripts 2003
//* For more information, visit http://www.hitech-scripts.com
//*
//***********************************************************

$header = get_header();
echo $header;

$action = $_POST["action"];

if ($action == "generate")
{
   echo "<hr>";
   echo "Generated passwords:";
   echo "<br>";
   echo "<br>";
   for($i = 0; $i < $qtty; $i++) {
      $pass = generate();
      echo $pass;
	  echo "<br>";
   }
   
   
   echo "<hr>";	  
}

?>

In order to generate random passwords select the options in the form bellow<br>
and press "Generate" button
	  				  
		   		 <form action="gen_pass.php" METHOD="POST">
				 <input name="action" type="hidden" value="generate">
				 <table width="300">
				    <tr height="30">
       			       <td>Quantity</td>
					   <td><input name="qtty" size="5" type="text" value="10"></td>
				    </tr>
				    <tr height="30">
       			       <td>Passwords length</td>
					   <td><input name="length" size="5" type="text" value="8"> (6 - 30 chars)</td>
				    </tr>					
				    <tr height="30">
       			       <td>Use mixed case</td>
					   <td><select name="use_mix"><option SELECTED>Yes</option><option>No</option></select>
					   (e.g. AbcaBc)
					   </td>
				    </tr>
				    <tr height="30">
       			       <td>Use numbers</td>
					   <td><select name="use_num"><option SELECTED>Yes</option><option>No</option></select>
					   (e.g. gh676hr1)
					   </td>
				    </tr>					
				    <tr height="30">
       			       <td>Use letters</td>
					   <td><select name="use_let"><option SELECTED>Yes</option><option>No</option></select>
					   (e.g. rhsngna)

					   </td>
				    </tr>
					<tr height="30">
       			       <td align="center" colspan="2"><input value="Generate" type="submit">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset"></td>
				    </tr>
				 </table>
				 </form>

<?

$footer = get_footer();
echo $footer;

exit;
//-------------------------------------------------------------------------------------------------
function generate()    {
   global $length, $use_mix, $use_num, $use_let;
   
   $allowable_characters = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789";
   
   if (($use_mix == "Yes") && ($use_num == "Yes") && ($use_let == "Yes"))  {
     $ps_st = 0;
     $ps_len = strlen($allowable_characters);
   }

   if (($use_mix == "No") && ($use_num == "Yes") && ($use_let == "Yes"))  {
     $ps_st = 24;
     $ps_len = strlen($allowable_characters);
   }

   if (($use_mix == "Yes") && ($use_num == "No") && ($use_let == "Yes"))  {
     $ps_st = 0;
     $ps_len = 47;
   }

   if (($use_mix == "Yes") && ($use_num == "Yes") && ($use_let == "No"))  {
     $ps_st = 48;
     $ps_len = strlen($allowable_characters);
   }

   if (($use_mix == "No") && ($use_num == "No") && ($use_let == "Yes"))  {
     $ps_st = 24;
     $ps_len = 47;
   }

   if (($use_mix == "No") && ($use_num == "Yes") && ($use_let == "No"))  {
     $ps_st = 48;
     $ps_len = strlen($allowable_characters);
   }

   if (($use_mix == "Yes") && ($use_num == "No") && ($use_let == "No"))  {
     $ps_st = 0;
     $ps_len = 1;
   }
   
   if (($use_mix == "No") && ($use_num == "No") && ($use_let == "No"))  {
     $ps_st = 0;
     $ps_len = 1;
   }
   
   mt_srand((double)microtime()*1000000);

   $pass = "";

   for($i = 0; $i < $length; $i++) {
	  $pass .= $allowable_characters[mt_rand($ps_st, $ps_len - 1)];
   }
   
   return $pass;
}
//-------------------------------------------------------------------------------------------------
function randString($length=16){
mt_srand((double)microtime()*1000000);
$newstring="";

if($length>0){
while(strlen($newstring)<$length){
switch(mt_rand(1,3)){
case 1: $newstring.=chr(mt_rand(48,57)); break;  // 0-9
case 2: $newstring.=chr(mt_rand(65,90)); break;  // A-Z
case 3: $newstring.=chr(mt_rand(97,122)); break; // a-z
}
}
}
return $newstring;
}
//-------------------------------------------------------------------------------------------------
function get_header()   {
   $header = '<html>
<head>
	<title>Random passwords generator</title>
<style>
body {font-family:verdana; font-size:8pt}
h1 {font-family:verdana; font-size:12pt}
td {font-size:8pt}

a:link { 
	color: #333333;
	}
a:visited { 
	color: #666666;
	}
a:hover { 
	color: #CCCCCC;
	background-color: #333333;
	text-decoration: none;
	}
a:active { 
	color: #333333;
	}
.border{border: 1pt solid black}


INPUT {
  COLOR: #000000; 
  BACKGROUND-COLOR: #f6f6f6;
  FONT-SIZE: 8pt;
  BORDER-BOTTOM: #333333 1px solid; 
  BORDER-LEFT:   #333333 1px solid; 
  BORDER-RIGHT:  #333333 1px solid; 
  BORDER-TOP:    #333333 1px solid 
}

SELECT {
  COLOR: #000000; 
  BACKGROUND-COLOR: #f6f6f6;
  FONT-SIZE: 8pt;
  BORDER-BOTTOM: #333333 1px solid; 
  BORDER-LEFT:   #333333 1px solid; 
  BORDER-RIGHT:  #333333 1px solid; 
  BORDER-TOP:    #333333 1px solid 
}

TEXTAREA {
  COLOR: #000000; 
  BACKGROUND-COLOR: #f6f6f6;
  FONT-SIZE: 8pt;
  BORDER-BOTTOM: #333333 1px solid; 
  BORDER-LEFT:   #333333 1px solid; 
  BORDER-RIGHT:  #333333 1px solid; 
  BORDER-TOP:    #333333 1px solid 
}
</style>		
</head>

<body>
<center><h1>Random passwords generator</h1>';
  
  return $header;
}
//-------------------------------------------------------------------------------------------------
function get_footer()   {
  $footer = '</center> <center>Powered by <a href="http://www.hitech-scripts.com">Hitech-scripts.com</a></center></body></html>';
  
  return $footer;
}
//-------------------------------------------------------------------------------------------------
?>
