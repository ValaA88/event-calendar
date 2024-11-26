<?php

session_start();

if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
  header("location: login.php");
}

if (isset($_SESSION['user'])) {
  header("location: home.php");
}

require_once('./db_connect_mamp.php');

$layout = $status = "";

$sql = "SELECT * FROM `users` WHERE `status` != 'adm'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
  $layout = "No result";
} else {
  $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
  foreach ($row as $value) {
    $layout .= "
    <div style='padding: 30px; '>
    <div class='card' style='width: 18rem;'>

    <div class='card-body'>
      <h6 class='card-title'>First Name: {$value['firstName']}</h6><br>
      <h6 class='card-title'>Last Name: {$value['lastName']}</h6><br>
      <h6 class='card-title'>Email: {$value['email']}</h6><br>
      <label name='blocked'>
      <p value=''> status: {$value['status']}</p>";
    if ($value['is_blocked'] == 0) {
      $layout .= "<a href='blocked_users.php?id={$value['id']}' class='btn btn-warning'>Block</a>";
    } else {
      $layout .= "<a href='unblocked_users.php?id={$value['id']}' class='btn btn-success'>Active</a>";
    }
    $layout .= "
      </label>
      </div>
      </div>
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
  <?php include "components/navbar.php" ?>
  <div class="container" style="margin-top: 40px;">
    <form method="post">
      <div class="container">
        <div class="row row-cols-3." style="padding: 20px">
          <?= $layout ?>
        </div>
      </div>

      <a href="dashboard.php" value="back" class="btn btn-danger">Back</a>

    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>