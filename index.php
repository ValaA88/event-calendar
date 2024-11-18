<?php

require_once("./db_connect_mamp.php");

$sql = "SELECT * FROM `events` ";
$result = mysqli_query($conn,$sql);

$layout = "";

if(mysqli_num_rows($result) == 0){
  $layout = "No Result";
} else {
  $rows = mysqli_fetch_all($result,MYSQLI_ASSOC);
  foreach($rows as $value){
    $layout .="<div class='card' style='width: 18rem;'>
    <div class='card-body'>
      <h5 class='card-title'>{$value['sport']}</h5>
      <h6 class='card-subtitle mb-2 text-body-secondary'>{$value['seasonGame']}</h6>

      <h7 class='card-title'>Status: {$value['status']}</h7><br>
      <h8 class='card-title'>Time: {$value['timeVenueUTC']} UTC</h8><br>
      <h9 class='card-title'>Date: {$value['dateVenue']}</h9><br>
      <h9 class='card-title'>Home Team: {$value['homeTeam']}</h9><br>
      <h9 class='card-title'>Away Team: {$value['awayTeam']}</h9><br>
      <a href='details.php?id={$value['id']}' class='btn btn-primary'>Details</a>

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

<body>
  <nav class="navbar bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="images/logo.jpg" alt="..." width="30" height="24">
      </a>

      <a class="navbar-brand" href="create.php">Create an Event</a>
      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>

    </div>
  </nav>
  <?= $layout ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>