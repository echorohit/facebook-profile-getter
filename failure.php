<?php
$error=  base64_decode($_GET['message']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facebook Profile fetch Script</title>
</head>
<body>
    <hr></hr>    
    <p align="center">
        <?php
            echo $error;
        ?>
        <br/>
         Click <a href="index.php">here</a> to go back.
    </p>

</body>
</html>