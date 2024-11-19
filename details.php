<?php

require_once('./db_connect_mamp.php');

$id = $_GET['id'];
$teamID = $_GET['0'];

$sql = "SELECT events.*, event_result.*,team.*, team_event_result.fk_event_id, team_event_result.fk_team_id, team_event_result.fk_event_result_id as attending FROM team_event_result
JOIN events ON events.id = team_event_result.fk_event_id JOIN event_result ON event_result.id = team_event_result.fk_event_result_id RIGHT JOIN team ON team.id = team_event_result.fk_team_id WHERE events.id = {$id} AND team.id = {$teamID}";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

// $sqlTeam = "SELECT * FROM team";
// $resultTeam = mysqli_query($conn, $sqlTeam);

// $rowTeam = mysqli_fetch_assoc($resultTeam);

$layout = "";

if(empty($row)){
 $layout.= "No results";
}else {
  $layout = "<div class='card' style='width: 18rem;'>
<div class='card-body'>
  <h5 class='card-title'>{$row['sport']}</h5>
  <h6 class='card-subtitle mb-2 text-body-secondary'>{$row['seasonGame']}</h6>

  <h7 class='card-title'>Status: {$row['status']}</h7><br>
  <h8 class='card-title'>Time: {$row['timeVenueUTC']} UTC</h8><br>
  <h9 class='card-title'>Date: {$row['dateVenue']}</h9><br>
  <div>
  <p>Result:</p>
  <h9 class='card-title'>{$row['name']} : {$row['homeTeamResult']}</h9><br>
  <h9 class='card-title'>{$row['name']} : {$row['awayTeamResult']}</h9><br>
  </div>
  <h9 class='card-title'>Group: {$row['groupSeason']}</h9><br>
  <h9 class='card-title'>Origin Competition Name: {$row['originCompetitionName']}</h9><br>
  <h9 class='card-title'>Stage: {$row['name']}</h9><br>
  <h9 class='card-title'>Ordering: {$row['ordering']}</h9><br>


  <a href='index.php' class='btn btn-primary'>Back</a>

</div>
</div>";


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