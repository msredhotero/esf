<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
$x_time = date("Y-m-d  H:i:s");

echo $x_time;
$x_time2 = date("H:i:s", time()+(60*60));
echo "<br>".$x_time2;
 ?>
</body>
</html>