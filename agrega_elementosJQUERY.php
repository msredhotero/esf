<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
    <script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
<script language="javascript">
        $(document).ready(function() {
            $('#btnAdd').click(function() {
                var num     = $('.clonedInput').length;
                var newNum  = new Number(num + 1);
 
                var newElem = $('#input' + num).clone().attr('id', 'input' + newNum);
     
                newElem.find('td:first input').attr('id', 'name' + newNum).attr('name', 'name' + newNum);
                $('#input' + num).after(newElem);
                $('#btnDel').attr('disabled','');
 
                if (newNum == 10)
                    $('#btnAdd').attr('disabled','disabled');
            });
 
            $('#btnDel').click(function() {
                var num = $('.clonedInput').length;
 
                $('#input' + num).remove();
                $('#btnAdd').attr('disabled','');
 
                if (num-1 == 1)
                    $('#btnDel').attr('disabled','disabled');
            });
 
            $('#btnDel').attr('disabled','disabled');
        });

    


</script>
</head>

<body>
<form id="testform">
<table>
<tr id="input1"  class="clonedInput">
  <td>
      <label id="" >Name</label>
     <span id="" > <input type="text" name="name1" id="name1" /></span>
   </td></tr>
   <tr><td><label>Need more fields?</label>
      <input type="button" id="btnAdd" value="+" />
      <input type="button" id="btnDel" value="-" /></td></tr>
  
      </table>
   
</form>
</body>
</html>