<?php

require_once('./db_connect_mamp.php');
require_once('./functions.php');

if(isset($_POST['create'])){
  $sport = cleanInputs($_POST['sport']);
  $seasonGame = cleanInputs($_POST['seasonGame']);
  $status = cleanInputs($_POST['status']);
  $timeVenueUTC = cleanInputs($_POST['timeVenueUTC']);
  $dateVenue = cleanInputs($_POST['dateVenue']);
  $stadium = cleanInputs($_POST['stadium']);
  $groupSeason = cleanInputs($_POST['groupSeason']);
  $originCompetitionName = cleanInputs($_POST['originCompetitionName']);
  $teamCountryCode =$_POST['teamCountryCode'];

    $team1 = cleanInputs($_POST['team1']);
    $team2 = cleanInputs($_POST['team2']);
    $team1Result = cleanInputs($_POST['team1Result']) ?? null;
    $team2Result = cleanInputs($_POST['team2Result']) ?? null;
    $goals = ($_POST['goals']) ?? null;
    $yellowCards = ($_POST['yellowCards']) ?? null;
    $redCards = ($_POST['redCards']) ?? null;

  $stageName = cleanInputs($_POST['stageName']);
  $stagePosition = cleanInputs($_POST['stagePosition']);
  $ordering = $_POST['ordering'];

  // insert for event table
  $sqlEvent = "INSERT INTO `events`(`sport`, `seasonGame`, `status`, `timeVenueUTC`, `dateVenue`, `stadium`, `groupSeason`, `originCompetitionName`) VALUES ('{$sport}','{$seasonGame}','{$status}','{$timeVenueUTC}','{$dateVenue}','{$stadium}','{$groupSeason}','{$originCompetitionName}')";

  $resultEvent = mysqli_query($conn, $sqlEvent);

  // insert for stage table
  $sqlStage ="INSERT INTO `stage`(`stageName`,`ordering`) VALUES ('{$stageName}','{$ordering}')";
  $resultStage = mysqli_query($conn, $sqlStage);

  // teams and results
  $teams = [
    ['name' => $team1, 'result' => $team1Result],
    ['name' => $team2, 'result' => $team2Result]
  ];

  foreach($teams as $team){
    // insert for team table
    $sqlTeam = "INSERT INTO `team`(`name`,`teamCountryCode`, `stagePosition`) VALUES ('{$team1}','{$teamCountryCode}','{$stagePosition}')";
    $resultTeam = mysqli_query($conn,$sqlTeam);

    //insert for team_event_result table
    $sqlTeamEventResult = "INSERT INTO `team_event_result`(`fk_team_id`, `fk_event_id`, `fk_event_result_id`, `fk_stage_id`) VALUES ('{$fk_team_id}','{$fk_event_id}','{$fk_event_result_id}','{$fk_stage_id}')";
    $resultTeamEventResult = mysqli_query($conn, $sqlTeamEventResult);
  }

  echo "Event created successfully!";
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
      <a class="navbar-brand" href="#">
        <img src="images/logo.jpg" alt="..." width="50" height="50">
      </a>
      <a class="navbar-brand" href="create.php">Login</a>
      <a class="navbar-brand" href="#">About us</a>
      <a class="navbar-brand" href="#">FAQ</a>

    </div>
  </nav>
  <div class="container mt-5">
    <h1>Create a New Event</h1>
    <form method="POST">
      <div class="mb-3">
        <label for="sport" class="form-label">Sport</label>
        <input type="text" class="form-control" id="sport" name="sport" required>
      </div>
      <div class="mb-3">
        <label for="seasonGame" class="form-label">Season Game</label>
        <input type="text" class="form-control" id="seasonGame" name="seasonGame" required>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status" required>
          <option value="scheduled">Scheduled</option>
          <option value="played">Played</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="timeVenueUTC" class="form-label">Time (UTC)</label>
        <input type="time" class="form-control" id="timeVenueUTC" name="timeVenueUTC">
      </div>
      <div class="mb-3">
        <label for="dateVenue" class="form-label">Date</label>
        <input type="date" class="form-control" id="dateVenue" name="dateVenue">
      </div>
      <div class="mb-3">
        <label for="stadium" class="form-label">Stadium</label>
        <input type="text" class="form-control" id="stadium" name="stadium">
      </div>
      <div class="mb-3">
        <label for="groupSeason" class="form-label">Group Season</label>
        <input type="text" class="form-control" id="groupSeason" name="groupSeason">
      </div>
      <div class="mb-3">
        <label for="originCompetitionName" class="form-label">Origin Competition Name</label>
        <input type="text" class="form-control" id="originCompetitionName" name="originCompetitionName">
      </div>

      <h3>Teams</h3>
      <div class="mb-3">
        <label for="team1" class="form-label">Team 1 Name</label>
        <input type="text" class="form-control" id="team1" name="team1" required>
      </div>
      <div class="mb-3">
        <label for="team1Result" class="form-label">Team 1 Result</label>
        <input type="text" class="form-control" id="team1Result" name="team1Result">
      </div>

      <div class="mb-3">
        <label for="team2" class="form-label">Team 2 Name</label>
        <input type="text" class="form-control" id="team2" name="team2" required>
      </div>
      <div class="mb-3">
        <label for="team2Result" class="form-label">Team 2 Result</label>
        <input type="text" class="form-control" id="team2Result" name="team2Result">
      </div>

      <h3>Event Totals</h3>
      <div class="mb-3">
        <label for="totalGoals" class="form-label">Total Goals</label>
        <input type="number" class="form-control" id="totalGoals" name="totalGoals">
      </div>
      <div class="mb-3">
        <label for="totalYellowCards" class="form-label">Total Yellow Cards</label>
        <input type="number" class="form-control" id="totalYellowCards" name="totalYellowCards">
      </div>
      <div class="mb-3">
        <label for="totalRedCards" class="form-label">Total Red Cards</label>
        <input type="number" class="form-control" id="totalRedCards" name="totalRedCards">
      </div>

      <h3>Stage</h3>
      <div class="mb-3">
        <label for="stageName" class="form-label">Stage Name</label>
        <input type="text" class="form-control" id="stageName" name="stageName" required>
      </div>
      <div class="mb-3">
        <label for="stagePosition" class="form-label">Stage Position</label>
        <input type="number" class="form-control" id="stagePosition" name="stagePosition">
      </div>
      <div class="mb-3">
        <label for="stageOrdering" class="form-label">Stage Ordering</label>
        <input type="number" class="form-control" id="stageOrdering" name="stageOrdering">
      </div>

      <button type="submit" class="btn btn-success" name="create">Create Event</button>
      <a href='index.php' class='btn btn-primary'>Back</a>
    </form>
  </div>
</body>

</html>