<?php

session_start();

require_once('./db_connect_mamp.php');
require_once('./functions.php');

$goBack = "";

if(isset($_SESSION['admin'])){
  $session = $_SESSION['admin'];
  $goBack = "dashboard.php";
}

$eventId = $_GET['id'];


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
team.id as team_id,
team.name,
team.teamCountryCode,
team.stagePosition,
stage.stageName,
stage.ordering,
stage.id,
team_event_result.fk_event_result_id

FROM events JOIN team_event_result ON team_event_result.fk_event_id = events.id
JOIN event_result ON team_event_result.fk_event_result_id = event_result.id
JOIN team ON team_event_result.fk_team_id = team.id
JOIN stage ON team_event_result.fk_stage_id = stage.id
WHERE events.id = {$eventId};
";

$result = mysqli_query($conn, $sql);

$eventData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $eventData['details'] = $row;
    $eventData['teams'][] = [
        'id' => $row['team_id'],
        'name' => $row['name'],
        'teamCountryCode' => $row['teamCountryCode'],
        'event_result_id' => $row['fk_event_result_id'],
        'teamResult' => $row['teamResult']
    ];
    $eventData['event_result'] = [
        'winner' => $row['winner'],
        'goals' => $row['goals'],
        'yellowCards' => $row['yellowCards'],
        'redCards' => $row['redCards']
    ];
}

if(isset($_POST['update'])){
  $sport = cleanInputs($_POST['sport']);
  $seasonGame = cleanInputs($_POST['seasonGame']);
  $status = cleanInputs($_POST['status']);
  $timeVenueUTC = cleanInputs($_POST['timeVenueUTC']);
  $dateVenue = cleanInputs($_POST['dateVenue']);
  $stadium = cleanInputs($_POST['stadium']);
  $groupSeason = cleanInputs($_POST['groupSeason']);
  $originCompetitionName = cleanInputs($_POST['originCompetitionName']);

  $winner = ($_POST['winner']) ?? null;
  $goals = ($_POST['goals']) ?? null;
  $yellowCards = ($_POST['yellowCards']) ?? null;
  $redCards = ($_POST['redCards']) ?? null;

  $stageName = cleanInputs($_POST['stageName']);
  $stagePosition = cleanInputs($_POST['stagePosition']);
  $ordering = $_POST['ordering'];

   // insert for event table
   $sqlEventUpdate = "UPDATE `events` SET `sport`='{$sport}',`seasonGame`='{$seasonGame}',`status`='{$status}',`timeVenueUTC`='{$timeVenueUTC}',`dateVenue`='{$dateVenue}',`stadium`='{$stadium}',`groupSeason`='{$groupSeason}',`originCompetitionName`='{$originCompetitionName}' WHERE id = {$eventId}";
   $resultEventUpdate = mysqli_query($conn, $sqlEventUpdate);
  //  $fkEventId = $conn->insert_id;


 // insert for stage table
 $sqlStageUpdate ="UPDATE `stage` SET `stageName`='{$stageName}',`ordering`='{$ordering}' WHERE id = {$eventData['details']['id']}";
 $resultStageUpdate = mysqli_query($conn, $sqlStageUpdate);
//  $fkStageId = $conn->insert_id;


// insert for event_result table(in update only inside loop)
 $sqlEventResultUpdate = "UPDATE `event_result` SET `winner`='{$winner}',`goals`='{$goals}',`yellowCards`='{$yellowCards}',`redCards`='{$redCards}' WHERE id = {$eventData['details']['fk_event_result_id']}";

 $resultEventResultUpdate = mysqli_query($conn, $sqlEventResultUpdate);
//  $fkEventResultId = $conn->insert_id;

 foreach ($eventData['teams'] as $index => $team) {
  $index++;
  $teamName = cleanInputs($_POST["team{$index}_name"]);
  $teamCountryCode = cleanInputs($_POST["team{$index}_countryCode"]);
  $teamResult = cleanInputs($_POST["team{$index}_result"]);


  $sqlTeamUpdate = "UPDATE team
  SET name = '$teamName',
      teamCountryCode = '$teamCountryCode',
      stagePosition = '$stagePosition'
  WHERE id = {$team['id']}
  ";
  $resultTeamUpdate = mysqli_query($conn, $sqlTeamUpdate);

  $sqlTeamResultUpdate = "UPDATE event_result
  SET teamResult = '$teamResult'
  WHERE id = {$team['event_result_id']}
  ";
  $resultTeamResultUpdate = mysqli_query($conn, $sqlTeamResultUpdate);
}

 if ($resultEventUpdate) {
  echo "<div class='alert alert-success' role='alert'>
  <h4 class='alert-heading'>Well done! </h4>
  <p>Your Product has been Created successfully!</p>
  <hr>
  <p class='mb-0'> now you can find it on the main page.</p>
</div>";
  header("refresh: 3; url=home.php");
} else {
  echo "<div class='alert alert-danger' role='alert'>
<h4 class='alert-heading'>something went wrong</h4>
<p>Your Product did not create!</p>
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
  <title>Create Event</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background-color: #cadedf">
  <nav class="navbar bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="/">
        <img src="images/logo.jpg" alt="..." width="50" height="50"> Hello Admin
      </a>
      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>

    </div>
  </nav>
  <div class="container mt-5">
    <h1>Update Event</h1>
    <form method="POST">
      <div class="mb-3">
        <label for="sport" class="form-label">Sport</label>
        <input type="text" class="form-control" id="sport" name="sport" value="<?= $eventData['details']['sport'] ?>"
          required>
      </div>
      <div class="mb-3">
        <label for="seasonGame" class="form-label">Season Game</label>
        <input type="text" class="form-control" id="seasonGame" name="seasonGame"
          value="<?= $eventData['details']['seasonGame'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status" value="<?= $eventData['details']['status'] ?>" required>
          <option value="scheduled">Scheduled</option>
          <option value="played">Played</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="timeVenueUTC" class="form-label">Time (UTC)</label>
        <input type="time" class="form-control" id="timeVenueUTC" name="timeVenueUTC"
          value="<?= $eventData['details']['timeVenueUTC'] ?>">
      </div>
      <div class="mb-3">
        <label for="dateVenue" class="form-label">Date</label>
        <input type="date" class="form-control" id="dateVenue" name="dateVenue"
          value="<?= $eventData['details']['dateVenue'] ?>">
      </div>
      <div class="mb-3">
        <label for="stadium" class="form-label">Stadium</label>
        <input type="text" class="form-control" id="stadium" name="stadium"
          value="<?= $eventData['details']['stadium'] ?>">
      </div>
      <div class="mb-3">
        <label for="groupSeason" class="form-label">Group Season</label>
        <input type="text" class="form-control" id="groupSeason" name="groupSeason"
          value="<?= $eventData['details']['groupSeason'] ?>">
      </div>
      <div class="mb-3">
        <label for="originCompetitionName" class="form-label">Origin Competition Name</label>
        <input type="text" class="form-control" id="originCompetitionName" name="originCompetitionName"
          value="<?= $eventData['details']['originCompetitionName'] ?>">
      </div>

      <h3>Teams</h3>
      <?php foreach ($eventData['teams'] as $index => $team): ?>

      <?php $index++ ?>
      <div class="mb-3">
        <label for="team<?= $index ?>_name" class="form-label">Team 1 Name</label>
        <input type="text" class="form-control" id="team<?= $index ?>_name" name="team<?= $index ?>_name"
          value="<?= $team['name'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="team<?= $index ?>_result" class="form-label">Team <?= $index ?> Result</label>
        <input type="text" class="form-control" id="team<?= $index ?>_result" name="team<?= $index ?>_result"
          value="<?= $team['teamResult'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="team<?= $index ?>_countryCode" class="form-label">Team <?= $index ?> Country Code</label>
        <input type="text" class="form-control" id="team<?= $index ?>_countryCode" name="team<?= $index ?>_countryCode"
          value="<?= $team['teamCountryCode'] ?>" required>
      </div>
      <?php endforeach; ?>

      <h3>Result</h3>
      <div class="mb-3">
        <label for="winner" class="form-label">Winner</label>
        <input type="text" class="form-control" id="winner" name="winner"
          value="<?= $eventData['event_result']['winner'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="goals" class="form-label">Total Goals</label>
        <input type="number" class="form-control" id="goals" name="goals"
          value="<?= $eventData['event_result']['goals'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="yellowCards" class="form-label">Total Yellow Cards</label>
        <input type="number" class="form-control" id="yellowCards" name="yellowCards"
          value="<?= $eventData['event_result']['yellowCards'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="redCards" class="form-label">Total Red Cards</label>
        <input type="number" class="form-control" id="redCards" name="redCards"
          value="<?= $eventData['event_result']['redCards'] ?>" required>
      </div>

      <h3>Stage</h3>
      <div class="mb-3">
        <label for="stageName" class="form-label">Stage Name</label>
        <input type="text" class="form-control" id="stageName" name="stageName"
          value="<?= $eventData['details']['stageName'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="stagePosition" class="form-label">Stage Position</label>
        <input type="number" class="form-control" id="stagePosition" name="stagePosition"
          value="<?= $eventData['details']['stagePosition'] ?>">
      </div>
      <div class="mb-3">
        <label for="ordering" class="form-label">Stage Ordering</label>
        <input type="text" class="form-control" id="ordering" name="ordering"
          value="<?= $eventData['details']['ordering'] ?>">
      </div>

      <button type="submit" class="btn btn-success" name="update">Update Event</button>
      <a href='dashboard.php' class='btn btn-primary'>Back</a>
    </form>
  </div>
</body>

</html>