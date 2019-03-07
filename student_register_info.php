<?php
    //create short variable names
    $email = $_POST['email'];
    $parentEmail = $_POST['parentEmail'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $grade = $_POST['grade'];
    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    
    $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysql_error());

  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

  $query_insert_user = "INSERT INTO users (username, password, name, emailAddress, phoneNumber) VALUES ('$email', '$password', '$name', '$email', '$phoneNumber')";
  $result = mysqli_query($myconnection, $query_insert_user) or die ('Query failed: ' . mysql_error());

  if(!$result) {
    trigger_error('query failed',E_USER_ERROR);
  } else {
    $user_id = mysqli_insert_id($myconnection);
    $update_mtor_query = "INSERT INTO mentors VALUES('$user_id')";
    $update_mtee_query = "INSERT INTO mentees VALUES('$user_id')"; 
  }

  $update_stu_query = "INSERT INTO students VALUES('$user_id','$grade')";
  $sql = mysqli_query($myconnection, $update_stu_query);
  if(!$sql) {
    trigger_error('query failed',E_USER_ERROR);
  }
  
  if($role == 'mentee') {
    $sql_mtee = mysqli_query($myconnection, $update_mtee_query);
  } else if($role == 'mentor') {
    $sql_mtor = mysqli_query($myconnection, $update_mtor_query);
  } else if($role == 'both') {
    $sql_mtor = mysqli_query($myconnection, $update_mtor_query);
    $sql_mtee = mysqli_query($myconnection, $update_mtee_query);
  }
  
  // if(!$sql_mtee) {
  //   trigger_error('query failed',E_USER_ERROR);
  // }

  echo "Welcome student: {$name}";
  echo '<br>';
  echo 'You have successfully registered';
  echo '<br>';

  mysqli_close($myconnection);

?>