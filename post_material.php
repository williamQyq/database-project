<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $message = "";

    //get the clicked btn stored info
    for($btn_key = 0; $btn_key<$_SESSION['postkey'];  $btn_key++){
        if(isset($_POST["postBtn".$btn_key]) || isset($_SESSION["postBtn".$btn_key])){
            $_SESSION["postBtn".$btn_key] = $btn_key; 
            $cid = $_SESSION["assinfo".$btn_key][0];
            $title = $_SESSION["assinfo".$btn_key][1];
            $sec_id = $_SESSION["assinfo".$btn_key][2];
        }
    }

            if(isset($_POST["smTitle"])){
                $smTitle = $_POST["smTitle"];
                $author = $_POST["author"];
                $type = $_POST["type"];
                $url = $_POST["URL"];
                $date = $_POST["date"];
                $notes = $_POST["notes"];

                $query_insert_sm = "INSERT INTO studyMaterials (title,author,type,URL,assignedDate,notes) VALUES ('$smTitle','$author','$type','$url','$date','$notes')";
                
                $result = mysqli_query($con,$query_insert_sm);
                if(!$result){
                    $message = "Fail to insert study material";
                }
                $sm_id = mysqli_insert_id($con);

                $query_insert_textUsed = "INSERT INTO textUsed VALUES ('$cid','$title','$sec_id','$sm_id')";
                $result = mysqli_query($con,$query_insert_textUsed);
                if(!$result){
                    $message = "Fail to insert study material";
                }
                $message = "Successfully!";
            }
?>
<html>
<head>
<title>Post material</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome Moderator <?php echo $_SESSION["name"]; ?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br>
    <a href="parent_view_mdtor.php">go back</a><br><br>
<?php
    }else {
?>
    <h1>Please <a href="parent_login.php" title="parentLogin">login</a> first.</h1><br>
<?php    
    }
?>
<h1>Post Material</h1><br>
<?php
    if($message != "") {echo $message;}
?>

    <form action="" method="post">
           <table>
                <tr>
                    <td><input type="text" name="smTitle" placeholder="enter book title"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="author" placeholder="enter the author"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="type" placeholder="enter book type"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="URL" placeholder="enter book URL"/></td>
                </tr>
                <tr>
                    <td><input type="date" name="date" placeholder="enter assigned date"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="notes" placeholder="enter notes"/></td>
                </tr>
                <tr>
                    <td><input type="submit" value="submit"/></td>
                </tr>
            </table>
    </form>
    
<?php
    //show study materials info for specific session
?>


</body>
</html>

<?php

?>
