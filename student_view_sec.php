<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $query_view_sec = "SELECT * FROM courses NATURAL JOIN sections_belong NATURAL JOIN timeSlot";

    $key = 0;
    $teachBtn = "teachBtn".$key;
    $enrollBtn = "enrollBtn".$key;
    $message = "";

    if(isset($_SESSION['key'])) {
        for($i = 0; $i<$_SESSION['key']; $i++) {
            $cid = $_SESSION["info".$i][0];
            $title = $_SESSION["info".$i][1];
            $sec_id = $_SESSION["info".$i][2];
            $req_mtor = $_SESSION["info".$i][3];
            $req_mtee = $_SESSION["info".$i][4];
            $id = $_SESSION["id"];
            if(isset($_POST['teachBtn'.$i])) {     
                //teach button $i is clicked 
                //store info into teach table
                if($_SESSION["grade"]>= $req_mtor) {
                    $query_insert_teach = "INSERT INTO teach(cid, title, sec_id, mtor_id) VALUES('$cid','$title','$sec_id','$id')";
                    mysqli_query($con,$query_insert_teach);
                    $message = "Successfully!";
                } else {
                    $message = "You can't do that! You do not meet the requirement!";
                }
                unset($_POST['teachBtn'.$i]);
            }
            if(isset($_POST['enrollBtn'.$i])) {
                //enroll button $i is clicked
                //store info into enroll table
                if($_SESSION["grade"]>= $req_mtee) {
                    $query_insert_enroll = "INSERT INTO enroll(cid, title, sec_id, mtee_id) VALUES('$cid','$title','$sec_id','$id')";
                    mysqli_query($con,$query_insert_enroll);
                    $message = "Succesfully!";
                } else{
                    $message = "You can;t do that! You do not meet the requirement!";
                }
                unset($_POST['enrollBtn'.$i]);
            }
        }   
    }
     
?>
<html>
<head>
<title>Section List</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome <?php echo $_SESSION["name"]; ?>.<br>
    Your grade is <?php echo translate_grade($_SESSION["grade"]);?>.<br>
    Click here to <a href="student_logout.php" tite="studentLogout">Logout</a>.<br>
    <a href="student_index.php">go back</a><br><br>
<?php
    }else {
?>
    <h1>Please <a href="student_login.php" title="studentLogin">login</a> first.</h1><br>
<?php    
    }
?>
<h1>Section List</h1><br>
<?php
    if($message != "") {echo $message;}
?>
<table border="1">
    <tr>
        <th>Course Title </th>
        <th>Section Name </th>
        <th>Start Date </th>
        <th>End Date </th>
        <th>Time Slot </th>
        <th>Capacity </th>
        <th>Mentor Req </th>
        <th>Mentee Req </th>
        <th>Enrolled Mentor </th>
        <th>Enrolled Mentee </th>
        <th>Teach as Mentor </th>
        <th>Enroll as Mentee </th>
    </tr>
    <?php
        $result = mysqli_query($con,$query_view_sec);
        if($result != NULL) {
            while($row = mysqli_fetch_array($result)) {
                //count enrolled mtor in section
                $query_count_mtor = "SELECT COUNT(mtor_id) FROM teach WHERE teach.cid = '$row[cid]'
                AND teach.title = '$row[title]' AND teach.sec_id = '$row[sec_id]'";
                //count enrolled mtee in section
                $query_count_mtee = "SELECT COUNT(mtee_id) FROM enroll WHERE enroll.cid = '$row[cid]'
                AND enroll.title = '$row[title]' AND enroll.sec_id = '$row[sec_id]'";
                
                if($result_cnt_mtor = mysqli_query($con,$query_count_mtor)){
                    $mtor_cnt = mysqli_fetch_array($result_cnt_mtor);
                }else{
                    $mtor_cnt[0] = '0';
                }
                //free $result_cnt_mtor
                mysqli_free_result($result_cnt_mtor);
                
                if($result_cnt_mtee = mysqli_query($con,$query_count_mtee)){
                    $mtee_cnt = mysqli_fetch_array($result_cnt_mtee);
                }else{
                    $mtee_cnt[0] = '0';
                }
                //free result
                mysqli_free_result($result_cnt_mtee);
                
                //store section info---------------
                $info = array(
                    $row["cid"],
                    $row["title"],
                    $row["sec_id"],
                    $row["mtors_req"],
                    $row["mtees_req"]
                );
                $info_index = "info".$key; 
                $_SESSION[$info_index] = $info;

                echo '<tr> 
                        <td>'.$row["title"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row["startDate"].'</td>
                        <td>'.$row["endDate"].'</td>
                        <td>'.$row["weekDay"].$row["startTime"].'-'.$row["endTime"].'</td>
                        <td>'.$row["capacity"].'</td>
                        <td>'.translate_grade($row["mtors_req"]).'</td>
                        <td>'.translate_grade($row["mtees_req"]).'</td> 
                        <td>'.$mtor_cnt[0].'</td>
                        <td>'.$mtee_cnt[0].'</td>
                        <td><form action="" method="POST">
                                <input type="submit" name="'.$teachBtn.'" value="teach"/>
                            </form>
                        </td>
                        <td><form action= "" method="POST">
                                <input type="submit" name="'.$enrollBtn.'" value="enroll"/>
                            </form>
                        </td>
                    </tr>';
                $key++;
                $teachBtn = "teachBtn".$key;
                $enrollBtn = "enrollBtn".$key;
            }
            $_SESSION['key'] = $key;
        }
    ?>
</table>
</body>
</html>

<?php

?>
