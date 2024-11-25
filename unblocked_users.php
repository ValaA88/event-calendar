<?php

require_once('./db_connect_mamp.php');

if(isset($_GET['id'])){
  $id = $_GET['id'];
}

$sqlUnblocked = "UPDATE `users` SET `is_blocked`='0' WHERE users.id = {$id}";
if(mysqli_query($conn, $sqlUnblocked)){
  header("location: allUsers.php");
} else {
  echo "something went wrong";
}