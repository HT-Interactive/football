<?php // Winning Deterministic Algorithm

function getGameWinner($game_id) {

  // Performing SQL query
  $query = "SELECT * FROM game WHERE gsis_id='$game_id'";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $game = pg_fetch_array($result, null, PGSQL_ASSOC);
  if($game['home_score']>$game['away_score']) {
    return $game['home_team'];
  } elseif($game['home_score']==$game['away_score']) {
    return "tied";
  } else {
    return $game['away_team'];
  }

}

function addPoint($db,$id,$p) {

  $sql = "UPDATE picks SET points='$p' WHERE pick_id='$id'";
      
  if(mysqli_query($db,$sql)) {
    //echo "Point Added";
  } else {
    echo mysqli_error($db); 
  } 

}


function updatePoints($db,$userid,$season_year,$season_type,$week) {
  // check picks table for games that match season/week and sum them
  //$sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$userid' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'"; 
  //$result = mysqli_query($db,$sql);
  //$row = mysqli_fetch_assoc($result); 
  //$sum = $row['points_sum'];
  //echo $sum;
  $sum = getWeeklyPoints($db,$userid,$season_year,$season_type,$week);

  $sql = "SELECT * FROM points WHERE user_id='$userid' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'";
  $result = mysqli_query($db,$sql);
  if(mysqli_num_rows($result) > 0) { // user has points for week in db so update
    // update points total in points table for user
    $sql = "UPDATE points SET points='$sum' WHERE user_id='$userid' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'";
    //$result = mysqli_query($db,$sql);
    if(mysqli_query($db,$sql)) {
      //echo "Point total for week updated";
    } else {
      echo mysqli_error($db); 
    }  
  } else { //no point total has been added so insert

    $sql = "INSERT INTO points (points_id, user_id, season_year, season_type, week, points) VALUES (NULL,'$userid','$season_year','$season_type','$week',$sum)";
    if(mysqli_query($db, $sql)) {
      //echo "Point total added for week.";
    } else {
      echo mysqli_error($db);
    }

  }    

}

function getWeeklyPoints($db,$userid,$season_year,$season_type,$week) {
  $sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$userid' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'"; 
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($result); 
  return $row['points_sum'];
}

?>