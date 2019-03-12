<?php
//this is a combined queries file.

//student_login.php
$query_select_user = "SELECT * FROM users WHERE username = '" . $_POST["username"] . "' and password = '" . $_POST["password"] . "'";

//student_register_info.php
$query_insert_user = "INSERT INTO users (username, password, name, emailAddress, phoneNumber) VALUES ('$email', '$password', '$name', '$email', '$phoneNumber')";
$update_mtor_query = "INSERT INTO mentors VALUES('$user_id')";
$update_mtee_query = "INSERT INTO mentees VALUES('$user_id')"; 
$update_stu_query = "INSERT INTO students VALUES('$user_id','$grade')";

//student_register.php
$query_check_duplicate = "SELECT * FROM users WHERE username = '" . $_POST["email"] . "' ";
?>