<?php // Standings Display Script

//--LOAD SITE HEADER
// Load Databases and Common functions
require("mysql.php");
include 'common.php';
include("functions.php"); //Site Functions
 
//try to guess the current week, function in get_winners
guessCurrentWeek();

include 'header.php';
//--END SITE HEADER

function subtractScore($user_score,$game_score) {
    return(abs($user_score-$game_score));
}

function displayStandings($totals,$num_games,$units) {
  arsort($totals);
  $i=0;
  foreach($totals as $u => $n) {
    echo "<div class=\"progress-label\">".$u."</div><div class=\"progress\">";
    echo "<div class=\"progress-bar";
    if($i==0 && $n > 0) { echo " progress-bar-success"; }
    $length = ($n/$num_games)*100;
    echo "\" role=\"progressbar\" aria-valuenow=\"$length\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em; width: $length%;\">
    $n ($n/$num_games, $length%)
    </div>
    </div>";
    $i++;
  }
}

function displayWeeklyStandings($db,$users,$season_year,$season_type,$week) {
  $num_games = getNumberOfGames($season_year,$season_type,$week);
  $percentages = array();
  //$monday_night = getMondayNight($season_year,$season_type,$week);
  $monday_night_game_id = getMondayNightGame($season_year,$season_type,$week);
  $monday_kickoff_time = getMondayNightKickoffTime($monday_night_game_id);
  //echo 'Monday Night game '.$monday_night_game_id.' Kick Time '.$monday_kickoff_time;
   

  foreach($users as $user) {
  
    $num_correct = getWeeklyPoints($db,$user['user_id'],$user['group_id'],$season_year,$season_type,$week);
    $score = getWeeklyScore($db,$user['user_id'],$user['group_id'],$monday_night_game_id); //returns array with score, gsis_id and winner
   // $score = getGameScore($monday_night_game_id);
    //$winner = getMondayNightWinner($monday_night_game_id);
    $percentage = ($num_correct / $num_games) * 100;
    //$percentages[$user['user_name']] = $percentage; 
    $this_key = $user['user_name'];
    //$this_game_id = $score[0];
    $totals[$this_key] = $num_correct; 
    $scores[$this_key] = $score[1];
    $winners[$this_key] = $score[2];

  }
  /*
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
  }*/
  arsort($totals);
  $i=0;
  echo '
  <table class="progress-table">
    <tr>';
    if(strtotime($monday_kickoff_time) < time()) { 
        echo '<th class="winner">Monday Night Winner</th><th class="score">Score</th>'; 
        echo '<th class="user">User</th><th class="points">Points</th></tr>';
    }

  foreach($totals as $u => $n) {

      $length = ($n/$num_games)*100;
      $length = round($length);
      $total_str = "$n ($n/$num_games, $length%)";
    echo '
    <tr>';
    if(strtotime($monday_kickoff_time) < time()) { echo '
        <td class="winner">'.$winners[$u].'</td>
        <td class="score">'.$scores[$u].'</td>';
    }
    echo '
        <td class="user">
            <div class="progress-label">'.substr($u,0,12).'</div>
        </td>
        <td class="points">
            <div class="progress">
                <div class="progress-bar';
    if($i==0 && $n > 0) { echo " progress-bar-success"; }
    echo '" role="progressbar" aria-valuenow="'.$length.'" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: '.$length.'%;">'
                    .$total_str.
                '</div>
            </div>
        </td>
    </tr>';
    $i++;
  }
  echo '</table>';
  
} //--End Function

function displaySeasonStandings($db,$users,$season_year,$season_types,$current_week) {
  global $this_group_id;
  // create an invisible DIV to hold debugging info
  echo "<div id=\"DivSeasonDebugging\" style=\"display: none;\">\n";

  //$season_types = getSeasonTypes(); //get all types
  $season_wins = array(); //initialize win placeholder
  $total_weeks = 0;
         
  //check if week is reconciled yet
  $sql = "SELECT user_id, COUNT(winner) as wins FROM points WHERE season_year='$season_year' AND group_id='$this_group_id' AND reconciled IS NOT NULL GROUP BY user_id ORDER BY wins DESC";
  $result = mysqli_query($db,$sql);
  $row_cnt = mysqli_num_rows($result);
  $row = mysqli_fetch_array($result,MYSQL_ASSOC);

  //  if(!is_null($row['user_id'])) { // completely unreconiled db's show num_rows of 1 because of SELECT COUNT, so check that it is real
  if(mysqli_num_rows($result) > 0) {
        //just build the $season_wins[$user_name] array from the previous DB Result
        $is_reconciled = TRUE;
        echo '<h2>DB is Reconciled so using those results.</h2>';
        print_r($row);
        do { //add winnders
            $this_key = getUserNameFromId($db,$row['user_id']);
            $this_val = $row['wins'];
            $total_weeks += $this_val;
            $season_wins[$this_key] = $this_val;    
        } while($row = mysqli_fetch_assoc($result));
        foreach($users as $user) {
            $this_key = getUserNameFromId($db,$user['user_id']);
            if(!array_key_exists($this_key,$season_wins)) {
                $season_wins[$this_key] = 0;
            }
        }
        echo "<p>Season Wins:";
        print_r($season_wins);
        echo "</p>\n";
   } else {//try to reconcile if all games are complete for the week
        echo "Empty result";
        foreach($users as $user) {
            $this_key = getUserNameFromId($db,$user['user_id']);
            if(!array_key_exists($this_key,$season_wins)) {
                $season_wins[$this_key] = 0;
            }
        }
   
      /*
        $is_reconciled = FALSE;
        
        echo '<h2>Find Winners and Reconcile DB</h2>';
        foreach($users as $user) { //set each user to 0 wins
            $season_wins[$user['user_name']] = 0;
        }
       
        foreach($season_types as $season_type) {

        $season_weeks = getWeeks($season_year,$season_type);
        echo "<p>Weeks this season:";
        print_r($season_weeks);
        echo "</p>\n";
        echo "<p>Current week is: $current_week";
        $total_weeks += count($season_weeks);
        echo "</p>\n";
        echo "<p>Week Count.: $total_weeks";
        echo "</p>\n";

        foreach($season_weeks as $week) {
            if(!allGamesFinished($season_year,$season_type,$week)) { //skip reconcil
                echo '<h2>Week Not Over</h2>\n';
                print_r($row);
                foreach($users as $user) {
                    $this_key = getUserNameFromId($db,$user['user_id']);
                    if(!array_key_exists($this_key,$season_wins)) {
                        $season_wins[$this_key] = 0;
                    }
                }
                break;
            } else { //try to reconcile
                echo '<h2>Find Winners and Reconcile DB</h2>';
                foreach($users as $user) { //set each user to 0 wins
                    $season_wins[$user['user_name']] = 0;
                }
                echo "<p>Season Wins:";
                print_r($season_wins);
                echo "</p>\n";
          
                if($week < $current_week) { //only count completed weeks
                    $num_games = getNumberOfGames($season_year,$season_type,$week);
                    $totals = array();
                    $scores = array();
 
                    foreach($users as $user) {    
                        $num_correct = getWeeklyPoints($db,$user['user_id'],$user['group_id'],$season_year,$season_type,$week);
                        $score = getWeeklyScore($db,$user['user_id'],$user['group_id'],$season_year,$season_type,$week); //returns array with gsis_id and total score
                        $this_key = $user['user_name'];
                        $this_game_id = $score[0];
                        $totals[$this_key] = $num_correct; 
                        $scores[$this_key] = $score[1];
                        echo "<p>User ".$this_key." has ".$totals[$this_key]." and a guess of score ".$scores[$this_key]." for game ".$this_game_id;
                        //print_r($score);
                        echo "</p>\n";
                        $percentage = ($num_correct / $num_games) * 100;
                    }  
        
                    echo "\n<br>Scores array<br>\n";
                    print_r($scores);
                    echo "<br>Unsorted Totals array:<br>\n";
                    print_r($totals);

                    arsort($totals);

                    $top_score = max($totals);
                    echo "\n<br>Sorted Totals array:<br>\n";
                    print_r($totals);
                    echo "\n<br>with a Top Score of $top_score<br>\n";

                    if(count($totals) <= 1 ) {

                    } elseif(current($totals) == next($totals)) { //there is a tie
                        reset($totals);
                        echo "<p>It's a tie!</p>\n";
                        do { //remove the low scores
                            if(current($totals) != $top_score) {
                                array_pop($totals);
                            }
                        } while(next($totals));

                        echo "<br>Possible winners after culling:<br>\n";
                        print_r($totals);
  
                        echo "<br>Score Array:<br>\n";
                        print_r($scores);

                        if(isset($this_game_id)) {
                            $game_score = getGameScore($this_game_id);
                        } else {
                            $game_score = 0;
                        }
                        echo "<br>Subtract Actual Game score of $game_score.<br>\n";
          
                        foreach($scores as $u => $s) {
                            $score_diffs[$u] = abs($s - $game_score);
                        }
         
                        echo "<br>Unsorted Score Differentials:<br>\n";
                        print_r($score_diffs);
                        asort($score_diffs);
                        echo "<br>Sorted Score Differentials:<br>\n";
                        print_r($score_diffs);
                        $lowest_diff = max($score_diffs);
                        echo "<br>with a Lowest Diff of $lowest_diff<br>\n";

                        if(current($score_diffs) == next($score_diffs)) { //there is a another tie
                            $winner = key($score_diffs); //flip a proverbial coin
                            echo $winner." has won the second tie breaker through a random selection process.<br>\n";
                            reconcileWinners($db,$winner,$user['group_id'],$season_year,$season_type,$week,$verbose=TRUE);
                            $season_wins[$winner] += 1;
                            echo "<p>".print_r($season_wins)."</p>\n";
                        } else {
                            reset($score_diffs);
                            $winner = key($score_diffs);
                            echo $winner." has won the tie breaker with a score differential of $lowest_diff.<br>\n";
                            reconcileWinners($db,$winner,$user['group_id'],$season_year,$season_type,$week,$verbose=TRUE);
                            $season_wins[$winner] += 1;
                            echo "<p>".print_r($season_wins)."</p>\n";
                        }

                    } else {
                        reset($totals);
                        $winner = key($totals);
                        echo $winner." has won on picks. ";
                        //Reconcile weekly point total and flag winner in DB
                        reconcileWinners($db,$winner,$user['group_id'],$season_year,$season_type,$week,$verbose=TRUE);
                        $season_wins[$winner] += 1;
                        echo "<p>".print_r($season_wins)."</p>\n";
                    }//End Tie Determination
                 }//--End IF current week check
            } //reconcile
        }//--Week
      }//End foreach Season 
     */ 
   }//END reconciliation check
   
    echo "</div>\n"; //end debug

//Display winner graph
    //print_r($season_wins);
    //echo "<div style=\"border: 1px solid green;\">";
    echo "<div id=\"DivSeasonWins\">\n";
    if($total_weeks > 0) {
        arsort($season_wins);
        $i=0;
        foreach($season_wins as $u => $w) {
            $p = ($w / $total_weeks ) * 100;
            $p =round($p);
            echo "<div class=\"progress-label\">".substr($u,0,12)."</div><div class=\"progress\">\n";
            echo "\t<div class=\"progress-bar";
            if($i==0 && $p > 0) { echo " progress-bar-success"; }
            echo "\" role=\"progressbar\" aria-valuenow=\"$p\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em; width: $p%\">\n
            $w ($w/$total_weeks, $p%)\n
            </div>\n
            </div>\n";
            $i++;
        }
        echo "</div>\n";

        echo "<div id=\"DivSeasonDollars\" style=\"position: absolute; visibility: hidden;\">\n";
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
        echo "<div class=\"progress-label\">".substr($u,0,12)."</div><div class=\"progress\">\n";
        if($winnings_p > $break_even_p) { 
            echo "<div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" style=\"min-width: 2em; width: $break_even_p%\"></div>\n";
            $whats_left = $winnings_p - $break_even_p;
            echo "<div class=\"progress-bar progress-bar-success\" role=\"progressbar\" style=\"min-width: 2em; width: $whats_left%\">&#36;$current_balance</div>\n";
        } else {
            echo "<div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" style=\"min-width: 2em; width: $winnings_p%\">&#36;$current_balance</div>\n";
        }
        echo "</div>\n";
        }
        
        //echo "</div>";
    } else {
        echo "<p>Season Standings will be available after the end of this week.</p>";  
    }
    echo "</div>\n";
//calculateWinnings($users,$weeks,$wins,$anty)
} //--End Function

    // get all users from mysql db
    $users = getUsers($db,$this_group_id);
    $last_week = $current_week - 1;
    $weekly_results_title = "Current Week Results <small>($current_season_year $current_season_type Week $current_week)</small>";
    $season_results_title = "$this_season_year Season Standings <small>(Through $current_season_year $current_season_type Week $last_week)</small>";
    // Accordion style
    //echo '
    //<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      echo '
        <div class="panel panel-default">
            <div class="panel-heading" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    '
                    .$weekly_results_title.
                    '
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">';
                    $first_kickoff_time_of_week = getFirstKickoffOfWeek($current_season_year,$current_season_type,$current_week);
                    $last_week = $current_week -1;
                    if(strtotime($first_kickoff_time_of_week) > time()) {
                        displayWeeklyStandings($db,$users,$current_season_year,$current_season_type,$last_week);
                    } else {
                        displayWeeklyStandings($db,$users,$current_season_year,$current_season_type,$current_week);
                    }
    echo '
                </div>
                <div class="panel-footer"></div>
            </div>
        </div><!--Panel -->';

    echo '
        <div class="panel panel-default">
            <div class="panel-heading" id="headingTwo">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    '
                    .$season_results_title.
                    '
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">';
                    $these_types = getGroupSeasonTypes($db,$this_group_id,$this_season_year);
                    displaySeasonStandings($db,$users,$this_season_year,$these_types,$current_week);
    echo '
                </div>
                <div class="panel-footer">
                    <div class="btn-group unit-selector" role="group" aria-label="...">
                        <button type="button" id="ButtonSeasonWins" class="btn btn-default btn-xs" onclick="showStandings(this,\'wins\')"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Wins</button>
                        <button type="button" id="ButtonSeasonDollars" class="btn btn-default btn-xs" style="background-color: #eee;" onclick="showStandings(this,\'dollars\')"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> </button>
                        <button type="button" id="ButtonSeasonWins" class="btn btn-default btn-xs" onclick="showDebugging(this)"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Debug</button>
                    </div>
                </div>
            </div>
        </div><!--Panel -->';


/*
    echo '
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" id="headingTwo">
      <div class="panel-heading">'.$this_season_year.' Season Standings
    </div>';
    echo '<div class="panel-body">';
    $these_types = getGroupSeasonTypes($db,$this_group_id,$this_season_year);
    displaySeasonStandings($db,$users,$this_season_year,$these_types,$current_week);
    echo '
    </div>
      <div class="panel-footer">';
      echo "<div class=\"btn-group unit-selector\" role=\"group\" aria-label=\"...\">
            <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default btn-xs\" onclick=\"showStandings(this,'wins')\"><span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\"></span> Wins</button>
            <button type=\"button\" id=\"ButtonSeasonDollars\" class=\"btn btn-default btn-xs\" style=\"background-color: #eee;\" onclick=\"showStandings(this,'dollars')\"><span class=\"glyphicon glyphicon-usd\" aria-hidden=\"true\"></span> </button>
            <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default btn-xs\" onclick=\"showDebugging(this)\"><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> Debug</button>
          </div>
      </div>";
    echo '</div>';
*/
  /*
  echo "<h2>Current Week Standings for ".getGroupName($db,$this_group_id)." <small>($current_season_year $current_season_type $current_week)</small></h2>";
  displayWeeklyStandings($db,$users,$current_season_year,$current_season_type,$current_week);


  echo "<h2>Current Season Standings for ".getGroupName($db,$this_group_id)." <small>($this_season_year)</small>";
  echo "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">
  <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default\" onclick=\"showStandings(this,'wins')\"><span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\"></span> Wins</button>
  <button type=\"button\" id=\"ButtonSeasonDollars\" class=\"btn btn-default\" style=\"background-color: #eee;\" onclick=\"showStandings(this,'dollars')\"><span class=\"glyphicon glyphicon-usd\" aria-hidden=\"true\"></span> </button>
  <button type=\"button\" id=\"ButtonSeasonWins\" class=\"btn btn-default btn-xs\" onclick=\"showDebugging(this)\"><span class=\"glyphicon glyphicon-warning-sign\" aria-hidden=\"true\"></span> Debug</button>
  </div></h2>";
  $these_types[] = getGroupSeasonTypes($db,$this_group_id,$this_season_year);
  //print_r($these_types);
  displaySeasonStandings($db,$users,$this_season_year,$these_types,$current_week);
  */
  //print_r($season_types);
   
  //foreach($users as $user) {
  //  $i = $user['user_id'];
  //  echo $user['user_name'].getUserWins($db,$i,$this_season_year)."<br>";
  //}
  //print_r($wins);
  
  //foreach($users as $user) {
  //  echo $user['user_name'].getSeasonPoints($db,$user['user_id'],$this_season_year)."<br>";
  //}
  
  echo "\n<h3>Results By Week</h3>\n";
  echo '
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';

  $season_types = getGroupSeasonTypes($db,$this_group_id,$this_season_year); 

  foreach($season_types as $display_type) {

    echo "<h4>$display_type <small>($this_season_year)</small></h4>\n";

    $season_weeks = getWeeks($this_season_year,$display_type);
    $i=1;
    foreach($season_weeks as $display_week) {
      if($display_week < $current_week) {
        //$wins[] = getWeeklyWinner($db,$this_season_year,$display_type,$display_week);
        echo '
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingWeek'.$display_type.$display_week.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseWeek'.$display_type.$display_week.'" aria-expanded="true" aria-controls="collapseWeek'.$display_type.$display_week.'">Week '.$display_week.' <small>('.$display_type.' '.$this_season_year.')</small></a>
                </h4>
            </div>
            <div id="collapseWeek'.$display_type.$display_week.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">';
                    displayWeeklyStandings($db,$users,$this_season_year,$display_type,$display_week);
        echo '
                </div>
                <div class="panel-footer"></div>
            </div>
        </div><!--Panel -->';

      }
      $i++;
    }

  }
  echo '
  </div><!--Panel Group -->';

//--Main Footer
include 'footer.php';

?>