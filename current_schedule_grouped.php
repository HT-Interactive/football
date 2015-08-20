<?php // Main Pick Selection Page

//Turn off timer for testing
$timer_on = false;

// Performing SQL query for correct week
$query = "SELECT * FROM game WHERE season_year='$this_season_year' AND season_type='$this_season_type' AND week='$this_week' ORDER BY start_time ASC";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Get all of the users picks
$pick_result = mysqli_query($db, "SELECT * FROM picks WHERE user_id='$this_user_id'");
while($user_pick = mysqli_fetch_array($pick_result)) {
  $user_picks[] = $user_pick;
}

//echo "<div class=\"row\">\n<div class=\"col-md-12\">\n<table class=\"pickTable\">\n";
//echo "<div>\n<div>\n<table class=\"pickTable\">\n";
if(!$timer_on) {
  echo "<div class=\"alert alert-warning\" role=\"alert\">Game Start Timer Inactive</div>";
}
echo "<table class=\"pickTable\">\n";

while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {
  //print_r($games);

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

  if(!isset($last_day_of_week) || $this_day_of_week != $last_day_of_week) { //print date row
    echo "<tr><td colspan=\"6\">";
    echo date('l, F j Y',strtotime($this_start_time));
    echo "</td></tr>";
  }

  $last_day_of_week = $this_day_of_week; //set last day to this day for next iteration

  $this_start_time_EST = date("g:iA T", strtotime($this_start_time));

  if(isset($user_picks)) { //at least some picks in db
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
        if($pick['score']) {
          $this_score = $pick['score'];
        } else {
          $this_score = "";
        }
      //Selection formatting, remember to change the football.js pickTeam script to match
        if($this_away_team == $this_winner) {
          $away_style = "color:green;background-color:LightGray;";
          $home_style = "color:black;background-color:#eee";
        } elseif($this_home_team == $this_winner) {
          $home_style = "color:green;background-color:LightGray;";
          $away_style = "color:black;background-color:#eee";        
        }

      // pass this_winner to a script that checks the actual_winner for the jesus_id in the nfl_db
      // if it returns true, print correct or add to score,,,,
      // if false, print LOSER and don't ++score
        if(strtotime($this_start_time) < time()) {
          if(getGameWinner($this_gsis_id) == $this_winner) {
            if($this_finished == "t") {
              $result_span = "correct";
              //echo "<span style=\"color:green;\">Correct</span>"; 
              // add point to picks table for user and gsis_id
              addPoint($db,$pick['pick_id'],1,false);
              updatePoints($db,$this_user_id,$this_season_year,$this_season_type,$this_week,false);
            } else {
              $result_span = "winning";
              //echo "<span style=\"color:green;\">Winning</span>";
            }
          } elseif(getGameWinner($this_gsis_id) == "tied") {
              $result_span = "tied";
              //echo "<span style=\"color:blue;\">Tied</span>";
          } else {
            if($this_finished == "t") {
              $result_span = "incorrect";
              //echo "<span style=\"color:red;\">Loser</span>";
              addPoint($db,$pick['pick_id'],0,false);
              updatePoints($db,$this_user_id,$this_season_year,$this_season_type,$this_week,false);
            } else {
              $result_span = "losing";
              //echo "<span style=\"color:red;\">Losing</span>";
            }
          }
        } else {
          $result_span = "";
        }

        switch($result_span) {
          case "correct":
            $result_span = sprintf("<span class=\"glyphicon glyphicon-%s\" aria-hidden=\"true\" style=\"color:%s\"></span>","ok","green");
            break;
          case "incorrect":
            $result_span = sprintf("<span class=\"glyphicon glyphicon-%s\" aria-hidden=\"true\" style=\"color:%s\"></span>","remove", "red");
            break;
          case "winning":
            $result_span = sprintf("<span class=\"glyphicon glyphicon-%s\" aria-hidden=\"true\" style=\"color:%s\"></span>","arrow-up", "green");
            break;
          case "losing":
            $result_span = sprintf("<span class=\"glyphicon glyphicon-%s\" aria-hidden=\"true\" style=\"color:%s\"></span>","arrow-down", "red");
            break;
          case "tied":
            $result_span = sprintf("<span class=\"glyphicon glyphicon-%s\" aria-hidden=\"true\" style=\"color:%s\"></span>","sort", "blue");
            break;
        }
        break;

      } else { 
        $home_style = "color:black;background-color:#eee";
        $away_style = "color:black;background-color:#eee";
        $this_score = "";
      }
    }
  } else { //user has made no picks so default to black
    $home_style = "color:black;background-color:#eee";
    $away_style = "color:black;background-color:#eee";
    $this_score = "";
  }
  if($has_started && $timer_on) { // alert the user that it is too late
    $onclick_away_str = "alert('It's too late to turn back now.')";
    $onclick_home_str = "alert('It's too late to turn back now.')";
  } else { // add pickTeam script to element    
    $onclick_away_str = "pickTeam(this,'".$this_user_id."','".$this_group_id."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_away_team."')";
    $onclick_home_str = "pickTeam(this,'".$this_user_id."','".$this_group_id."','".$this_gsis_id."','".$this_season_year."','".$this_season_type."','".$this_week."','".$this_home_team."')";
  }

//Game Row start
  echo "\t<tr>\n"; //start new row in table

//Away Team Cell
  echo "<td><div id=\"$this_gsis_id"."_away\" onclick=\"$onclick_away_str\" class=\"teamCell\" style=\"text-align:right;$away_style;\">$this_away_team</div></td>";
//Away Team Score
  echo "<td>";
  if($has_started) {
    echo "<span class=\"badge\">$this_away_score</span>";
  }
  echo "</td>";
  echo "<td>at</td>";
//Home Team Score
  echo "<td>";
  if($has_started) {
    echo "<span class=\"badge\">$this_home_score</span>";
  }
  echo "</td>";
//Home Team Cell
  echo "<td><div id=\"$this_gsis_id"."_home\" onclick=\"$onclick_home_str\" class=\"teamCell\" style=\"text-align:left;$home_style;\">$this_home_team</div></td>";
//Result Cell
  echo "<td class=\"dayCell\">";
  if(isset($result_span)) {
    echo $result_span;
   }
   echo "</td>";
//Game Date
  //echo "<td>at</td>";
  //echo "<td class=\"dayCell\">$this_day_of_week</td>";
  //echo "<td class=\"timeCell\">";
  //echo date('F j Y',strtotime($this_start_time));
 // echo "</td>";
  echo "<td class=\"dayCell\">at $this_start_time_EST</td>";
//Pick Result
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
              addPoint($db,$pick['pick_id'],1,false);
              updatePoints($db,$this_user_id,$this_season_year,$this_season_type,$this_week,false);
            } else {
              echo "<span style=\"color:green;\">Winning</span>";
            }
          } elseif(getGameWinner($this_gsis_id) == "tied") {
              echo "<span style=\"color:blue;\">Tied</span>";
          } else {
            if($this_finished == "t") {
              echo "<span style=\"color:red;\">Loser</span>";
              addPoint($db,$pick['pick_id'],0,false);
              updatePoints($db,$this_user_id,$this_season_year,$this_season_type,$this_week,false);
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
  
  echo "</tr>";

} //End While

echo "</table>";
echo "<form class=\"form-inline\">";
echo "<div class=\"input-group input-group-sm\">
  <span class=\"input-group-addon\" id=\"basic-addon1\">Score of $this_away_team at $this_home_team</span>
  <input type=\"text\" id=\"score\" class=\"form-control\" name=\"score\" value=\"$this_score\" size=\"1\" />";

if(isset($this_gsis_id)) {
  if($has_started && $timer_on) {
    $current_score = $this_home_score + $this_away_score;
    $score_diff = $this_score - $current_score;
    echo "<span class=\"input-group-addon\" id=\"basic-addon2\">You are off by $score_diff points.</span>";
  } else {
    echo "<span class=\"input-group-btn\">
    <button class=\"btn btn-primary\" type=\"button\" onclick=\"enterScore('$this_user_id','$this_group_id','$this_gsis_id','$this_season_year','$this_season_type','$this_week',score.value)\">Submit</button>
    </span>";
  }

  echo "</div></form>";

} else {
  echo "<p>No games this week.</p>";
}

//echo "</div>\n</div>";

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>