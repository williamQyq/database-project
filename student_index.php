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
    Welcome <?php echo $_SESSION["name"]; ?>.<br>
    Your grade is <?php echo $_SESSION["grade"];?>.<br>
    Click here to <a href="student_logout.php" tite="studentLogout">Logout</a>.<br><br>
<?php
    }else {
?>
    <h1>Please <a href="student_login.php" title="studentLogin">login</a> first.</h1><br>
<?php    
    }
?>

<table border="1">
    <tr>
        <td>User</td>
        <td>Profile</td>
        <td><a href=student_prof.php title="studentProfile">Change Your Profile</a></td>
    </tr>
    <tr>
        <td>Student</td>
        <td>Section</td>
        <td><a href=student_view_sec.php title="studentSec">View Section</a></td>
    </tr>
    <tr>
        <td>Mentor</td>
        <td>Mentor</td>
        <td><a href=student_view_mtor.php title="studentMtor">View Mentor</a></td>
    </tr>
    <tr>
        <td>Mentee</td>
        <td>Mentee</td>
        <td><a href=student_view_mtee.php title="studentMtee">View Mentee</a></td>
    </tr>
</table>

</body>
</html>