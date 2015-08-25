<?php
//reconcile_picks.php

//pseudocode flow
//get all non-reconciled PICKS   
//compare the PICK winner to the actual winner by querying the NFL-DB with the game_id
//if the game has started, check if the user picked correctly 
//if yes, set picks.points to 1; else, set picks.points to 0 
//check to see if the game has finished
//if yes, mark the Pick as Reconciled; else, exit; (or loop back)
include("mysql.php");
include_once("functions.php");

//get all non-reconciled PICKS   
// Get all of the users picks
$sql = "SELECT * FROM picks WHERE reconciled IS NULL";
$pick_result = mysqli_query($db, $sql) or die(mysqli_error($db));
while($user_pick = mysqli_fetch_array($pick_result)) {
    extract($user_pick,EXTR_PREFIX_ALL,"user");
//$user_pick['pick_id'],$user_pick['group_id']
    $this_gsis_id = $user_pick['game_id'];
    $query = "SELECT * FROM game WHERE gsis_id='$this_gsis_id'";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $game = pg_fetch_array($result, null, PGSQL_ASSOC);
    //$game['start_time']
    extract($game,EXTR_PREFIX_ALL,"game"); //load all game variables from db_array

    if(strtotime($game_start_time) < time()) {
      $has_started = true;
    } else {
      $has_started = false;
    }
    if($game_finished=="t") {
      $has_finished = true;
    }else {
      $has_finished = false;
    }

    if($has_started) {
        if($game['home_score'] > $game['away_score']) {
            $winning_team = $game['home_team'];
        } elseif($game['home_score']==$game['away_score']) {
            $winning_team = "tied";
        } else {
            $winning_team = $game['away_team'];
        }

        if($winning_team == $user_winner) {
            //pick correct
            addPoint($db,$user_pick_id,1,FALSE);
            updatePoints($db,$user_user_id,$user_group_id,$user_season_year,$user_season_type,$user_week,false);
        } else { 
            //($winning_team != $user_winner || $winning_team == 'tied') 
            addPoint($db,$user_pick_id,0,FALSE);
            updatePoints($db,$user_user_id,$user_group_id,$user_season_year,$user_season_type,$user_week,false);
        }

        if($has_finished) {
            //reconcile to prevent future changes
            $sql = "UPDATE picks SET reconciled=1 WHERE pick_id='$user_pick_id'";
            if(mysqli_query($db,$sql)) {
                // echo "Point $user_pick_id Reconciled for User $user_user_id.\n";      
            } else {
                echo mysqli_error($db); 
            }  
        }

    }
} 
echo "Picks reconciled. ";

//--RECONCILE WIINERS
//get all non-reconciled PICKS   
// Get all of the users picks
$sql = "SELECT season_year, season_type, week, group_id FROM points WHERE reconciled IS NULL";
$result = mysqli_query($db, $sql) or die(mysqli_error($db));
while($row = mysqli_fetch_array($result)) {
    extract($row,EXTR_PREFIX_ALL,"this");
    if(allGamesFinished($this_season_year,$this_season_type,$this_week)) { //determine winner and reconcile
        // echo '<h2>Find Winners and Reconcile DB</h2>';
        //Sum all user points and scores and put them into an array with the user_name as the key
        $sql = "SELECT user_id, SUM(points) as sum_points, SUM(score) as sum_scores FROM picks WHERE season_year=".$this_season_year." AND season_type='".$this_season_type."' AND week=".$this_week." GROUP BY user_id ORDER BY sum_points DESC";
        $result = mysqli_query($db, $sql) or die(mysqli_error($db));
        while($row = mysqli_fetch_array($result)) {
            $user_name = getUserNameFromId($db,$row['user_id']);
            $total_points[$user_name] = $row['sum_points'];
            $total_scores[$user_name] = $row['sum_scores'];
        }
        // echo "<p>Results Sorted by Most Points:</p>";
        print_r($total_points);
        //check for a tie
        $most_points = max($total_points);
        $possible_winners=0;
        foreach($total_points as $p) {
            if($p==$most_points) {
                $possible_winners++;
            }    
        }
        if($possible_winners > 1) { //there is a tie
            // echo "<p>It's a tie!</p>\n";
            do { //remove the low scores
                if(current($total_points) != $most_points) {
                    array_pop($total_points);
                }
            } while(next($total_points));

            // echo "<br>Possible winners after culling:<br>\n";
            print_r($total_points);
  
            // echo "<br>No compare the Scores. Score Array:<br>\n";
            print_r($total_scores);

            $this_game_id = getMondayNightGame($this_season_year,$this_season_type,$this_week);
            $this_game_score = getGameScore($this_game_id);

            // echo "<br>Subtract Actual Game score of $this_game_score.<br>\n";
          
            foreach($total_scores as $u => $s) {
                $score_diffs[$u] = abs($s - $this_game_score);
            }
         
            // echo "<br>Unsorted Score Differentials:<br>\n";
            print_r($score_diffs);
            asort($score_diffs);
            // echo "<br>Sorted Score Differentials:<br>\n";
            print_r($score_diffs);

            $lowest_diff = min($score_diffs);
            // echo "<br>with a Lowest Diff of $lowest_diff<br>\n";

            $possible_winners=0;
            foreach($score_diffs as $d) {
                if($d==$lowest_diff) {
                    $possible_winners++;
                }    
            }
            if($possible_winners > 1) { //there is another tie
                $winner = array_rand($score_diffs); //flip a proverbial coin
                // echo $winner." has won the second tie breaker through a random selection process.<br>\n";
                
            } else {
                reset($score_diffs);
                $winner = key($score_diffs);
                // echo $winner." has won the tie breaker with a score differential of $lowest_diff.<br>\n";
            }

        } elseif(count($total_points) == 0 ) {
            //no one picked anything
            break;
        } else {
            reset($total_points);
            $winner = key($total_points);
            // echo $winner." has won on picks. ";
        }//End Tie Determination

        //Reconcile weekly point total and flag winner in DB
        reconcileWinners($db,$winner,$this_group_id,$this_season_year,$this_season_type,$this_week,$verbose=TRUE);
    } else {
        // echo "<h2>Games still in progress for $this_season_year $this_season_type Week $this_week (Group $this_group_id)</h2>";
    }
}
echo "Points reconciled. ";

?>