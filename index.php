<?php
require_once("./db_connect_mamp.php");

$sql = "
SELECT
    events.id AS event_id,
    events.sport,
    events.seasonGame,
    events.status,
    events.timeVenueUTC,
    events.dateVenue,
    stage.stageName,
    team.name AS team_name,
    event_result.teamResult AS team_result
FROM events
LEFT JOIN team_event_result ON team_event_result.fk_event_id = events.id
LEFT JOIN stage ON stage.id = team_event_result.fk_stage_id
LEFT JOIN team ON team.id = team_event_result.fk_team_id
LEFT JOIN event_result ON event_result.id = team_event_result.fk_event_result_id
ORDER BY events.id, team_event_result.id
";

$result = mysqli_query($conn, $sql);

$layout = "";

if (mysqli_num_rows($result) == 0) {
    $layout = "No Result";
} else {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $events = [];
    foreach ($rows as $row) {
        $eventId = $row['event_id'];
        if (!isset($events[$eventId])) {
            $events[$eventId] = [
                'details' => [
                    'sport' => $row['sport'],
                    'seasonGame' => $row['seasonGame'],
                    'status' => $row['status'],
                    'timeVenueUTC' => $row['timeVenueUTC'],
                    'dateVenue' => $row['dateVenue'],
                    'stageName' => $row['stageName']
                ],
                'teams' => [],
                'result' => []
            ];
        }
        if (!empty($row['team_name']) || !empty($row['team_result'])) {
            $events[$eventId]['teams'][] = $row['team_name'];
            $events[$eventId]['result'][] = $row['team_result'];
        }
    }

    foreach ($events as $eventId => $eventData) {
        $layout .= "
        <div style='padding: 30px; '>
        <div class='card' style='width: 18rem ; background-color: #white ;color:black' >
        <div class='card-body'>
            <h5 class='card-title'>{$eventData['details']['sport']}</h5>
            <h6 class='card-subtitle'>{$eventData['details']['seasonGame']}</h6>
            <h7 class='card-title'>Status: {$eventData['details']['status']}</h7><br>
            <h8 class='card-title'>Time: {$eventData['details']['timeVenueUTC']} UTC</h8><br>
            <h9 class='card-title'>Date: {$eventData['details']['dateVenue']}</h9><br>
            <h9 class='card-title'>Stage: {$eventData['details']['stageName']}</h9><br>";

            $count = 1;
            foreach ($eventData['teams'] as $index => $team) {
                $teamResult = $eventData['result'][$index] ?? '';
                $layout .= "<h9 class='card-title' style='color:#black'>Team {$count}: {$team} - {$teamResult}</h9><br>";
                $count++;
            }


        $layout .= "<a href='details.php?id={$eventId}' class='btn btn-primary'>Details</a>
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
  <nav class="navbar bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="images/logo.jpg" alt="..." width="50" height="50">
      </a>
      <a class="navbar-brand" href="create.php">Login</a>
      <a class="btn btn-success" href="create.php">Create an Event</a>
      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>

    </div>
  </nav>
  <form enctype="multipart/form-data">

    <div class="container">
      <div class="row row-cols-3 .">
        <?= $layout ?>
      </div>
    </div>

  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>