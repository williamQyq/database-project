<?php
    include 'config.php';
    include 'translate.php';
    session_start();

    $con = mysqli_connect($host, $username) or die('Unable To connect');
    $mydb = mysqli_select_db($con, $database) or die ('could not select database');

    // current uid
    $id = $_SESSION["id"];
    //select child info--------------------------
    $query_view_child = "SELECT u.uid, u.emailAddress, u.name, u.phoneNumber FROM users u, custody c 
                         WHERE u.uid = c.stu_id AND c.par_id = '$id'";
    $result = mysqli_query($con, $query_view_child);
     
?>
<html>
<head>
<title>Your Children List</title>
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
<h1>Your Children List</h1><br>
<table border="1">
    <tr>
        <th>User ID </th>
        <th>Name </th>
        <th>Email </th>
        <th>Phone </th>
        <th>Change Profile </th>
        
    </tr>
    <?php
        $key = 0;
        $altBtn = "altBtn".$key;
        $result = mysqli_query($con,$query_view_child);
        if(mysqli_num_rows($result)>0) {
            while($row = mysqli_fetch_array($result)) {
                echo '<tr> 
                        <td>'.$row["uid"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row["emailAddress"].'</td>
                        <td>'.$row["phoneNumber"].'</td>
                        <td><form action="parent_alt_child.php" method="POST">
                                <input type="submit" name="'.$altBtn.'" value="change"/>
                            </form>
                        </td>
                    </tr>';
                $key++;
                $altBtn = "altBtn".$key;
            }

            //store section info---------------
            $info = array(
                $row["uid"],
                $row["name"],
                $row["emailAddress"],
                $row["phoneNumber"],
            );
           
            $_SESSION["info".$key] = $info;
            $_SESSION['key'] = $key;
        }
    ?>
</table>
</body>
</html>

<?php

?>
