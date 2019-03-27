<?php
    include 'config.php';
   
    session_start();
    $message="";
    $row=NULL;
    if(count($_POST)>0) {
        //db connection no password.
        $con = mysqli_connect($host,$username) or die('Unable to connect');
        $mydb = mysqli_select_db($con, $database) or die ('could not select database');
        
        if(isset($_POST['email'])){
            $query_check_duplicate = "SELECT * FROM users WHERE username = '" . $_POST["email"] . "' ";
            $result = mysqli_query($con,$query_check_duplicate);
            $row  = mysqli_fetch_array($result);
        }
        if(is_array($row)) {
            $message = "This email has been registered! Please change your email.";
        } else if(isset($_POST['email'])) {
            //create short variable names
            $email = $_POST['email'];
            $parentEmail = $_POST['parentEmail'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $grade = $_POST['grade'];
            $name = $_POST['name'];
            $phoneNumber = $_POST['phoneNumber'];
            
            $query_check_par = "SELECT emailAddress FROM users WHERE users.emailAddress = '$parentEmail'";
            
            $result = mysqli_query($con, $query_check_par);
            if(mysqli_num_rows($result)<=0){
                $message = "Fail to insert Parent Children relation. Do you enter parent email correctly? ";
            }else{
                mysqli_free_result($result);
                //insert users table
                $query_insert_user = "INSERT INTO users (username, password, name, emailAddress, phoneNumber) VALUES ('$email', '$password', '$name', '$email', '$phoneNumber')";
                $result = mysqli_query($con, $query_insert_user) or die ('Query failed: ' . mysql_error());
                if(!$result) {
                    trigger_error('query failed',E_USER_ERROR);
                } 
                //get auto increment uid from insert user table 
                $user_id = mysqli_insert_id($con);
                
                //insert students table
                $update_stu_query = "INSERT INTO students VALUES('$user_id','$grade')";
                $sql = mysqli_query($con, $update_stu_query);
                if(!$sql) {
                    trigger_error('query failed',E_USER_ERROR);
                }
                //insert custody table
                $query_insert_custody = "INSERT INTO custody (par_id, stu_id) SELECT u.uid, s.stu_id FROM users u, students s
                                        WHERE u.emailAddress = '$parentEmail' AND s.stu_id = '$user_id'";
                $sql_custody = mysqli_query($con, $query_insert_custody);
                if(!$sql_custody) {
                    trigger_error('query failed',E_USER_ERROR);
                }

                //insert mtor mtee table
                $update_mtor_query = "INSERT INTO mentors VALUES('$user_id')";
                $update_mtee_query = "INSERT INTO mentees VALUES('$user_id')"; 
                if($role == 'mentee') {
                    $sql_mtee = mysqli_query($con, $update_mtee_query);
                } else if($role == 'mentor') {
                    $sql_mtor = mysqli_query($con, $update_mtor_query);
                } else if($role == 'both') {
                    $sql_mtor = mysqli_query($con, $update_mtor_query);
                    $sql_mtee = mysqli_query($con, $update_mtee_query);
                }
                    header("Location:parent_alt_child_prof.php");
            }
        }
    }

?>

<html>
    <head>
        <title>Change Your Student Info</title>
    </head>
    <body>
        <h1> Change Your Student Info</h1>
        <div><?php if($message!="") { echo $message;} ?></div>
        <form action="" method="post">
           <table>
               <tr>
                    <td><input type="text" name="email" placeholder="enter children new email"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="parentEmail" placeholder="enter parent email"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="password" placeholder="enter password"/></td>
                </tr>
                <tr>
                    <td>Role:</td>    
                    <td>
                        <select name="role">
                        <option value="noRole">no role</option>
                        <option value="mentee">mentee</option>
                        <option value="mentor">mentor</option>
                        <option value="both">both</option>   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Grade:</td>
                    <td>    
                    <select name="grade">
                        <option value="1">Freshman</option>
                        <option value="2">Sophmore</option>
                        <option value="3">Junior</option>
                        <option value="4">Senior</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="name" placeholder="enter student new name"/></td>
                </tr>
                <tr>
                    <td><input type="tel" name="phoneNumber" placeholder="enter student new phone number"/></td>
                </tr>
                <tr>
                    <td><input type="submit" value="submit"/></td>
                </tr>
            </table>
        </form>
        <a href="home.html">go to home</a>
    </body>
</html>
