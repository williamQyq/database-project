<?php
include 'config.php';
session_start();
$id = $_SESSION["id"];

$message="";
//build connection no password
if(count($_POST)>0) {
    $con = mysqli_connect($host, $username) or die ('Could not connect: ' . mysql_error());
    $mydb = mysqli_select_db ($con, $database) or die ('Could not select database');

    $query_update_user = "UPDATE users SET username='" . $_POST["email"] . "', 
    password='" . $_POST["password"] . "', 
    name='" . $_POST["name"] . "', 
    phoneNumber='" . $_POST["phoneNumber"] . "'
    WHERE uid='". $id . "' ";

    $result = mysqli_query($con,$query_update_user);
    if(!$result) {
        trigger_error('query failed',E_USER_ERROR);
    }else {
        $message="You have successfully update your profile!";
    }
}

?>
<html>
    <head>
        <title>Change Parent Profile</title>
    </head>
    <body>
        <h1> Change Your Parent Profile</h1>
        <div><?php if($message!="") {echo $message;} ?></div>
        <form action="" method="post">
           <table>
               <tr>
                    <td><input type="text" name="email" placeholder="enter your email address"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="password" placeholder="enter your new password"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="name" placeholder="enter your name"/></td>
                </tr>
                <tr>
                    <td><input type="tel" name="phoneNumber" placeholder="enter your phone number"/></td>
                </tr>
                <tr>
                    <td><input type="submit" value="submit"/></td>
                </tr>
            </table>
        </form>
        <a href="logout.php">go to home</a>
    </body>
</html>
