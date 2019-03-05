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

  echo "Welcome student: {$name}";
  echo '<br>';
  echo 'You have successfully registered';
  echo '<br>';

  mysqli_close($myconnection);

?>