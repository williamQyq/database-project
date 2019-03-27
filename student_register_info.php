<?php
    include 'config.php';

    //start session
    session_start();

    //create short variable names
    $email = $_SESSION['email'];
    $parentEmail = $_SESSION['parentEmail'];
    $password = $_SESSION['password'];
    $role = $_SESSION['role'];
    $grade = $_SESSION['grade'];
    $name = $_SESSION['name'];
    $phoneNumber = $_SESSION['phoneNumber'];
    
    //build connection no password
    $con = mysqli_connect($host, $username) or die ('Could not connect: ' . mysql_error());
    $mydb = mysqli_select_db ($con, $database) or die ('Could not select database');

    $query_check_par = "SELECT emailAddress FROM users WHERE users.emailAddress = '$parentEmail'";
    $result = mysqli_query($con, $query_check_par);
    if(mysqli_num_rows($result)<=0){
      echo "Fail to Register. Do you enter Parent Email correctly? ";
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
    
      echo "Welcome student: {$name}";
      echo '<br>';
      echo 'You have successfully registered';
      echo '<br><br>';
    }
  mysqli_close($con);

?>

<?php
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
?>

<a href="home.html">go to home page</a>