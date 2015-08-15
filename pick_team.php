<?php // Pick Team Script

include("mysql.php");

// Date: 8-15-2015
$this_userid = $_REQUEST['user_id'];
$this_game_id = $_REQUEST['game_id'];
$this_winner = $_REQUEST['winner'];

$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE game_id='$this_game_id' AND user_id='$this_userid'");

if($this_pick = mysqli_fetch_array($pick_result)) { // User has already picked so update

  $sql = "UPDATE picks SET winner='$this_winner' WHERE user_id='$this_userid' AND game_id='$this_game_id'";
      
  if(mysqli_query($db,$sql)) {
    echo "Pick updated";
  } else {
    echo mysqli_error($db); 
  } 

} else {
  $sql = "INSERT INTO picks (game_id, user_id, winner) VALUES ('$this_game_id','$this_userid','$this_winner')";
  if(mysqli_query($db, $sql)) {
    echo "Picke entered";
  } else {
    echo mysqli_error($db);
  }
}

?>