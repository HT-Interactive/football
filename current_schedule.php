<?php

include("get_winner.php");

if(isset($_REQUEST['Show'])) {
  extract($_REQUEST,EXTR_PREFIX_ALL,"this");
} else {
  $query = "SELECT * FROM game WHERE finished=FALSE ORDER BY start_time ASC";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $next_game = pg_fetch_array($result, null, PGSQL_ASSOC);
  //echo $next_game['season_year'];
  $this_year = $next_game['season_year'];
  $this_phase = $next_game['season_type'];
  $this_week = $next_game['week'];

  //$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?year=".$this_year."&phase=".$this_phase."&week=".$this_week;
  //header("Location: http://".$goTo);
  //exit;
}

// Performing SQL query
$query = "SELECT * FROM game WHERE season_year='$this_year' AND season_type='$this_phase' AND week='$this_week' ORDER BY start_time ASC";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE user_id='$this_userid'");
while($user_pick = mysqli_fetch_array($pick_result)) {
  $user_picks[] = $user_pick;
}

//print_r($user_picks);

// Printing results in HTML
echo "<h>$this_year $this_phase Week $this_week</h>";
$my_points = getWeeklyPoints($db,$this_userid,$this_year,$this_phase,$this_week);
echo "<p>You have <b>$my_points points</b> this week. ";
$current_time = date("l h:iA T", time());
echo "The current time is <b>".$current_time."</b></p>";
echo "<table>\n";
while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
    //$home_team = $games['home_team'];
    extract($games,EXTR_PREFIX_ALL,"this");
    $this_start_time_EST = date("h:iA T", strtotime($this_start_time));
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
        if($pick['score']) {
          $this_score = $pick['score'];
        } else {
          $this_score = "";
        }
        if($this_away_team == $this_winner) {
          $away_color = "green";
          $home_color = "black";
        } elseif($this_home_team == $this_winner) {
          $home_color = "green";
          $away_color = "black";
        }
        break;
      } else { 
        $home_color = "black";
        $away_color = "black";
        $this_score = "";
      }
    }
    if(strtotime($this_start_time) > time()) {
      $onclick_away_str = "pickTeam(this,'".$this_userid."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_away_team."')";
      $onclick_home_str = "pickTeam(this,'".$this_userid."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_home_team."')";
    } else {
      $onclick_away_str = "alert('Game Started')";
      $onclick_home_str = "alert('Game Started')";
    }
    echo "\t\t<td id=\"$this_gsis_id"."_away\" style=\"color:$away_color;\" onclick=\"$onclick_away_str\">$this_away_team</td><td>($this_away_score)</td><td>at</td><td id=\"$this_gsis_id"."_home\"style=\"color:$home_color;\" onclick=\"$onclick_home_str\">$this_home_team</td><td>($this_home_score)</td><td>on</td><td>$this_day_of_week</td><td>$this_start_time_EST</td>\n";
    echo "<td>";
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
        
      // pass this_winner to a script that checks the actual_winner for the jesus_id in the nfl_db
      // if it returns true, print correct or add to score,,,,
      // if false, print LOSER and don't ++score
        if(strtotime($this_start_time) < time()) {
          if(getGameWinner($this_gsis_id) == $this_winner) {
            if($this_finished == "t") {
              echo "<span style=\"color:green;\">Correct</span>"; 
              // add point to picks table for user and gsis_id
              addPoint($db,$pick['pick_id'],1);
              updatePoints($db,$this_userid,$this_season_year,$this_season_type,$this_week);
            } else {
              echo "<span style=\"color:green;\">Winning</span>";
            }
          } elseif(getGameWinner($this_gsis_id) == "tied") {
              echo "<span style=\"color:blue;\">Tied</span>";
          } else {
            if($this_finished == "t") {
              echo "<span style=\"color:red;\">Loser</span>";
              addPoint($db,$pick['pick_id'],0);
              updatePoints($db,$this_userid,$this_season_year,$this_season_type,$this_week);
            } else {
              echo "<span style=\"color:red;\">Losing</span>";
            }
          }
        }
       
       
      } else { // show count down timer
        
      }
    }
    echo "</td>";
    
    echo "\t</tr>\n";
}
echo "</table>\n";
if(strtotime($this_start_time) > time()) {
  $score_visibility = "visible";
} else {
  $score_visibility = "hidden";
}
echo "<span id=\"score_span_$this_gsis_id\"> Tiebreaker Score of $this_away_team at $this_home_team:<input type=\"text\" id=\"score\" name=\"score\" value=\"$this_score\" size=\"3\" /><button style=\"visibility:$score_visibility;\" onclick=\"enterScore('$this_userid','$this_gsis_id','$this_season_year','$this_season_type','$this_week',score.value)\">Submit</button></span>";
// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>
