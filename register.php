<?php

require_once('./db_connect_mamp.php');
require_once('./functions.php');

session_start();

if (isset($_SESSION["admin"])) {
  header("Location: dashboard.ph?page=events");
} elseif (isset($_SESSION["user"])) {
  header("Location: home.php");
}

$error = false;
$firstName = $lastName = $email = $password = $dateOfBirth = "";
$fnameError = $lnameError = $emailError = $passError = $dateError = "";

if (isset($_POST['register'])) {
  $firstName = cleanInputs($_POST['firstName']);
  $lastName = cleanInputs($_POST['lastName']);
  $email = cleanInputs($_POST['email']);
  $password = cleanInputs($_POST['password']);
  $dateOfBirth = cleanInputs($_POST['dateOfBirth']);

  #first name validation
  if (empty($firstName)) {
    $error = true;
    $fnameError = 'You can leave the first name empty';
  } elseif (strlen($firstName) < 3) {
    $error = true;
    $fnameError = "First name must be at least 3 chars";
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $firstName)) {
    $error = true;
    $fnameError = "First name must contain only letters and spaces";
  }

  #last name validation
  if (empty($lastName)) {
    $error = true;
    $lnameError = 'You can leave the last name empty';
  } elseif (strlen($lastName) < 3) {
    $error = true;
    $lnameError = "Last name must be at least 3 chars";
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $lastName)) {
    $error = true;
    $lnameError = "Last name must contain only letters and spaces";
  }

  #email validation
  if (empty($email)) {
    $error = true;
    $emailError = "Please enter your email address";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = true;
    $emailError = "Please enter a valid email";
  } else {
    $sql = "SELECT `email` from `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 0) {
      $error = true;
      $emailError = "Provided email is already in use";
    }
  }

  //Password validation
  if (empty($password)) {
    $error = true;
    $passError = "Please enter a password";
  } elseif (!preg_match("/.{6,}/", $password)) {
    $error = true;
    $passError = "Password must be at least 6 characters long";
  }

  #date fo birth validation
  if (empty($dateOfBirth)) {
    $error = true;
    $dateError = "Please select the date of birth";
  }

  if (!$error) {
    $password = hash("sha256", $password);

    $sql = "INSERT INTO `users`(`firstName`, `lastName`, `email`, `password`, `dateOfBirth`) VALUES ('{$firstName}','{$lastName}','{$email}','{$password}','{$dateOfBirth}')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      $sql = "SELECT * FROM `users` WHERE email = '$email' AND `password` = '$password'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);

      if (mysqli_num_rows($result) == 1) {

        if ($row["status"] == "user") {
          $_SESSION["user"] = $row["id"];
          header("refresh: 3; url=home.php");
        } else {
          echo "Something went wrong, please try again later";
        }
      }
    }
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background-color: #cadedf">
  <?php include "components/navbar.php" ?>


  <form class="container" method="post" enctype="multipart/form-data" style="padding: 30px">
    <div class="form-group">
      <label for="firstName">First Name</label>
      <input type="text" class="form-control" name="firstName" value="<?= $firstName ?>">
      <small class="form-text text-danger"><?= $fnameError ?></small>
    </div>
    <div class="form-group">
      <label for="lastName">Last name</label>
      <input type="text" class="form-control" name="lastName" value="<?= $lastName ?>">
      <small class="form-text text-danger"><?= $lnameError ?></small>
    </div>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" aria-describedby="emailHelp" name="email" value="<?= $email ?>">
      <small class="form-text text-danger"><?= $emailError ?></small>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password">
      <small class="form-text text-danger"><?= $passError ?></small>
    </div>
    <div class="form-group">
      <label for="dateOfBirth">Date of birth</label>
      <input type="date" class="form-control" name="dateOfBirth" value="<?= $dateOfBirth ?>">
      <small class="form-text text-danger"><?= $dateError ?></small>
    </div>
    <div style="padding-top: 18px">
      <button type="submit" name="register" class="btn btn-primary">Submit</button>
      <a href="index.php" class="btn btn-outline-primary">Back to main page</a>
    </div>
  </form>
</body>

</html>