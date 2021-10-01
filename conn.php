<?php
$host = "localhost";
$user = "admin";
$pass = "12345";
$con = mysqli_connect($host,$user,$pass,"test");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}else{
    echo "connected";
}
?>