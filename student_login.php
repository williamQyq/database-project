<?php
    session_start();
    $message="";
    if(count($_POST)>0) {
        $con = mysqli_connect('localhost', 'root', '') or die('Unable To connect');
        $mydb = mysqli_select_db($con, 'db2') or die ('could not select database');

        $query_select_user = "SELECT * FROM users WHERE username = '" . $_POST["username"] . "' and password = '" . $_POST["password"] . "'";
        $result = mysqli_query($con,$query_select_user);
        $row  = mysqli_fetch_array($result);
        if(is_array($row)) {
            $_SESSION["id"] = $row[stu_id];
            $_SESSION["name"] = $row[name];
        } else {
            $message = "Invalid Username or Password!";
        }
    }
    if(isset($_SESSION["id"])) {
        header("Location:index_student.php");
    }
?>

<html>
<head>
<title>User Login</title>
</head>
<body>
<form name="frmUser" method="post" action="">
<div><?php if($message!="") { echo $message; } ?></div>
<h3>Enter Student Login Details</h3>
 Username:<br>
 <input type="text" name="username">
 <br>
 Password:<br>
<input type="password" name="password">
<br><br>
<input type="submit" name="submit" value="Submit">
<input type="reset">
</form>
</body>
</html>