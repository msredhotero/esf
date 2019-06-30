<?

/** Serialized Array of big names, thousand, million, etc 
 * @package NumberToText */
define("N2T_BIG", serialize(array('mil', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion', 'unvigintillion', 'duovigintillion', 'trevigintillion', 'quattuorvigintillion', 'quinvigintillion', 'sexvigintillion', 'septenvigintillion', 'octovigintillion', 'novemvigintillion', 'trigintillion', 'untrigintillion', 'duotrigintillion', 'tretrigintillion', 'quattuortrigintillion', 'quintrigintillion', 'sextrigintillion', 'septentrigintillion', 'octotrigintillion', 'novemtrigintillion')));
/** Serialized Array of medium names, twenty, thirty, etc 
 * @package NumberToText */
define("N2T_MEDIUM", serialize(array(2=>'veinte', 3=>'treinta', 4=>'cuarenta', 5=>'cincuenta', 6=>'sesenta', 7=>'setenta', 8=>'ochenta', 9=>'noventa'))); 
/** Serialized Array of small names, zero, one, etc.. up to eighteen, nineteen 
 * @package NumberToText */
define("N2T_SMALL", serialize(array('cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieziseis', 'diezisiete', 'dieziocho', 'diceznueve')));
/** Word for "dollars" 
 * @package NumberToText */
define("N2T_DOLLARS", "pesos");
/** Word for one "dollar" 
 * @package NumberToText */
define("N2T_DOLLARS_ONE", "peso");
/** Word for "cents" 
 * @package NumberToText */
define("N2T_CENTS", "centavos");
/** Word for one "cent" 
 * @package NumberToText */
define("N2T_CENTS_ONE", "centavo");
/** Word for "and" 
 * @package NumberToText */
define("N2T_AND", "y");
/** Word for "negative" 
 * @package NumberToText */
define("N2T_NEGATIVE", "negative");

/** Number to text converter. Converts a number into a textual description, such as
 * "one hundred thousand and twenty-five".
 * 
 * Now supports _any_ size number, and negative numbers. To pass numbers > 2 ^32, you must
 * pass them as a string, as PHP only has 32-bit integers.
 *
 * @author Greg MacLelan
 * @version 1.1
 * @param int  $number      The number to convert
 * @param bool $currency    True to convert as a dollar amount
 * @param bool $capatalize  True to capatalize every word (except "and")
 * @param bool $and         True to use "and"  (ie. "one hundred AND six")
 * @return The textual description of the number, as a string.
 * @package NumberToText
 */
/** Changelog:
 * 2007-01-11: Fixed bug with invalid array references, trim() output
 */
function NumberToText($number, $currency = false, $capatalize = false, $and = true) {
    $big = unserialize(N2T_BIG);
    $small = unserialize(N2T_SMALL);
    
    // get rid of leading 0's
    /*
    while ($number{0} == 0) {
        $number = substr($number,1);
    }
    */
    
    $text = "";
    
    //$negative = ($number < 0); // check for negative
    //$number = abs($number); // make sure we have a +ve number
    if (substr($number, 0, 1) == "-") {
        $negative = true;
        $number = substr($number,1); // abs()
    } else {
        $negative = false;
    }
    
    // get the integer and decimal parts
    //$int_o = $int = floor($number); // store into two vars
    if ($pos = strpos($number,".")) {
        $int_o = $int = substr($number,0,$pos);
        $decimal_o = $decimal = substr($number,$pos + 1);
    } else {
        $int_o = $int = $number;
        $decimal_o = $decimal = 0;
    }
    // $int_o and $decimal_o are for "original value"
    
    // conversion for integer part:
    
    $section = 0; // $section controls "thousand" "million" etc
    do {
        // keep breaking down into 3 digits ($convert) and the rest
        //$convert = $int % 1000;
        //$int = floor($int / 1000);
        
        if ($section > count($big) - 1) {
            // ran out of names for numbers this big, call recursively
            $text = NumberToText($int, false, false, $and)." ".$big[$section-1]." ".$text;
            $int = 0;
        } else { 
            // we can handle it
            
            $convert = substr($int, -3); // grab the last 3 digits
            $int = substr($int, 0, -1 * strlen($convert));
            
            if ($convert > 0) {
                // we have something here, put it in
                $text = trim(n2t_convertthree($convert, $and, ($int > 0)).(isset($big[$section-1]) ? ' '.$big[$section-1].' ' : '').$text);
            }
        }
        
        $section++;
    } while ($int > 0);
    
    
    // conversion for decimal part:
    
    if ($currency && floor($number)) {
        // add " dollars"
        $text .= " ".($int_o == 1 ? N2T_DOLLARS_ONE : N2T_DOLLARS)." ";
    }
    
    if ($decimal && $currency) {
        // if we have any cents, add those
        if ($int_o > 0) {
            $text .= " ".N2T_AND." ";
        }
        
        $cents = substr($decimal,0,2); // (0.)2342 -> 23
        $decimal = substr($decimal,2); // (0.)2345.. -> 45..
        
        $text .= n2t_convertthree($cents, false, true); // explicitly show "and" if there was an $int
    }
    
    if ($decimal) {
        // any remaining decimals (whether or not $currency is set)
        $text .= " point";
        for ($i = 0; $i < strlen($decimal); $i++) {
            // go through one number at a time
            $text .= " ".$small[$decimal{$i}];
        }
    }
    
    if ($decimal_o && $currency) {
        // add " cents" (if we're doing currency and had decimals)
        $text .= " ".($decimal_o == 1 ? N2T_CENTS_ONE : N2T_CENTS);
    }
    
    // check for negative
    if ($negative) {
        $text = N2T_NEGATIVE." ".$text;
    }
    
    // capatalize words
    if ($capatalize) {
        // easier to capatalize all words then un-capatalize "and"
        $text = str_replace(ucwords(N2T_AND), N2T_AND, ucwords($text));
    }
    
    return trim($text);
}

/** This is a utility function of n2t. It converts a 3-digit number
 * into a textual description. Normally this is not called by itself.
 *
 * @param  int  $number     The 3-digit number to convert (0 - 999)
 * @param  bool $and        True to put the "and" in the string
 * @param  bool $preceding  True if there are preceding members, puts an
 *                          explicit and in (ie 1001 => one thousand AND one)
 * @return The textual description of the number, as a string 
 * @package NumberToText
 */
function n2t_convertthree($number, $and, $preceding) {
    $small = unserialize(N2T_SMALL);
    $medium = unserialize(N2T_MEDIUM);
    
    $text = "";
    
    if ($hundreds = floor($number / 100)) {
        // we have 100's place
        $text .= $small[$hundreds]." mil ";
    }
    $tens = $number % 100;
    if ($tens) {
        // we still have values
        if ($and && ($hundreds || $preceding)) {
            $text .= " ".N2T_AND." ";
        }
        
        if ($tens < 20) {
            $text .= $small[$tens];
        } else {
            $text .= $medium[floor($tens/10)];
            if ($ones = $tens % 10) {
                $text .= "-".$small[$ones];
            }
        }
    }
    
    return $text;
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 


$currency = isset($_GET["currency"]) ? (bool)$_GET["currency"] : false;
$capatalize = isset($_GET["capatalize"]) ? (bool)$_GET["capatalize"] : false;
$and = isset($_GET["and"]) ? (bool)$_GET["and"] : !isset($_GET["num"]); // set to true by default if we didnt submit anything

echo "<form method=\"GET\">";
echo "<input type=text name=\"num\" value=\"".$_GET["num"]."\">";
echo "<input type=submit value=\"Convert\">";
echo "<br><input type=checkbox name=\"currency\" value=\"1\" ".($currency ? "CHECKED" :"")."> Currency";
echo "<br><input type=checkbox name=\"capatalize\" value=\"1\" ".($capatalize ? "CHECKED" :"")."> Capatalize";
echo "<br><input type=checkbox name=\"and\" value=\"1\" ".($and ? "CHECKED" :"")."> Use 'and'";
echo "</form>";

if (isset($_GET['num'])) {
    $num = preg_replace('/[^0-9\.]/','',$_GET['num']);
    echo $num . " => ".numbertotext($num, $currency, $capatalize, $and);
    echo "<br><br><br><br>";
    exit;
} 

$arr = array(1,2,10,11,12,20,21,100,200,202,220,223,1000,1100,1232,10000,100000,1100000,1000001,1.1,1.23,1.424,1.3005,0.1,0.23,23445.34,349.35,3476233.52); 
echo "Normal:<br><br>";

$time_start = getmicrotime();
foreach ($arr as $val) {
    //echo number_format($val,6)." => ".numbertotext($val,false,false)."<br>";
    echo $val." => \"".numbertotext($val,false,false)."\"<br>";
}
$time_end = getmicrotime();
$time = $time_end - $time_start;
echo "Completed in $time";

echo "<br><br><br>Currency:<br><br>";

$time_start = getmicrotime();
foreach ($arr as $val) {
    //echo number_format($val,6)." => ".numbertotext($val,true,true)."<br>";
    echo $val." => ".numbertotext($val,true,true)."<br>";
}
$time_end = getmicrotime();
$time = $time_end - $time_start;
echo "Completed in $time";


echo "<br><br><br>";


$arr = array(-1,-32754264,7823572834.23432,"842673284525.3253",-69,1000000000,10000000000,100000000000,"1000000000000","10000000000000","100000000000000","1000000000000000","10000000000000000","100000000000000000","1000000000000000000","1000000000000000000000","1000000000000000000000000","1000000000000000000000000000","1000000000000000000000000000000","1000000000000000000000000000000000","1000000000000000000000000000000000000","1000000000000000000000000000000000000000","1000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000000000000000","1000000000000000000000000000000000000000000000000000000000000000000","1735493936528346132019285410348712730057341562931024518346213403490","-8365102934619034174538361493275960273462592739257246349237426342384.38651","100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000");
echo "Normal:<br><br>";

$time_start = getmicrotime();
foreach ($arr as $val) {
    //echo number_format($val,6)." => ".numbertotext($val,false,false)."<br>";
    echo $val." => ".numbertotext($val,false,false)."<br>";
}
$time_end = getmicrotime();
$time = $time_end - $time_start;
echo "Completed in $time";

echo "<br><br><br>Currency:<br><br>";

$time_start = getmicrotime();
foreach ($arr as $val) {
    //echo number_format($val,6)." => ".numbertotext($val,true,true)."<br>";
    echo $val." => ".numbertotext($val,true,true)."<br>";
}
$time_end = getmicrotime();
$time = $time_end - $time_start;
echo "Completed in $time";


?>

