
<?php

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
$current_time = date("Y m d h:i T", time());
echo "<p>".$current_time."</p>";
echo "<table>\n";
while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
    //$home_team = $games['home_team'];
    extract($games,EXTR_PREFIX_ALL,"this");
    $this_start_time_EST = date("l h:iA T", strtotime($this_start_time));
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
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
      }
    }
    if(strtotime($this_start_time) > time()) {
      echo "\t\t<td id=\"$this_gsis_id"."_away\" style=\"color:$away_color;\" onclick=\"pickTeam(this,'$this_userid','$this_gsis_id','$this_away_team')\">$this_away_team</td><td>at</td><td id=\"$this_gsis_id"."_home\"style=\"color:$home_color;\" onclick=\"pickTeam(this,'$this_userid','$this_gsis_id','$this_home_team')\">$this_home_team</td><td>on</td><td>$this_start_time_EST</td>\n";
    } else {
      echo "\t\t<td id=\"$this_gsis_id"."_away\" style=\"color:$away_color;\">$this_away_team</td><td>at</td><td id=\"$this_gsis_id"."_home\" style=\"color:$home_color;\">$this_home_team</td><td>on</td><td>$this_start_time_EST</td>\n";
    }
    echo "<td>";
    foreach($user_picks as $pick) {
      if($pick['game_id'] == $this_gsis_id) { //user has already picked game so diplay winner
        $this_winner = $pick['winner'];
        echo "$this_winner Selected as winner.";
      } else { // show count down timer
        
      }
    }
    echo "</td>";
    
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>
