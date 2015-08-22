<?php // Main Pick Selection Page

include("mysql.php");
include("functions.php");

function reconcileWeeklyWinners($db,$group_id,$season_year,$week) {
  
  // create an invisible DIV to hold debugging info
  echo "<div id=\"DivSeasonDebugging\" style=\"display: none;\">";
  $season_types = getSeasonTypes(); //get all types

  foreach($season_types as $season_type) {

    $season_weeks = getWeeks($season_year,$season_type);

    foreach($season_weeks as $week) {

		//Check if all games are completed and if a someone has already been marked a winner
    $query = "SELECT * FROM game WHERE season_year='$this_season_year' AND season_type='$season_type' AND week='$week' AND finshed IS FALSE";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    if (!pg_fetch_all($result)) { //all games are finished
    	$query = "SELECT wins FROM points WHERE season_year='$this_season_year' AND season_type='$season_type' AND week='$week' AND wins IS NOT NULL";
    	$result = mysqli_query($db, $query);
      if(mysqli_num_rows($result) > 0) { //A winner has already been determined for this week
        break;
      } else { //continue
      
        $total_weeks += 1;
        if($week < $current_week) { //only count completed weeks
          $num_games = getNumberOfGames($season_year,$season_type,$week);
          $percentages = array();
          $scores = array();

          foreach($users as $user) {
          
            $num_correct = getWeeklyPoints($db,$user['user_id'],$season_year,$season_type,$week);
            $score = getWeeklyScore($db,$user['user_id'],$season_year,$season_type,$week); //returns array with gsis_id and total score
            $percentage = ($num_correct / $num_games) * 100;
            $this_key = $user['user_name'];
            $percentages[$this_key] = $percentage; 
            $scores[$this_key] = $score;

          }//--User
          echo "<br>Scores array<br>";
          print_r($scores);
          echo "<br>Unsorted Percents array:<br>";
          print_r($percentages);
          arsort($percentages);

          $max_p = max($percentages);
          echo "<br>Sorted Percents array:<br>";
          print_r($percentages);
          echo "<br>with a max P of $max_p<br>";

          if(current($percentages) == next($percentages)) { //there is a tie
            reset($percentages);
            do { //remove the low scores
              if(current($percentages) != $max_p) {
                array_shift($percentages);
              }
            } while(next($percentages));
            echo "<br>Possible winners after culling:<br>";
            print_r($percentages);
            //pull game id out of score array      
            foreach($scores as $u => $s) {
              if($s[0] > 0) { 
                $g_id = $s[0]; 
              } else {
                //need some other tie breaker

              }
            }
            if(isset($g_id)) {
              $game_score = getGameScore($g_id);
            } else {
              $game_score = 0;
            }
            foreach($scores as $u => $s) {
              $s[1] -= $game_score;
            }
            $scores = array_intersect_key($scores,$percentages); //remove all but possible winners
            $remaining_scores = array_filter($scores, function($k) { return $k > 0;}); //return only positives, price is right style
            array_multisort($remaining_scores, SORT_ASC, SORT_NUMERIC);
            echo "<br>Possible winners after tie breaker:<br>";
            print_r($sores);

            echo key($scores)." has won tie breaker.<br>";

            $season_wins[key($scores)] +=1;
            //Add a win for the user in the points table
            addWin($db,key($scores),$this_season_year,$season_type,$week,TRUE);

          } else {
            reset($percentages);
            echo key($percentages)." has won on picks.<br>";
            $season_wins[key($percentages)] += 1;
            addWin($db,key($percentages),$this_season_year,$season_type,$week,TRUE);
          }
          do {
            if(current($percentages) != $max_p) {
            echo "<br>percent".current($percentages)." popped.";
            array_pop($percentages);
            }
          } while(next($percentages));
          reset($percentages);
          echo "Winner(s) of Week $week should be:<br>";
          print_r($percentages);
          echo "<br>count=".count($percentages)."key=".key($percentages);

          if(count($percentages) > 1) { //must be a tie 
            
          } elseif(count($percentages) == 1) { //add a win for the user
            echo key($percentages)." has won on picks.<br>";
            $season_wins[key($percentages)] += 1;
            addWin($db,key($percentages),$this_season_year,$season_type,$week,TRUE);
          }
        }//--End IF
    	}//--If
  	}//--Week
	}//--Season
	}
  echo "</div>"; //end debug
} //--End Function


//Use SQL Union to find seasons that belong to any group
//$season_years = getSeasonYears(); //get all years

//$season_types = getSeasonTypes(); //get all types
$sql = "SELECT season_year, season_type, group_id FROM picks\n"
     . "UNION\n"
     . "SELECT season_year, season_type, group_id FROM g_seasons";
$result = mysqli_query($db, $sql) or die(mysqli_error_list($db));
while($row = mysqli_fetch_array($result)) {
    $season_years[] = $row['season_year'];
    $season_types[] = $row['season_type'];
    $groups[] = $row['group_id'];
}
//print_r($season_years);
//print_r($season_types);

foreach($season_years as $season_year) {
  foreach($season_types as $season_type) {

    $season_weeks = getWeeks($season_year,$season_type);

    foreach($season_weeks as $week) {

      //$wins[] = getWeeklyWinner($db,$this_season_year,$display_type,$display_week);
        //$groups = getGroups($db);
        foreach($groups as $group) {
            "<h4>Reconciling $season_year $season_type Week $week for Group $group</h4>";
    	    reconcileWeeklyPoints($db,$group,$season_year,$season_type,$week);
        }
      
    }
  }
}    

//SQL INNER JOIN example to return all user picks for the seasons defined by g_seasons
/*
$sql = "SELECT * \n"
    . "FROM \n"
    . " picks \n"
    . " INNER JOIN\n"
    . " groups \n"
    . " ON picks.group_id=groups.group_id\n"
    . " INNER JOIN\n"
    . " g_seasons \n"
    . " ON groups.group_id=g_seasons.group_id WHERE user_id='$user_id'";
 */

?>