<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $id = $_SESSION["id"];
    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');

    if(isset($_SESSION['key']) && count($_POST)>0) {
        for($i = 0; $i<$_SESSION['key']; $i++) {
            $cid = $_SESSION["info_ses".$i][0];
            $title = $_SESSION["info_ses".$i][1];
            $sec_id = $_SESSION["info_ses".$i][2];
            $ses_id = $_SESSION["info_ses".$i][3];
            $ses_date = $_SESSION["info_ses".$i][5];
            $mtor_id = $_SESSION["info_ses".$i][6];
            $type = $_SESSION["info_ses".$i][7];
            
            $invalid_mtee_id = '-1';

            if($_POST["ans"] == "participate" && $type == "mtor"){
                $query_insert_participate = "INSERT INTO participate VALUES('$invalid_mtee_id','$mtor_id',$cid','$title','$sec_id','$ses_id','$ses_date')";
            // } else if($_POST['ans']=='participate' && $type == 'mtee'){
            //     $query_insert_participate = "INSERT INTO participate
            //                                  VALUES('$mtee_id','-1',$cid','$title','$sec_id','$ses_id','$ses_date')";
            }
            
            if($_POST['ans']=='decline' && $type == 'mtor'){
                $query_insert_participate = "DELETE FROM participate p 
                                             WHERE p.mtor_id = '$mtor_id' AND p.cid = '$cid' AND p.title = '$title' AND p.sec_id = '$sec_id' AND p.ses_id = '$ses_id' ";
            // } else if($_POST['ans']=='decline' && $type == 'mtee'){
            //     $query_insert_participate = "DELETE FROM participate p 
            //                                  WHERE p.mtee_id = '$mtee_id' AND p.cid = '$cid' AND p.title = '$title' AND p.sec_id = '$sec_id' AND p.ses_id = '$ses_id' ";
            }

            if(isset($_POST["ansBtn".$i])) {    
                echo $_POST["ansBtn".$i];
                echo var_dump($ses_date);
                $result = mysqli_query($con, $query_insert_participate);
                echo $result;
                if($result){
                    echo "yeyeyeyeye";
                }else {echo 'no';}
                unset($_POST['ansBtn'.$i]);
            }
        }   
    }
    unset($_POST);
    unset($_SESSION['key']);
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


<?php
//show Participate Notification

//mentor part
$query_mtor_in_teach = "SELECT * FROM teach t WHERE t.mtor_id = '$id' ";

$result_mtor_in_teach = mysqli_query($con,$query_mtor_in_teach);
if(mysqli_num_rows($result_mtor_in_teach) <= 0){
    echo '<h1> No notification for next week </h1>';
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
        $query_cnt_parti_mtor = "SELECT COUNT(mtor_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]'";
        //count participate mentee
        $query_cnt_parti_mtee = "SELECT COUNT(mtee_id) FROM participate p WHERE p.cid = '$row[cid]'
                AND p.title = '$row[title]' AND p.sec_id = '$row[sec_id]' AND p.ses_id = '$row[ses_id]'";
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

        echo '<tr>
                <td>'.$title.'</td>
                <td>'.$sec_id.'</td>
                <td>'.$ses_name.'</td>
                <td>'.$ses_date.'</td>
                <td>'.$mtor_cnt[0].'</td>
                <td>'.$mtee_cnt[0].'</td>
                <td><form action="" method="POST">
                        <select name="ans">
                            <option value="participate">participate</option>
                            <option value="decline">decline</option>  
                        </select>
                        <input type="submit" name="'.$ansBtn.'" value="'.$ansBtn.'"/>
                    </form>
                </td>
              </tr>';
        $key++;
        $ansBtn = "ansBtn".$key;
    }
    $_SESSION['key'] = $key;   
    echo '</table><br>';
}
?>

</body>
</html>