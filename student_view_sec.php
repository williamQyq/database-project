<?php
    include 'config.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $query_view_sec = "SELECT * FROM courses NATURAL JOIN sections_belong";
    
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
    Your grade is <?php echo $_SESSION["grade"];?>.<br>
    Click here to <a href="student_logout.php" tite="studentLogout">Logout</a>.<br><br>
<?php
    }else {
?>
    <h1>Please <a href="student_login.php" title="studentLogin">login</a> first.</h1><br>
<?php    
    }
?>
<h1>Section List</h1><br>
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
                echo '<tr> 
                        <td>'.$row["title"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row["startDate"].'</td>
                        <td>'.$row["endDate"].'</td>
                        <td>NA</td>
                        <td>'.$row["capacity"].'</td>
                        <td>'.$row["mtors_req"].'</td>
                        <td>'.$row["mtees_req"].'</td> 
                        <td>NA</td>
                        <td>NA</td>
                        <td><form action= "section_add_mentor.php">
                                <input type="submit" value="add mentor"/>
                            </form>
                        </td>
                        <td><form action= "section_add_mentee.php">
                                <input type="submit" value="add mentee"/>
                            </form>
                        </td>
                    </tr>';
            }
        }
    ?>
</table>

</body>
</html>