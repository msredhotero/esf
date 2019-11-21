<?php
$has_title_row = true;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(is_uploaded_file($_FILES['csvfile']['tmp_name'])){
        $filename = basename($_FILES['csvfile']['name']);

        if(substr($filename, -3) == 'csv'){
            $tmpfile = $_FILES['csvfile']['tmp_name'];
            if (($fh = fopen($tmpfile, "r")) !== FALSE) {
                $i = 0;
                while (($items = fgetcsv($fh, 10000, ",")) !== FALSE) {
                    if($has_title_row === true && $i == 0){ // skip the first row if there is a tile row in CSV file
                        $i++;
                        continue;
                    }
                    print_r($items);
                    $i++;
                }
            }
        }
        else{
            die('Invalid file format uploaded. Please upload CSV.');
        }
    }
    else{
        die('Please upload a CSV file.');
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Raju Gautam" />
	<title>Test</title>
</head>
<body>
    <form enctype="multipart/form-data" action="" method="post" id="add-courses">
        <table cellpadding="5" cellspacing="0" width="500" border="0">
            <tr>
                <td class="width"><label for="image">Upload CSV file : </label></td>
                <td><input type="hidden" name="MAX_FILE_SIZE" value="10000000" /><input type="file" name="csvfile" id="csvfile" value=""/></td>
                <td><input type="submit" name="uploadCSV" value="Upload" /></td>
            </tr>
        </table>
    </form>
</body>
</html>
