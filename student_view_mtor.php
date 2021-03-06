<?php
    include 'config.php';
    include 'translate.php';
    session_start();
?>
<html>
<head>
<title>mentor view</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome <?php echo $_SESSION["name"]; ?>.<br>
    Your grade is <?php echo translate_grade($_SESSION["grade"]);?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br>
    <a href="student_index.php">go back</a><br><br>
<?php
    }else {
?>
    <h1>Please <a href="student_login.php" title="studentLogin">login</a> first.</h1><br>
<?php    
    }
    $id = $_SESSION["id"];
    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');
    $query_select_sec = "SELECT * FROM sections_belong s NATURAL JOIN teach t WHERE t.mtor_id = '$id'
                         UNION
                         SELECT * FROM sections_belong s NATURAL JOIN enroll e WHERE e.mtee_id = '$id'";

    $result_select_sec = mysqli_query($con,$query_select_sec);
    if(mysqli_num_rows($result_select_sec)<=0){
        echo '<h1> No section found! </h1>';
    } else {
        while($row = mysqli_fetch_array($result_select_sec)) {
            $cid = $row["cid"];
            $title = $row["title"];
            $sec_id = $row["sec_id"];
            $sec_name = $row["name"];
            
            //select mtors query
            $query_select_mtors = "SELECT name, grade FROM users NATURAL JOIN students 
            WHERE users.uid = students.stu_id AND users.uid IN (
                SELECT teach.mtor_id FROM teach WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id'
            )";
            //select mtees query
            $query_select_mtees = "SELECT DISTINCT users.name, students.grade FROM users, students, enroll 
            WHERE users.uid IN (
                SELECT enroll.mtee_id FROM enroll WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id'
            ) AND students.stu_id = users.uid";
            
            echo '<table border="1">
                    <tr>'.$title.' '.$sec_name.'</tr>
                    <tr>
                        <th>student name</th>
                        <th>grade</th>
                        <th>role</th>
                    </tr>';
                    
                    //print out mentors
                    echo '<tr>
                            <th>mentors</th>
                          </tr>';
                    $result_mtors = mysqli_query($con, $query_select_mtors);
                    if(mysqli_num_rows($result_mtors)>0){
                        while($row_mtors = mysqli_fetch_array($result_mtors)){
                            echo '<tr>
                                    <td>'.$row_mtors["name"].'</td>
                                    <td>'.translate_grade($row_mtors["grade"]).'</td>
                                    <td>mentor</td>
                                  </tr>';
                        }
                    } else{
                        echo '<tr>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                              </tr>';
                    }
                    //print out mentees
                    echo '<tr>
                            <th>mentees</th>
                          </tr>';
                    $result_mtees = mysqli_query($con, $query_select_mtees);
                    if(mysqli_num_rows($result_mtees)>0){
                        while($row_mtees = mysqli_fetch_array($result_mtees)){
                            echo '<tr>
                                    <td>'.$row_mtees["name"].'</td>
                                    <td>'.translate_grade($row_mtees["grade"]).'</td>
                                    <td>mentee</td>
                                  </tr>';
                        }
                    }else{
                        echo '<tr>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                              </tr>';
                    }
                    

            echo '</table><br>';
            
        }
    }
?>
</body>
</html>