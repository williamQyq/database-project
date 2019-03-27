<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $query_view_sec = "SELECT * FROM courses NATURAL JOIN sections_belong NATURAL JOIN timeSlot";

    $message = "";

    if(isset($_SESSION['key'])) {
        for($i = 0; $i<$_SESSION['key']; $i++) {
            $cid = $_SESSION["info".$i][0];
            $title = $_SESSION["info".$i][1];
            $sec_id = $_SESSION["info".$i][2];
            $mtor_num = $_SESSION["info".$i][3];
            $mtee_num = $_SESSION["info".$i][4];
            $mdtor_num = $_SESSION["info".$i][5];//---------------------------------------------
            $id = $_SESSION["id"];

            $query_check_moderate = "SELECT * FROM moderate WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND mdtor_id = '$id'";
          
            if(isset($_POST['modBtn'.$i])) {     
                //check duplicate
                
                $result = mysqli_query($con,$query_check_moderate);
                // $row  = mysqli_fetch_array($result);
                if(mysqli_num_rows($result)>0){
                    $message = "Fail to do that, You have moderated this section!";
                } else {
                    //mod button $i is clicked 
                    //store info into moderate table
                    $query_insert_moderate = "INSERT INTO moderate(cid, title, sec_id, mdtor_id) VALUES('$cid','$title','$sec_id','$id')";
                    mysqli_query($con,$query_insert_moderate);
                    $message = "Successfully!";
                }
                //unset button  
                unset($_POST['modBtn'.$i]);  
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
    Welcome Parent <?php echo $_SESSION["name"]; ?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br>
    <a href="parent_index.php">go back</a><br><br>
<?php
    }else {
?>
    <h1>Please <a href="parent_login.php" title="parentLogin">login</a> first.</h1><br>
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
        <th>Enrolled Moderator</th>
        <th>Moderate as Morderator </th>
    </tr>
    <?php

        $key = 0;
        $modBtn = "modBtn".$key;
        $result = mysqli_query($con,$query_view_sec);
        if(mysqli_num_rows($result)>0) {
            while($row = mysqli_fetch_array($result)) {
                //count enrolled mtor in section
                $query_count_mtor = "SELECT COUNT(mtor_id) FROM teach WHERE teach.cid = '$row[cid]'
                AND teach.title = '$row[title]' AND teach.sec_id = '$row[sec_id]'";
                //count enrolled mtee in section
                $query_count_mtee = "SELECT COUNT(mtee_id) FROM enroll WHERE enroll.cid = '$row[cid]'
                AND enroll.title = '$row[title]' AND enroll.sec_id = '$row[sec_id]'";
                //count moderator insection
                $query_count_mdtor = "SELECT COUNT(mdtor_id) FROM moderate WHERE moderate.cid = '$row[cid]'
                AND moderate.title = '$row[title]' AND moderate.sec_id = '$row[sec_id]'";

                if($result_cnt_mtor = mysqli_query($con,$query_count_mtor)){
                    $mtor_cnt = mysqli_fetch_array($result_cnt_mtor);
                }else{
                    $mtor_cnt[0] = 0;
                }
                //free $result_cnt_mtor
                mysqli_free_result($result_cnt_mtor);
                
                if($result_cnt_mtee = mysqli_query($con,$query_count_mtee)){
                    $mtee_cnt = mysqli_fetch_array($result_cnt_mtee);
                }else{
                    $mtee_cnt[0] = 0;
                }
                //free result
                mysqli_free_result($result_cnt_mtee);

                if($result_cnt_mdtor = mysqli_query($con,$query_count_mdtor)){
                    $mdtor_cnt = mysqli_fetch_array($result_cnt_mdtor);
                }else{
                    $mdtor_cnt[0] = 0;
                }
                //free result
                mysqli_free_result($result_cnt_mdtor);
                
                //store section info---------------
                $info = array(
                    $row["cid"],
                    $row["title"],
                    $row["sec_id"],
                    $row["mtors_req"],
                    $row["mtees_req"],
                    $mtor_cnt[0],
                    $mtee_cnt[0],
                    $mdtor_cnt[0],
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
                        <td>'.$mdtor_cnt[0].'</td>
                        <td><form action="" method="POST">
                                <input type="submit" name="'.$modBtn.'" value="moderate"/>
                            </form>
                        </td>
                    </tr>';

                $key++;
                $modBtn = "modBtn".$key;
            }
            $_SESSION['key'] = $key;
        }
    ?>
</table>
</body>
</html>

<?php

?>
