<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $query_view_mtor = "SELECT u.uid, u.name, s.grade FROM users u NATURAL JOIN mentors m NATURAL JOIN students s WHERE u.uid = m.mtor_id AND m.mtor_id = s.stu_id";

    $message = "";

        //get the clicked btn stored info
        for($btn_key = 0; $btn_key<$_SESSION['asskey'];  $btn_key++){
            if(isset($_POST["assBtn".$btn_key]) || isset($_SESSION["assBtn".$btn_key])){
                $_SESSION["assBtn".$btn_key] = $btn_key; 
                $cid = $_SESSION["assinfo".$btn_key][0];
                $title = $_SESSION["assinfo".$btn_key][1];
                $sec_id = $_SESSION["assinfo".$btn_key][2];
            }
        }
        if(isset($_SESSION["setKey"])) {
            for($set_key=0; $set_key<$_SESSION["setKey"]; $set_key++) {
                $uid = $_SESSION["setInfo".$set_key][0];
                $name = $_SESSION["setInfo".$set_key][1];
                $grade = $_SESSION["setInfo".$set_key][2];
        
                $query_check_mentor = "SELECT * FROM teach WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND mtor_id = '$uid'";    
                if(isset($_POST["setBtn".$set_key])){
                    //avoid lost post;
                    $result = mysqli_query($con,$query_check_mentor);
                        // $row  = mysqli_fetch_array($result);
                    if(mysqli_num_rows($result)>0){
                        $message = "Fail to do that! This mentor has taught this section!";
                    } else {
                        //mod button $i is clicked 
                        //store info into moderate table
                        $query_insert_teach = "INSERT INTO teach (cid, title, sec_id, mtor_id) VALUES('$cid','$title','$sec_id','$uid')";
                        mysqli_query($con,$query_insert_teach);
                        $message = "Successfully!";
                    }   
                }
            }
        }
     
?>
<html>
<head>
<title>Assign mentor</title>
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
<h1>Assign Mentor List</h1><br>
<?php
    if($message != "") {echo $message;}
?>
<table border="1">
    <tr>
        <th>Mentor Name</th>
        <th>Grade </th>
        <th>Assign</th>
    </tr>
    <?php

        $key = 0;
        $setBtn = "setBtn".$key;
        $result = mysqli_query($con,$query_view_mtor);
        if(mysqli_num_rows($result)>0) {
            while($row = mysqli_fetch_array($result)) {
                //store section info---------------
                $info = array(
                    $row["uid"],
                    $row["name"],
                    $row["grade"],
                );
               
                $_SESSION["info".$key] = $info;
                
                echo '<tr> 
                        <td>'.$row["name"].'</td>
                        <td>'.translate_grade($row["grade"]).'</td>
                        <td><form action="" method="POST">
                                <input type="submit" name="'.$setBtn.'" value="Assign"/>
                            </form>
                        </td>
                    </tr>';

                $_SESSION["setInfo".$key] = $info; 
                $key++;
                $setBtn = "setBtn".$key;
            
            }
            $_SESSION['setKey'] = $key;
        }
    ?>
</table>
</body>
</html>

<?php

?>
