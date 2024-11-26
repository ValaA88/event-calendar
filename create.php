<?php

session_start();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
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

require_once('./db_connect_mamp.php');
require_once('./functions.php');



if (isset($_POST['create'])) {
  $sport = cleanInputs($_POST['sport']);
  $seasonGame = cleanInputs($_POST['seasonGame']);
  $status = cleanInputs($_POST['status']);
  $timeVenueUTC = cleanInputs($_POST['timeVenueUTC']);
  $dateVenue = cleanInputs($_POST['dateVenue']);
  $stadium = cleanInputs($_POST['stadium']);
  $groupSeason = cleanInputs($_POST['groupSeason']);
  $originCompetitionName = cleanInputs($_POST['originCompetitionName']);



  $team1 = cleanInputs($_POST['team1']);
  $team2 = cleanInputs($_POST['team2']);
  $team1CountryCode = $_POST['team1CountryCode'];
  $team2CountryCode = $_POST['team2CountryCode'];
  // ## we take that out
  //   $winner = ($_POST['winner']) ?? null;
  //   $goals = ($_POST['goals']) ?? null;
  //   $yellowCards = ($_POST['yellowCards']) ?? null;
  //   $redCards = ($_POST['redCards']) ?? null;

  $stageName = cleanInputs($_POST['stageName']);
  $stagePosition = cleanInputs($_POST['stagePosition']);
  $ordering = $_POST['ordering'];



  if (isset($_SESSION['admin']) || isset($_SESSION['user'])) {
    // insert for event table
    $fk_users_id = $_SESSION['user'];
    $sqlEvent = "INSERT INTO `events`(`sport`, `seasonGame`, `status`, `timeVenueUTC`, `dateVenue`, `stadium`, `groupSeason`, `originCompetitionName`, `fk_users_id`) VALUES ('{$sport}','{$seasonGame}','{$status}','{$timeVenueUTC}','{$dateVenue}','{$stadium}','{$groupSeason}','{$originCompetitionName}','{$fk_users_id}')";
    $resultEvent = mysqli_query($conn, $sqlEvent);
    $fkEventId = $conn->insert_id;
  } else {
    $sqlEvent = "INSERT INTO `events`(`sport`, `seasonGame`, `status`, `timeVenueUTC`, `dateVenue`, `stadium`, `groupSeason`, `originCompetitionName`, `fk_users_id`) VALUES ('{$sport}','{$seasonGame}','{$status}','{$timeVenueUTC}','{$dateVenue}','{$stadium}','{$groupSeason}','{$originCompetitionName}',null)";
    $resultEvent = mysqli_query($conn, $sqlEvent);
    $fkEventId = $conn->insert_id;
  }



  // insert for stage table
  $sqlStage = "INSERT INTO `stage`(`stageName`,`ordering`) VALUES ('{$stageName}','{$ordering}')";
  $resultStage = mysqli_query($conn, $sqlStage);
  $fkStageId = $conn->insert_id;

  // insert for event_result table
  $sqlEventResult = "INSERT INTO `event_result`(`team1Result`,`team2Result`, `winner`, `goals`, `yellowCards`, `redCards`) VALUES (null, null, null, null, null, null)";

  $resultEventResult = mysqli_query($conn, $sqlEventResult);
  $fkEventResultId = $conn->insert_id;


  // teams and results
  $teams = [
    ['name' => $team1, 'teamCountryCode' => $team1CountryCode],
    ['name' => $team2, 'teamCountryCode' => $team2CountryCode]

  ];



  foreach ($teams as $team) {
    // insert for team table
    $sqlTeam = "INSERT INTO `team`(`name`,`teamCountryCode`,`stagePosition`) VALUES ('{$team['name']}','{$team['teamCountryCode']}','{$stagePosition}')";
    $resultTeam = mysqli_query($conn, $sqlTeam);
    $fkTeamId = $conn->insert_id;


    //insert for team_event_result table
    $sqlTeamEventResult = "INSERT INTO `team_event_result`(`fk_team_id`, `fk_event_id`, `fk_event_result_id`, `fk_stage_id`) VALUES ('{$fkTeamId}','{$fkEventId}','{$fkEventResultId}','{$fkStageId}')";
    $resultTeamEventResult = mysqli_query($conn, $sqlTeamEventResult);
  }

  if ($resultEvent) {
    echo "<div class='alert alert-success' role='alert'>
    <h4 class='alert-heading'>Well done! </h4>
    <p>Your Event has been Created successfully!</p>
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
  <?php include "components/navbar.php" ?>

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
        <label for="team1CountryCode" class="form-label">Team 1 Country Code</label>
        <input type="text" class="form-control" id="team1CountryCode" name="team1CountryCode" required>
      </div>

      <div class="mb-3">
        <label for="team2" class="form-label">Team 2 Name</label>
        <input type="text" class="form-control" id="team2" name="team2" required>
      </div>
      <div class="mb-3">
        <label for="team2CountryCode" class="form-label">Team 2 Country Code</label>
        <input type="text" class="form-control" id="team2CountryCode" name="team2CountryCode" required>
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
        <label for="ordering" class="form-label">Stage Ordering</label>
        <input type="text" class="form-control" id="ordering" name="ordering">
      </div>

      <button type="submit" class="btn btn-success" name="create">Create Event</button>
      <a href=<?= $goBack ?> class='btn btn-primary'>Back</a>
    </form>
  </div>
</body>

</html>