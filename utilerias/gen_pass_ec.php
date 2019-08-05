<?php
function generate($length, $use_mix, $use_num, $use_let)    {
   
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
?>
