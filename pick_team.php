<?php // Pick Team Script

include("mysql.php");

// Date: 8-15-2015
$this_user_id = $_REQUEST['user_id'];
$this_group_id = $_REQUEST['group_id'];
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

$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE game_id='$this_game_id' AND user_id='$this_user_id' AND group_id='$this_group_id'");

if($this_pick = mysqli_fetch_array($pick_result)) { // User has already picked so update

  if(isset($_REQUEST['winner'])) {
    $this_winner = $_REQUEST['winner'];
    $sql = "UPDATE picks SET winner='$this_winner' WHERE user_id='$this_user_id' AND game_id='$this_game_id' AND group_id='$this_group_id'";
      
    if(mysqli_query($db,$sql)) {
      echo "Pick updated to $this_winner";
    } else {
      echo mysqli_error($db); 
    } 
  }

  if(isset($_REQUEST['score'])) {
    $this_score = $_REQUEST['score'];
    if($this_score == '') { 
        $sql = "UPDATE picks SET score=NULL WHERE user_id='$this_user_id' AND game_id='$this_game_id' AND group_id='$this_group_id'"; 
    } else {
        $sql = "UPDATE picks SET score='$this_score' WHERE user_id='$this_user_id' AND game_id='$this_game_id' AND group_id='$this_group_id'";
    }
      
    if(mysqli_query($db,$sql)) {
      echo "Score of $this_score Entered";
    } else {
      echo mysqli_error($db); 
    } 
  }
  

} else { //first pick so add it to db and create point holder for week
  $sql = "INSERT INTO picks (pick_id, group_id, game_id, season_year, season_type, week, user_id, winner, score, points,reconciled) VALUES (NULL,'$this_group_id','$this_game_id','$this_season_year','$this_season_type','$this_week','$this_user_id','$this_winner','$this_score',NULL,NULL)";
  if(mysqli_query($db, $sql)) {
    echo "Pick $this_winner entered";
  } else {
    echo mysqli_error($db);
  }
  updatePoints($db,$this_user_id,$this_group_id,$this_season_year,$this_season_type,$this_week,false);
}

?>