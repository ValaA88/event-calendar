<?php
require_once("./db_connect_mamp.php");


// Optimized SQL query to fetch all necessary data in one go
$sql = "
SELECT
    events.id AS event_id,
    events.sport,
    events.seasonGame,
    events.status,
    events.timeVenueUTC,
    events.dateVenue,
    stage.stageName,
    team.name AS team_name
FROM events
LEFT JOIN team_event_result ON team_event_result.fk_event_id = events.id
LEFT JOIN stage ON stage.id = team_event_result.fk_stage_id
LEFT JOIN team ON team.id = team_event_result.fk_team_id
ORDER BY events.id, team_event_result.id
";

$result = mysqli_query($conn, $sql);

$layout = "";

if (mysqli_num_rows($result) == 0) {
    $layout = "No Result";
} else {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Group data by event
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
                'teams' => []
            ];
        }
        if (!empty($row['team_name'])) {
            $events[$eventId]['teams'][] = $row['team_name'];
        }
    }

     // Build layout
    foreach ($events as $eventId => $eventData) {
        $layout .= "<div class='card' style='width: 18rem;'>
        <div class='card-body'>
            <h5 class='card-title'>{$eventData['details']['sport']}</h5>
            <h6 class='card-subtitle mb-2 text-body-secondary'>{$eventData['details']['seasonGame']}</h6>
            <h7 class='card-title'>Status: {$eventData['details']['status']}</h7><br>
            <h8 class='card-title'>Time: {$eventData['details']['timeVenueUTC']} UTC</h8><br>
            <h9 class='card-title'>Date: {$eventData['details']['dateVenue']}</h9><br>
            <h9 class='card-title'>Stage: {$eventData['details']['stageName']}</h9><br>";

        $count = 1;
        foreach ($eventData['teams'] as $team) {
            $layout .= "<h9 class='card-title'>Team {$count}: {$team}</h9><br>";
            $count++;
        }

        $layout .= "<a href='details.php?id={$eventId}' class='btn btn-primary'>Details</a>
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
  <form enctype="multipart/form-data">
    <a href="create.php" class="btn btn-primary" style="margin: 20px; text-align: center;">Create an Event</a>
    <?= $layout ?>

  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>