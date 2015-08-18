<?php // Main Pick Selection Page

include("mysql.php");
include("get_winner.php");

// get all users from mysql db
$users = getUsers($db);
foreach($users as $user) {
  $this_userid = $user['user_id'];
  echo "<p>User ".$user['user_display_name']."(id $this_userid)</p>";

  // Get all of the users picks
  $pick_result = mysqli_query($db, "SELECT * FROM picks WHERE user_id='$this_userid'");
  while($user_pick = mysqli_fetch_array($pick_result)) {
    $user_picks[] = $user_pick;
  }

  // Performing SQL query for all games
  $query = "SELECT * FROM game";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());

  while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {

    extract($games,EXTR_PREFIX_ALL,"this"); //load all game variables from db_array

    if(strtotime($this_start_time) < time()) {
      $has_started = true;
    } else {
      $has_started = false;
    }
    if($this_finished=="t") {
      $has_finished = true;
    }else {
      $has_finished = false;
    }


  
    if(isset($user_picks)) { //at least some picks in db

      foreach($user_picks as $pick) {
        if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
          $this_winner = $pick['winner'];
          
        // pass this_winner to a script that checks the actual_winner for the jesus_id in the nfl_db
        // if it returns true, print correct or add to score,,,,
        // if false, print LOSER and don't ++score
          if(strtotime($this_start_time) < time()) {
            if(getGameWinner($this_gsis_id) == $this_winner) {
              if($this_finished == "t") {
                //echo "<span style=\"color:green;\">Correct</span>"; 
                // add point to picks table for user and gsis_id
                addPoint($db,$pick['pick_id'],1,true);
                updatePoints($db,$this_userid,$this_season_year,$this_season_type,$this_week,true);
              } 
            } else {
              if($this_finished == "t") {
                //echo "<span style=\"color:red;\">Loser</span>";
                addPoint($db,$pick['pick_id'],0,true);
                updatePoints($db,$this_userid,$this_season_year,$this_season_type,$this_week,true);
              } 
            }
          }
        } 
      }
    }
  } //End While
} //End User Foreach

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>