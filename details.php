<?php
session_start();

if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
  $session = 0;
  $goBack = "index.php";
}

if (isset($_SESSION["admin"])) {
  $session = $_SESSION["admin"];
  $goBack = "dashboard.php";
}

if (isset($_SESSION["user"])) {
  $session = $_SESSION["user"];
  $goBack = "home.php";
}


require_once("./db_connect_mamp.php");

$id = $_GET['id'];

$sql = "
SELECT
events.id AS event_id,
events.sport,
events.seasonGame,
events.status,
events.timeVenueUTC,
events.dateVenue,
events.stadium,
events.groupSeason,
events.originCompetitionName,
event_result.teamResult,
event_result.winner,
event_result.goals,
event_result.yellowCards,
event_result.redCards,
team.name,
team.teamCountryCode,
team.stagePosition,
stage.stageName,
stage.ordering
FROM events JOIN team_event_result ON team_event_result.fk_event_id = events.id
JOIN event_result ON team_event_result.fk_event_result_id = event_result.id
JOIN team ON team_event_result.fk_team_id = team.id
JOIN stage ON team_event_result.fk_stage_id = stage.id
WHERE events.id = {$id};
";

$result = mysqli_query($conn, $sql);

$layout = "";

if (mysqli_num_rows($result) == 0) {
  $layout = "No Result";
} else {
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


  $events = [];
  $count = 0;
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
          'stadium' => $row['stadium'],
          'stageName' => $row['stageName'],
          'groupSeason' => $row['groupSeason'],
          'originCompetitionName' => $row['originCompetitionName'],
          'goals' => $row['goals'],
          'yellowCards' => $row['yellowCards'],
          'redCards' => $row['redCards'],
          'winner' => $row['winner'],
          'stagePosition' => $row['stagePosition'],
          'ordering' => $row['ordering'],
        ],
        'teams' => [
          'name' => [$row['name']],
          'teamCountryCode' => [$row['teamCountryCode']],

        ],
        'result' => [
          'teamResult' => [$row['teamResult']],

        ]
      ];
    }

    if ($count == 1 && (!empty($row['name']) || !empty($row['teamResult']) || !empty($row['teamCountryCode']))) {
      $events[$eventId]['teams']["name"][] = $row['name'];
      $events[$eventId]['result']["teamResult"][] = $row['teamResult'];
      # $events[$eventId]['result'][] = $row['teamResult'];
      $events[$eventId]['teams']['teamCountryCode'][] = $row['teamCountryCode'];
    }
    $count++;
  }

  $i = 0;
  foreach ($events as $eventId => $eventData) {
    $formattedDate = date('l d.m.Y', strtotime($eventData['details']['dateVenue']));
    $layout .= "
        <div class='container' style='padding:50px; size: 100px;'>
        <div class='card' style='width: 18rem; background-color: #cadedf ;color:black'>
        <div class='card-body'>
            <h4 class='card-title'>{$eventData['details']['sport']}</h4>
            <h5 class='card-subtitle mb-2 text-body-secondary'>{$eventData['details']['seasonGame']}</h5>
            <h6 class='card-title'>Status: {$eventData['details']['status']}</h6><br>
            <h6 class='card-title'>Time: {$eventData['details']['timeVenueUTC']} UTC</h6><br>
            <h6 class='card-title'>Date: {$formattedDate}</h6><br>
            ";

    $count = 1;
    $i = 0;
    foreach ($eventData['teams'] as $index => $team) {
      $teamResult = $eventData['result']['teamResult'][$i] ?? '-';
      $teamCountryCode = $eventData['teams']['teamCountryCode'][$i];
      $team = $eventData['teams']['name'][$i];
      $layout .= "
                <table class='table'>
                  <thead>
                    <tr class='table-secondary'>
                      <th scope='col'></th>
                      <th scope='col'>Team</th>
                      <th scope='col'>Result</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <th scope='row'></th>
                    <td>{$count}: {$team}</td>
                    <td>{$teamResult}</td>
                  </tr>
                  <tr>
                    <th scope='row'></th>
                    <td>Team Country Code:</td>
                    <td>{$eventData['teams']['teamCountryCode'][$i]}</td>

                  </tr>
                  </tbody>
                </table>
                ";
      $count++;
      $i++;
    }
    $layout .= "

            <h6 class='card-title'>Winner: {$eventData['details']['winner']}</h6><br>
            <h6 class='card-title'>Total Goals: {$eventData['details']['goals']}</h6><br>
            <h6 class='card-title'>Total Yellow Cards: {$eventData['details']['yellowCards']}</h6><br>
            <h6 class='card-title'>Total Red Cards: {$eventData['details']['redCards']}</h6><br>

            <h9 class='card-title'>stadium: {$eventData['details']['stadium']}</h9><br>
            <h9 class='card-title'>Stage: {$eventData['details']['stageName']}</h9><br>
            <h9 class='card-title'>group: {$eventData['details']['groupSeason']}</h9><br>
            <h9 class='card-title'>Competition Name: {$eventData['details']['originCompetitionName']}</h9><br>

            <h9 class='card-title'>Stage Position: {$eventData['details']['stagePosition']}</h9>
            <br>
            <h9 class='card-title'>Stage Ordering: {$eventData['details']['ordering']}</h9>
            <br>
        <div style='padding-top: 15px'>
        <a href='{$goBack}' class='btn btn-primary'>Back</a>
        </div>
        </div>
        </div>
        </div>";
    $i++;
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

<body style="background-color: #274472">
  <?php include "components/navbar.php" ?>
  <div class="container">
    <div class="row row-cols-3">
      <?= $layout ?>
    </div>
  </div>
</body>

</html>