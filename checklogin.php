<?php
session_start();
$username=$_GET['username'];
$password=$_GET['password'];



if($username == 'test' && $password == 'test') {
  $_SESSION['username'] = $username;
  $_SESSION['password'] = $password;
  $redirect=$_GET['redirect'];

  header("Location: $redirect");
  die;
}
else {
  echo "Wrong Username or Password";
}
?>
