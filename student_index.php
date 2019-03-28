<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $id = $_SESSION["id"];
    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');

    if(isset($_SESSION['keyT']) && isset($_POST['ans'])) {
        for($i = 0; $i<$_SESSION['keyT']; $i++) {
            $cid = $_SESSION["info_ses".$i][0];
            $title = $_SESSION["info_ses".$i][1];
            $sec_id = $_SESSION["info_ses".$i][2];
            $ses_id = $_SESSION["info_ses".$i][3];
            $ses_date = $_SESSION["info_ses".$i][5];
            $stu_id = $_SESSION["info_ses".$i][6];
            $type = $_SESSION["info_ses".$i][7];

            if($_POST['ans'] == "participate" && $type == "mtor"){
                $query_insert_participate = "INSERT INTO participate VALUES('$stu_id','$cid','$title','$sec_id','$ses_id','$ses_date')";
            } else if($_POST['ans']=='participate' && $type == 'mtee'){
                $query_insert_participate = "INSERT INTO participate VALUES('$stu_id','$cid','$title','$sec_id','$ses_id','$ses_date')";
            }
            
            if($_POST['ans'] == "decline" && $type == 'mtor'){
                $query_insert_participate = "DELETE FROM participate WHERE stu_id = '$stu_id' AND cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND ses_id = '$ses_id'";
            } else if($_POST['ans'] == "decline" && $type == 'mtee'){
                $query_insert_participate = "DELETE FROM participate WHERE stu_id = '$stu_id' AND cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND ses_id = '$ses_id'";
            }

            if(isset($_POST["ansBtn".$i])) {    
                $check_parti = mysqli_query($con,"SELECT * FROM participate p WHERE p.cid = '$cid'
                AND p.title = '$title' AND p.sec_id = '$sec_id' AND p.ses_id = '$ses_id' 
                AND p.stu_id = '$stu_id'");
                if(mysqli_num_rows($check_parti) <= 0){
                    $result = mysqli_query($con, $query_insert_participate);
                }
                unset($_POST['ansBtn'.$i]);
            }
        }   
    }

    if(isset($_SESSION['keyM']) && isset($_POST['ansM'])>0) {
        for($i = 0; $i<$_SESSION['keyM']; $i++) {
            $cid = $_SESSION["info_ses_M".$i][0];
            $title = $_SESSION["info_ses_M".$i][1];
            $sec_id = $_SESSION["info_ses_M".$i][2];
            $ses_id = $_SESSION["info_ses_M".$i][3];
            $ses_date = $_SESSION["info_ses_M".$i][5];
            $stu_id = $_SESSION["info_ses_M".$i][6];
            $type = $_SESSION["info_ses_M".$i][7];

            if($_POST['ansM'] == "participate" && $type == "mtor"){
                $query_insert_participate = "INSERT INTO participate VALUES('$stu_id','$cid','$title','$sec_id','$ses_id','$ses_date')";
            } else if($_POST['ansM']=='participate' && $type == 'mtee'){
                $query_insert_participate = "INSERT INTO participate VALUES('$stu_id','$cid','$title','$sec_id','$ses_id','$ses_date')";
            }
            
            if($_POST['ansM'] == "decline" && $type == 'mtor'){
                $query_insert_participate = "DELETE FROM participate WHERE stu_id = '$stu_id' AND cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND ses_id = '$ses_id'";
            } else if($_POST['ansM'] == "decline" && $type == 'mtee'){
                $query_insert_participate = "DELETE FROM participate WHERE stu_id = '$stu_id' AND cid = '$cid' AND title = '$title' AND sec_id = '$sec_id' AND ses_id = '$ses_id'";
            }

            if(isset($_POST["ansBtnM".$i])) {  
                $check_parti = mysqli_query($con,"SELECT * FROM participate p WHERE p.cid = '$cid'
                AND p.title = '$title' AND p.sec_id = '$sec_id' AND p.ses_id = '$ses_id' 
                AND p.stu_id = '$stu_id'");
                if(mysqli_num_rows($check_parti) <= 0){  
                    $result = mysqli_query($con, $query_insert_participate);
                }
                unset($_POST['ansBtnM'.$i]);
            }
        }   
    }


    unset($_POST);
    unset($_SESSION['keyT']);
    unset($_SESSION['keyM']);
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
    Your grade is <?php echo translate_grade($_SESSION["grade"]);?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br><br>
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
        <td>Mentor/Mentee</td>
        <td>Mentor/Mentee</td>
        <td><a href=student_view_mtor.php title="studentMtor">View Mentor/Mentee</a></td>
    </tr>
</table>


<?php
//show Participate Notification

//mentor part
$query_mtor_in_teach = "SELECT * FROM teach t WHERE t.mtor_id = '$id' ";

$result_mtor_in_teach = mysqli_query($con,$query_mtor_in_teach);
if(mysqli_num_rows($result_mtor_in_teach) <= 0){
    echo '<h1> No notification for mentor next week </h1>';
} else {
    echo '<h1> Mentor Notification for next week </h1>';

    echo '<table border="1">
                <tr>
                    <th>Course Title</th>
                    <th>Section Name</th>
                    <th>Session Name</th>
                    <th>Session Date</th>
                    <th>Participate Mentee</th>
                    <th>Participate Mentor</th>
                    <th>Status</th>
                    <th>Participate/Decline</th>
                </tr>';

    //record participate decline ansBtn
    $key = 0;
    $ansBtn = "ansBtn".$key;
    //get ses which is from section that mtor will teach
    $query_get_ses_mtor = "SELECT * FROM sessions s NATURAL JOIN teach t WHERE t.mtor_id ='$id' ";
    $result_get_ses_mtor = mysqli_query($con, $query_get_ses_mtor);
    while($row = mysqli_fetch_array($result_get_ses_mtor)) {
        $cid = $row["cid"];
        $title = $row["title"];
        $sec_id = $row["sec_id"];
        $ses_id = $row["ses_id"];
        $ses_name = $row["name"];
        $ses_date = $row["date"];
        $mtor_id = $row["mtor_id"];
        
        //count participate mentor
        $query_cnt_parti_mtor = "SELECT COUNT(stu_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]' 
                AND p.stu_id IN (SELECT mtor_id from mentors)";
        //count participate mentee
        $query_cnt_parti_mtee = "SELECT COUNT(stu_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]'
                AND p.stu_id IN (SELECT mtee_id from mentees)";
        $result_mtor_cnt = mysqli_query($con, $query_cnt_parti_mtor);
        $mtor_cnt = mysqli_fetch_array($result_mtor_cnt);
        $result_mtee_cnt = mysqli_query($con, $query_cnt_parti_mtee);
        $mtee_cnt = mysqli_fetch_array($result_mtee_cnt);

        //store ses info
        $info = array(
            $cid,
            $title,
            $sec_id,
            $ses_id,
            $ses_name,
            $ses_date,
            $mtor_id,
            'mtor',
        );
        $info_ses_index = "info_ses".$key; 
        $_SESSION[$info_ses_index] = $info;

        //check session status
        if($mtee_cnt[0]>=3){
            $ses_status = "Open";
        } else {
            $ses_status = "Canceled";
        }

        echo '<tr>
                <td>'.$title.'</td>
                <td>'.$sec_id.'</td>
                <td>'.$ses_name.'</td>
                <td>'.$ses_date.'</td>
                <td>'.$mtor_cnt[0].'</td>
                <td>'.$mtee_cnt[0].'</td>
                <td>'.$ses_status.'</td>
                <td><form action="" method="POST">
                        <select name="ans">
                            <option value="participate">participate</option>
                            <option value="decline">decline</option>  
                        </select>
                        <input type="submit" name="'.$ansBtn.'" value=submit/>
                    </form>
                </td>
              </tr>';
        $key++;
        $ansBtn = "ansBtn".$key;
    }
    $_SESSION['keyT'] = $key;   
    echo '</table><br>';
}
//Mentee part
$query_mtee_in_enroll = "SELECT * FROM enroll e WHERE e.mtee_id = '$id' ";

$result_mtee_in_enroll = mysqli_query($con,$query_mtee_in_enroll);
if(mysqli_num_rows($result_mtee_in_enroll) <= 0){
    echo '<h1> No notification for mentee next week </h1>';
} else {
    echo '<h1> Mentee Notification for next week </h1>';

    echo '<table border="1">
                <tr>
                    <th>Course Title</th>
                    <th>Section Name</th>
                    <th>Session Name</th>
                    <th>Session Date</th>
                    <th>Participate Mentee</th>
                    <th>Participate Mentor</th>
                    <th>Status</th>
                    <th>Participate/Decline</th>
                </tr>';

    //record participate decline ansBtn
    $key = 0;
    $ansBtn = "ansBtnM".$key;
    //get ses which is from section that mtor will teach
    $query_get_ses_mtee = "SELECT * FROM sessions s NATURAL JOIN enroll e WHERE e.mtee_id ='$id' ";
    $result_get_ses_mtee = mysqli_query($con, $query_get_ses_mtee);
    while($row = mysqli_fetch_array($result_get_ses_mtee)) {
        $cid = $row["cid"];
        $title = $row["title"];
        $sec_id = $row["sec_id"];
        $ses_id = $row["ses_id"];
        $ses_name = $row["name"];
        $ses_date = $row["date"];
        $mtee_id = $row["mtee_id"];
        
        //count participate mentor
        $query_cnt_parti_mtor = "SELECT COUNT(stu_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]' 
                AND p.stu_id IN (SELECT mtor_id from mentors)";
        //count participate mentee
        $query_cnt_parti_mtee = "SELECT COUNT(stu_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]'
                AND p.stu_id IN (SELECT mtee_id from mentees)";
        $result_mtor_cnt = mysqli_query($con, $query_cnt_parti_mtor);
        $mtor_cnt = mysqli_fetch_array($result_mtor_cnt);
        $result_mtee_cnt = mysqli_query($con, $query_cnt_parti_mtee);
        $mtee_cnt = mysqli_fetch_array($result_mtee_cnt);

        //store ses info
        $info = array(
            $cid,
            $title,
            $sec_id,
            $ses_id,
            $ses_name,
            $ses_date,
            $mtee_id,
            'mtee',
        );
        $info_ses_index = "info_ses_M".$key; 
        $_SESSION[$info_ses_index] = $info;

        if($mtee_cnt[0]>=3){
            $ses_status = "Open";
        } else {
            $ses_status = "Canceled";
        }

        echo '<tr>
                <td>'.$title.'</td>
                <td>'.$sec_id.'</td>
                <td>'.$ses_name.'</td>
                <td>'.$ses_date.'</td>
                <td>'.$mtor_cnt[0].'</td>
                <td>'.$mtee_cnt[0].'</td>
                <td>'.$ses_status.'</td>
                <td><form action="" method="POST">
                        <select name="ansM">
                            <option value="participate">participate</option>
                            <option value="decline">decline</option>  
                        </select>
                        <input type="submit" name="'.$ansBtn.'" value=submit/>
                    </form>
                </td>
              </tr>';
        $key++;
        $ansBtn = "ansBtnM".$key;
    }
    $_SESSION['keyM'] = $key;   
    echo '</table><br>';
}
?>

</body>
</html>