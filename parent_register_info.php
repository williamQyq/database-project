<?php
    include 'config.php';

    //start session
    session_start();

    //create short variable names
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $role = $_SESSION['role'];
    $name = $_SESSION['name'];
    $phoneNumber = $_SESSION['phoneNumber'];
    
    //build connection no password
    $con = mysqli_connect($host, $username) or die ('Could not connect: ' . mysql_error());
    $mydb = mysqli_select_db ($con, $database) or die ('Could not select database');

    $query_insert_user = "INSERT INTO users (username, password, name, emailAddress, phoneNumber) VALUES ('$email', '$password', '$name', '$email', '$phoneNumber')";
    $result = mysqli_query($con, $query_insert_user) or die ('Query failed: ' . mysql_error());

    if(!$result) {
      trigger_error('query failed',E_USER_ERROR);
    } else {
      $user_id = mysqli_insert_id($con);
      $update_modtor_query = "INSERT INTO moderators VALUES('$user_id')";
    }

    $update_prt_query = "INSERT INTO parents VALUES('$user_id')";
    $sql = mysqli_query($con, $update_prt_query);
    if(!$sql) {
      trigger_error('query failed',E_USER_ERROR);
    }
  
  if($role == 'moderator') {
    $sql_mtee = mysqli_query($con, $update_modtor_query);
  } 
  
  // if(!$sql_mtee) {
  //   trigger_error('query failed',E_USER_ERROR);
  // }

  echo "Welcome parent: {$name}";
  echo '<br>';
  echo 'You have successfully registered';
  echo '<br>';

  mysqli_close($con);

?>

<?php
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
?>

<a href="home.html">go to login</a>