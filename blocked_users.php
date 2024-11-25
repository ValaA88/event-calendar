<?php

require_once('./db_connect_mamp.php');

if(isset($_GET['id'])){
  $id = $_GET['id'];
}

$sqlBlocked = "UPDATE `users` SET `is_blocked`='1' WHERE users.id = {$id}";
if(mysqli_query($conn, $sqlBlocked)){
  header("location: allUsers.php");
} else {
  echo "something went wrong";
}