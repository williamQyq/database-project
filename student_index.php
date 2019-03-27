<?php
    include 'config.php';
    include 'translate.php';
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

$id = $_SESSION["id"];
$con = mysqli_connect($host, $username) or die('Unable To connect');
$mydb = mysqli_select_db($con, $database) or die ('could not select database');

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
            $mtor_id
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
                        <input type="submit" name="'.$ansBtn.'" value="submit"/>
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