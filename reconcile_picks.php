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
$pick_result = mysqli_query($db, $sql);
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
            addPoint($db,$user_pick_id,1,TRUE);
        } else { 
            //($winning_team != $user_winner || $winning_team == 'tied') 
            addPoint($db,$user_pick_id,0,TRUE);
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
echo "Database Picks reconciled.";

?>