<?php
    include 'config.php';
   
    session_start();
    $message="";
    if(count($_POST)>0) {
        //db connection no password.
        $con = mysqli_connect($host,$username) or die('Unable to connect');
        $mydb = mysqli_select_db($con, $database) or die ('could not select database');
        
        $query_check_duplicate = "SELECT * FROM users WHERE username = '" . $_POST["email"] . "' ";
        $result = mysqli_query($con,$query_check_duplicate);
        $row  = mysqli_fetch_array($result);
        if(is_array($row)) {
            $message = "This email has been registered! Please change your email.";
        } else {
            $_SESSION["email"] = $_POST['email'];
            $_SESSION["password"] = $_POST["password"];
            $_SESSION["role"] = $_POST["role"];
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["phoneNumber"] = $_POST["phoneNumber"];
            header("Location:parent_register_info.php");
        }
    }

?>

<html>
    <head>
        <title>Parent Register</title>
    </head>
    <body>
        <h1> Parent register</h1>
        <div><?php if($message!="") { echo $message;} ?></div>
        <form action="" method="post">
           <table>
               <tr>
                    <td><input type="text" name="email" placeholder="enter your email address"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="password" placeholder="enter your password"/></td>
                </tr>
                <tr>
                    <td>Role:</td>    
                    <td>
                        <select name="role">
                        <option value="noRole">no role</option>
                        <option value="moderator">moderator</option>  
                        </select>
                    </td>
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
        <a href="home.html">go to home</a>
    </body>
</html>
