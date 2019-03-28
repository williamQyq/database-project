<?php
    include 'config.php';
    include 'translate.php';
    session_start();
?>
<html>
<head>
<title>moderator view</title>
</head>
<body>

<?php
    if($_SESSION["name"]) {
?>
    Welcome Moderator <?php echo $_SESSION["name"]; ?>.<br>
    Click here to <a href="logout.php" tite="Logout">Logout</a>.<br>
    <a href="parent_index.php">go back</a><br><br>
<?php
    }else {
?>
    <h1>Please <a href="parent_login.php" title="parentLogin">login</a> first.</h1><br>
<?php    
    }
    //user id
    $id = $_SESSION["id"];
    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');

    //for asign btn
    $key = 0;
    $assignBtn = "assBtn".$key;
    $postBtn = "postBtn".$key;

    $query_select_sec = "SELECT * FROM sections_belong";
    $result_select_sec = mysqli_query($con,$query_select_sec);
    if(mysqli_num_rows($result_select_sec)<=0){
        echo '<h1> No section found! </h1>';
    } else {
        while($row = mysqli_fetch_array($result_select_sec)) {
            $cid = $row["cid"];
            $title = $row["title"];
            $sec_id = $row["sec_id"];
            $sec_name = $row["name"];

            //select mdtors query
            $query_select_mdtors = "SELECT name FROM users NATURAL JOIN moderators m
            WHERE users.uid = m.mdtor_id AND users.uid IN (
                SELECT moderate.mdtor_id FROM moderate WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id'
            )";
            //select mtors query
            $query_select_mtors = "SELECT name FROM users NATURAL JOIN mentors mt 
            WHERE users.uid = mt.mtor_id AND users.uid IN (
                SELECT teach.mtor_id FROM teach WHERE cid = '$cid' AND title = '$title' AND sec_id = '$sec_id'
            )";


            echo '<table border="1">
                    <tr>'.$title.' '.$sec_name.'</tr>
                    <tr>
                        <th>moderator name</th>
                        <th>role</th>
                        <th> 
                            <form action="assign_mentor.php" method="POST">
                                <input type="submit" name="'.$assignBtn.'" value="asign mentor"/>
                            </form>
                        </th>
                        <th> 
                            <form action="post_material.php" method="POST">
                                <input type="submit" name="'.$postBtn.'" value="post material"/>
                            </form>
                        </th>
                        
                    </tr>';
                    
                    //print out moderators
                    echo '<tr>
                            <th>moderators</th>
                          </tr>';
                    $result_mdtors = mysqli_query($con, $query_select_mdtors);
                    if(mysqli_num_rows($result_mdtors)>0){
                        while($row_mdtors = mysqli_fetch_array($result_mdtors)){
                            echo '<tr>
                                    <td>'.$row_mdtors["name"].'</td>
                                    <td>moderator</td>
                                  </tr>';
                        }
                    } else{
                        echo '<tr>
                                    <td>N/A</td>
                                    <td>N/A</td>
                              </tr>';
                    }

                    //print out mentors
                    echo '<tr>
                            <th>mentors</th>
                          </tr>';
                    $result_mtors = mysqli_query($con, $query_select_mtors);
                    if(mysqli_num_rows($result_mtors)>0){
                        while($row_mtors = mysqli_fetch_array($result_mtors)){
                          echo '<tr>
                                    <td>'.$row_mtors["name"].'</td>
                                    <td>mentor</td>
                                </tr>';
                        }
                     } else{
                            echo '<tr>
                                      <td>N/A</td>
                                      <td>N/A</td>
                                  </tr>';
                    }

            echo '</table><br>';
            //save info for assgin 
            $info = array(
                $cid,
                $title,
                $sec_id
            );
            $_SESSION["assinfo".$key] = $info;
            $key++;
            $assignBtn = "assBtn".$key;
            $postBtn = "posBtn".$key;
        }
        $_SESSION['postkey'] = $key;
        $_SESSION['asskey'] = $key;
    }
?>
</body>
</html>