<?php // Pick Team Script

include("mysql.php");

// Date: 8-15-2015
$this_userid = $_REQUEST['user_id'];
$this_game_id = $_REQUEST['game_id'];
$this_season_year = $_REQUEST['season_year'];
$this_season_type = $_REQUEST['season_type'];
$this_week = $_REQUEST['week'];

if(isset($_REQUEST['winner'])) {
  $this_winner = $_REQUEST['winner'];
} else {
  $this_winner = NULL;
}
if(isset($_REQUEST['score'])) {
  $this_score = $_REQUEST['score'];
} else {
  $this_score = NULL;
}

$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE game_id='$this_game_id' AND user_id='$this_userid'");

if($this_pick = mysqli_fetch_array($pick_result)) { // User has already picked so update

  if(isset($_REQUEST['winner'])) {
    $this_winner = $_REQUEST['winner'];
    $sql = "UPDATE picks SET winner='$this_winner' WHERE user_id='$this_userid' AND game_id='$this_game_id'";
      
    if(mysqli_query($db,$sql)) {
      echo "Pick updated";
    } else {
      echo mysqli_error($db); 
    } 
  }

  if(isset($_REQUEST['score'])) {
    $this_score = $_REQUEST['score'];
    $sql = "UPDATE picks SET score='$this_score' WHERE user_id='$this_userid' AND game_id='$this_game_id'";
      
    if(mysqli_query($db,$sql)) {
      echo "Score Entered";
    } else {
      echo mysqli_error($db); 
    } 
  }
  

} else {
  $sql = "INSERT INTO picks (pick_id, game_id, season_year, season_type, week, user_id, winner, score, points) VALUES (NULL,'$this_game_id','$this_season_year','$this_season_type','$this_week','$this_userid','$this_winner','$this_score',NULL)";
  if(mysqli_query($db, $sql)) {
    echo "Picke entered";
  } else {
    echo mysqli_error($db);
  }
}

?>