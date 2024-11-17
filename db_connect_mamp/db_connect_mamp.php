<?php

$hostname="localhost";
$username= "mamp";
$password= "root";
$dbname= "Sport_Event_Calender";

$conn = mysqli_connect("$hostname", "$username", "$password", "$dbname");

if(!$conn){
  die("Connection Failed". mysqli_connect_error());
}