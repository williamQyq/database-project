<?php
    include 'config.php';
    session_start();
    if(isset($_SESSION['key'])) {
        unset($_SESSION['key']);
    }

    $id = $_SESSION["id"];
    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');



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
        <td><a href=parent_alt_child_prof.php title="parentChildProfile">Change Your children Profile</a></td>
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

<?php 
    //selec mentor less than 2 sessions
    $query_select_ses_with_less_2_mtor = "SELECT m.cid, m.title, m.sec_id, m.ses_id FROM
                                   (SELECT t.mtor_id,s.cid,s.title,s.sec_id,s.ses_id
                                    FROM teach t NATURAL JOIN sessions s 
                                    WHERE t.cid = s.cid AND t.title = s.title AND t.sec_id = s.sec_id) AS m
                             GROUP BY m.cid, m.title, m.sec_id, m.ses_id
                             HAVING COUNT(m.mtor_id)<2";

    $result = mysqli_query($con,$query_select_ses_with_less_2_mtor);
    if(mysqli_num_rows($result)>0){
        echo '<h1> Moderator Notification </h1>';
        echo '<h3> sessions with less than 2 mentors</h3>';
        echo '<table border="1">
            <tr>
                <th>Course ID</th>
                <th>Course Title</th>
                <th>Section ID</th>
                <th>Session ID</th>
            </tr>';
    
        while($row = mysqli_fetch_array($result)){
            echo '<tr>
                    <td>'.$row["cid"].'</td>
                    <td>'.$row["title"].'</td>
                    <td>'.$row["sec_id"].'</td>
                    <td>'.$row["ses_id"].'</td>
                  </tr>';
        }
        echo '</table>';
    }
?>


</body>
</html>