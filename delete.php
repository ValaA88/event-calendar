<?php

require_once('./db_connect_mamp.php');

$id = $_GET['id'];

$sql = "SELECT
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
WHERE events.id = {$id};";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


$sqlDelete = "DELETE FROM events WHERE id = {$id}";
$resultDelete = mysqli_query($conn, $sqlDelete);

if($resultDelete){
  header("location: dashboard.php"); //take care of teh location later, maybe index is needed!
}