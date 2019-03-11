<?php
    session_start();
?>
<html>
<head>
<title>Student Login</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome <?php echo $_SESSION["name"]; ?>. Click here to <a href="student_logout.php" tite="studentLogout">Logout</a>.
<?php
    }else {
?>
    <h1>Please <a href="student_login.php" title="studentLogin">login</a> first.</h1><br>
<?php    
    }
?>
</body>
</html>