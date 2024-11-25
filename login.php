<?php

require_once('./db_connect_mamp.php');
require_once('./functions.php');

session_start();

if(isset($_SESSION['user'])){
  header("location: home.php");
}

if(isset($_SESSION['admin'])){
  header("location: dashboard.php");
}


$error = false;
$email = $emailError = $passError = $loginError = "";

if(isset($_POST['login'])){
  $email =cleanInputs($_POST['email']);
  $password =cleanInputs($_POST['password']);

# email validation
if(empty($email)){
  $error = true;
  $emailError = 'this input can not be empty';
} elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  $error = true;
  $emailError = 'Please type a valid email';
}

#password validation
if(empty($password)){
  $error = true;
  $passError = 'You can not leave this input empty';
}

if(!$error){
  $password = hash("sha256", $password);
}

$sql = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

if($count == 1){
  if($row['is_blocked']){
    $loginError = "<div class='alert alert-danger' role='alert'>
    <h4 class='alert-heading'>Your account is Blocked</h4>
    <p>Please contact admin</p>
    <hr>
  </div>";
  }
  elseif($row['status'] == 'adm'){
    $_SESSION['admin'] = $row['id'];
    header("location: dashboard.php");
  }
  elseif($row['status'] == 'user'){
    $_SESSION['user'] = $row['id'];
    header("location: home.php");
  }
}


if($error){
  echo "<div class='alert alert-danger' role='alert'>
  <h4 class='alert-heading'>something went wrong</h4>
  <hr>
  <p class='mb-0'>Please try again.</p>
</div>";
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
  <nav class="navbar bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="/">
        <img src="images/logo.jpg" alt="..." width="50" height="50">
      </a>
      <a class="navbar-brand" href="index.php">Home</a>

      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>

    </div>
  </nav>

  <h1><?= $loginError ?></h1>
  <form class="container" method="post">

    <h4>To create an Event you need to Login</h4>
    <h6>If you don not have an account please Register first</h6>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" aria-describedby="emailHelp" name="email" value="<?= $email?>">
      <small class="form-text text-danger"><?= $emailError ?></small>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password">
      <small class="form-text text-danger"><?= $passError ?></small>
    </div>

    <div style="padding-top: 18px">
      <button type="login" name="login" class="btn btn-primary">Login</button>
      <a href="index.php" class="btn btn-outline-primary">Back to main page</a>
    </div>
    <div style="padding-top: 18px">
      <a href="register.php" class="btn btn-outline-success">Register Here</a>
      <a href="create.php" class="btn btn-outline-dark">Continue as Guest</a>

    </div>
  </form>
</body>

</html>