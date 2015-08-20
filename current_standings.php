<?php // Standings Display Script

function displayWeeklyStandings($db,$users,$season_year,$season_type,$week) {
  $num_games = getNumberOfGames($season_year,$season_type,$week);
  $percentages = array();

  foreach($users as $user) {
  
    $num_correct = getWeeklyPoints($db,$user['user_id'],$user['group_id'],$season_year,$season_type,$week);
    $percentage = ($num_correct / $num_games) * 100;
    $percentages[$user['user_name']] = $percentage; 

  }

  arsort($percentages);
  $i=0;
  foreach($percentages as $u => $p) {
    echo "<div class=\"progress-label\">".$u."</div><div class=\"progress\">";
    echo "<div class=\"progress-bar";
    if($i==0 && $p > 0) { echo " progress-bar-success"; }
    echo "\" role=\"progressbar\" aria-valuenow=\"$p\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em; width: $p%\">
    $p%
    </div>
    </div>";
    $i++;
  }

} //--End Function

function displaySeasonStandings($db,$users,$season_year,$current_week) {
  
  // create an invisible DIV to hold debugging info
  echo "<div id=\"DivSeasonDebugging\" style=\"display: none;\">";
  $season_types = getSeasonTypes(); //get all types
  $season_wins = array(); //initialize win placeholder
  $total_weeks = 0;

  foreach($users as $user) { //set each user to 0 wins
    $season_wins[$user['user_name']] = 0;
  }
  //print_r($season_wins);

  foreach($season_types as $season_type) {

    $season_weeks = getWeeks($season_year,$season_type);

    foreach($season_weeks as $week) {
      $total_weeks += 1;
      if($week < $current_week) { //only count completed weeks
        $num_games = getNumberOfGames($season_year,$season_type,$week);
        $percentages = array();
        $scores = array();

        foreach($users as $user) {
        
          $num_correct = getWeeklyPoints($db,$user['user_id'],$user['group_id'],$season_year,$season_type,$week);
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
          array_multisort($scores, SORT_ASC, SORT_NUMERIC);

          echo key($scores)." has won tie breaker.<br>";

          $season_wins[key($scores)] +=1;


        } else {
          reset($percentages);
          echo key($percentages)." has won on picks.<br>";
          $season_wins[key($percentages)] += 1;
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
        }
      }//--End IF
    }//--Week
  }//--Season
  echo "</div>"; //end debug
  //print_r($season_wins);
  //echo "<div style=\"border: 1px solid green;\">";
  echo "<div id=\"DivSeasonWins\">";
  arsort($season_wins);
  $i=0;
  foreach($season_wins as $u => $w) {
    $p = ($w / $total_weeks ) * 100;
    echo "<div class=\"progress-label\">".$u."</div><div class=\"progress\">";
    echo "<div class=\"progress-bar";
    if($i==0 && $p > 0) { echo " progress-bar-success"; }
    echo "\" role=\"progressbar\" aria-valuenow=\"$p\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em; width: $p%\">
    $w
    </div>
    </div>";
    $i++;
  }
  echo "</div>";

  echo "<div id=\"DivSeasonDollars\" style=\"position: absolute; visibility: hidden;\">";
  // each user bar should go from -34 to max
  //$i=0;
  $anty = 2;
  foreach($season_wins as $u => $w) {
    //starting balance = weeks*anty
    //total pot = users * weeks * anty
    //winnings = (wins * users * anty) - (total_weeks*anty)
    $starting_anty = $total_weeks * $anty;
    $starting_balance = $starting_anty * -1;
    $max_winnings = ($total_weeks * count($users) * $anty);
    $max_balance = $max_winnings - $starting_anty;
    $winnings = ($w * count($users) * $anty); 
    $current_balance = $starting_balance + $winnings;
    $winnings_p = ($winnings / $max_winnings ) * 100;
    $break_even_p = ($starting_anty / $max_winnings ) * 100; 
    echo "<div class=\"progress-label\">".$u."</div><div class=\"progress\">";
    if($winnings_p > $break_even_p) { 
      echo "<div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" style=\"min-width: 2em; width: $break_even_p%\"></div>";
      $whats_left = $winnings_p - $break_even_p;
      echo "<div class=\"progress-bar progress-bar-success\" role=\"progressbar\" style=\"min-width: 2em; width: $whats_left%\">&#36;$current_balance</div>";
    } else {
      echo "<div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" style=\"min-width: 2em; width: $winnings_p%\">&#36;$current_balance</div>";
    }
    echo "</div>";
  }
  echo "</div>";
  //echo "</div>";

//calculateWinnings($users,$weeks,$wins,$anty)
} //--End Function

  // get all users from mysql db
  $users = getUsers($db,$this_group_id);

  echo "<h2>Current Week Standings for ".getGroupName($db,$this_group_id)." <small>($current_season_year $current_season_type $current_week)</small></h2>";
  displayWeeklyStandings($db,$users,$current_season_year,$current_season_type,$current_week);

  echo "<h2>Current Season Standings for ".getGroupName($db,$this_group_id)." <small>($this_season_year)</small>";
  echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">
  <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default\" onclick=\"showStandings(this,'wins')\"><span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\"></span> Wins</button>
  <button type=\"button\" id=\"ButtonSeasonDollars\" class=\"btn btn-default\" style=\"background-color: #eee;\" onclick=\"showStandings(this,'dollars')\"><span class=\"glyphicon glyphicon-usd\" aria-hidden=\"true\"></span> </button>
  <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default btn-xs\" onclick=\"showDebugging(this)\"><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> Debug</button>
  </div></h2>";
  displaySeasonStandings($db,$users,$this_season_year,$current_week);
  
  //print_r($season_types);
   
  //foreach($users as $user) {
  //  $i = $user['user_id'];
  //  echo $user['user_name'].getUserWins($db,$i,$this_season_year)."<br>";
  //}
  //print_r($wins);
  
  //foreach($users as $user) {
  //  echo $user['user_name'].getSeasonPoints($db,$user['user_id'],$this_season_year)."<br>";
  //}
  
  echo "<h2>Results By Week</h2>";
  $season_types = getSeasonTypes(); 
  foreach($season_types as $display_type) {

    echo "<h3>$display_type <small>($this_season_year)</small></h3>";

    $season_weeks = getWeeks($this_season_year,$display_type);

    foreach($season_weeks as $display_week) {
      if($display_week < $current_week) {
        //$wins[] = getWeeklyWinner($db,$this_season_year,$display_type,$display_week);
      	echo "<h4>Week $display_week <small>($display_type $this_season_year)</small></h4>";
      	displayWeeklyStandings($db,$users,$this_season_year,$display_type,$display_week);
      }
    }

  }
  //print_r($wins);

  
  // search mysql table and sum points for each user for  each season type week


?>