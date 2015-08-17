<?php // Main Pick Selection Page

// Performing SQL query for correct week
$query = "SELECT * FROM game WHERE season_year='$this_season_year' AND season_type='$this_season_type' AND week='$this_week' ORDER BY start_time ASC";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Get all of the users picks
$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE user_id='$this_userid'");
while($user_pick = mysqli_fetch_array($pick_result)) {
  $user_picks[] = $user_pick;
}

//echo "<div class=\"row\">\n<div class=\"col-md-12\">\n<table class=\"pickTable\">\n";
//echo "<div>\n<div>\n<table class=\"pickTable\">\n";
echo "<table class=\"pickTable\">\n";

while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {

  echo "\t<tr>\n"; //start new row in table

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


  $this_start_time_EST = date("h:iA T", strtotime($this_start_time));

  if(isset($user_picks)) { //at least some picks in db
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
        if($pick['score']) {
          $this_score = $pick['score'];
        } else {
          $this_score = "";
        }
        if($this_away_team == $this_winner) {
          $away_style = "color:green;background-color:LightGray;";
          $home_style = "color:black;background-color:#eee";
        } elseif($this_home_team == $this_winner) {
          $home_style = "color:green;background-color:LightGray;";
          $away_style = "color:black;background-color:#eee";        }
        break;
      } else { 
        $home_style = "color:black;background-color:#eee";
        $away_style = "color:black;background-color:#eee";
        $this_score = "";
      }
    }
  } else { //user has made no picks so default to black
    $home_color = "black";
    $away_color = "black";
    $this_score = "";
  }
  if(!$has_started) {
    $onclick_away_str = "pickTeam(this,'".$this_userid."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_away_team."')";
    $onclick_home_str = "pickTeam(this,'".$this_userid."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_home_team."')";
  } else {
    $onclick_away_str = "alert('Game Started')";
    $onclick_home_str = "alert('Game Started')";
  }
  echo "\t\t<td><div id=\"$this_gsis_id"."_away\" onclick=\"$onclick_away_str\" class=\"teamCell\" style=\"text-align:right;$away_style;\">$this_away_team</div></td><td>";
  if($has_started) {
    echo "<span class=\"badge\">$this_away_score</span>";
  }
  echo "</td><td>at</td><td>";
  if($has_started) {
    echo "<span class=\"badge\">$this_home_score</span>";
  }
  echo "</td><td><div id=\"$this_gsis_id"."_home\" onclick=\"$onclick_home_str\" class=\"teamCell\" style=\"text-align:left;$home_style;\">$this_home_team</div></td><td>on</td><td class=\"dayCell\">$this_day_of_week</td><td class=\"timeCell\">$this_start_time_EST</td>\n";
  echo "<td class=\"resultCell\">";
  //echo "<button onclick=\"alert('Test')\">Test Me</button>";

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
  }
  echo "</td>";
  
  echo "\t</tr>\n";

} //End While

if(strtotime($this_start_time) > time()) {
  $score_visibility = "visible";
} else {
  $score_visibility = "hidden";
}
echo "<tr><td colspan=\"9\"><p id=\"score_span_$this_gsis_id\" class=\"form-inline\"><label for=\"score\">Tiebreaker Score of $this_away_team at $this_home_team</label><input type=\"text\" id=\"score\" class=\"form-control\" name=\"score\" value=\"$this_score\" class=\"form-control\" size=\"3\" /><button class=\"btn btn-primary btn-sm\" style=\"visibility:$score_visibility;\" onclick=\"enterScore('$this_userid','$this_gsis_id','$this_season_year','$this_season_type','$this_week',score.value)\">Submit</button></p></td></tr>";
echo "</table>";
//echo "</div>\n</div>";

//echo "<div class=\"row\">\n<div class=\"col-md-12\">\n<p id=\"score_span_$this_gsis_id\" class=\"form-inline\"><label for=\"score\">Tiebreaker Score of $this_away_team at $this_home_team</label><input type=\"text\" id=\"score\" class=\"form-control\" name=\"score\" value=\"$this_score\" class=\"form-control\" size=\"3\" /><button class=\"btn btn-primary btn-sm\" style=\"visibility:$score_visibility;\" onclick=\"enterScore('$this_userid','$this_gsis_id','$this_season_year','$this_season_type','$this_week',score.value)\">Submit</button></p>";
//echo "</div>\n</div>";

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>