<?php
    session_start();
?>
<html>
<head>
<title>Parent Login</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome Parent <?php echo $_SESSION["name"]; ?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br><br>
<?php
    }else {
?>
    <h1>Please <a href="parent_login.php" title="parentLogin">login</a> first.</h1><br>
<?php    
    }
?>

<table border="1">
    <tr>
        <td>User</td>
        <td>Profile</td>
        <td><a href=parent_prof.php title="parentProfile">Change Your Profile</a></td>
    </tr>
    <tr>
        <td>User</td>
        <td>Profile</td>
        <td><a href=parent_change_child_prof.php title="parentChildProfile">Change Your children Profile</a></td>
    </tr>
    <tr>
        <td>Parent</td>
        <td>Section</td>
        <td><a href=parent_view_sec.php title="parentSec">View Section</a></td>
    </tr>
    <tr>
        <td>Moderator</td>
        <td>Moderator</td>
        <td><a href=parent_view_mdtor.php title="parentMtor">View Moderator</a></td>
    </tr>
</table>

</body>
</html>