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
      <h6 class='card-subtitle mb-2 text-body-secondary'>Card subtitle</h6>
      <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      <a href='#' class='card-link'>Card link</a>
      <a href='#' class='card-link'>Another link</a>
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
  <?= $layout ?>
</body>

</html>