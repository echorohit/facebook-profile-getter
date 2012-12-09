<?php
error_reporting(0);
include('conn.php')
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facebook Profile fetch Script</title>
</head>

<body>
    <fieldset>
<legend>Facebook Profile Information</legend>
<form id="form1" name="form1" method="post" action="process.php">
  <label>Enter Profile URL
  <input type="text" name="profileUrl" id="profileUrl"  size="50"/>
  </label>
  <input type="submit" name="Submit" id="Submit" value="Submit" />  
</form>
</fieldset>
</body>
</html>

