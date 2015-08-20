<?php // Winning Deterministic Algorithm

//DB Reconciliation Functions
//***---------------------------

function guessCurrentWeek() {
  global $current_season_year, $current_season_type, $current_week, $this_season_year, $this_season_type, $this_week, $this_group_id, $this_user_id, $this_default_group;
// Guess Current Week
  $query = "SELECT * FROM game WHERE finished=FALSE ORDER BY start_time ASC";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $next_game = pg_fetch_array($result, null, PGSQL_ASSOC);

  $current_season_year = $next_game['season_year'];
  $current_season_type = $next_game['season_type'];
  $current_week = $next_game['week'];

// Get any passed variables and extract them
  extract($_REQUEST,EXTR_PREFIX_ALL,"this");
  
  //echo 'This group: '.$this_group;
  
  if(!isset($this_season_year)) { // user did not pass year so assume current
    $this_season_year = $current_season_year;
  }  
  if(!isset($this_season_type)) { // user did specify so assume current
    $this_season_type = $current_season_type;
  }
  if(!isset($this_week)) { // user did specify so assume current
    $this_week = $current_week;
  }
 
  if(!isset($this_group_id)) {
  	if(isset($this_user_id)) {
  		$this_group_id = $this_default_group;
  	} else {
  		$this_group_id = 0;
  	}
  }

}

function addWin($db,$u,$s,$t,$w,$verbose) {

  $sql = "UPDATE points SET winner='1' WHERE user_id='$u' AND season_year='$s' AND season_type='$t' AND week='$week'";
      
  if(mysqli_query($db,$sql)) {
    if($verbose) { echo "Win Added for User $u for $s $t Week $w<br>\n";}
  } else {
    echo mysqli_error($db); 
  } 

}

function addPoint($db,$id,$p,$verbose) {

  $sql = "UPDATE picks SET points='$p' WHERE pick_id='$id'";
      
  if(mysqli_query($db,$sql)) {
    if($verbose) { echo "$p Point Added to Pick $id<br>\n";}
  } else {
    echo mysqli_error($db); 
  } 

}

function updatePoints($db,$user_id,$group_id,$season_year,$season_type,$week,$verbose) {
  // check picks table for games that match season/week and sum them
  //$sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$user_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'"; 
  //$result = mysqli_query($db,$sql);
  //$row = mysqli_fetch_assoc($result); 
  //$sum = $row['points_sum'];
  //echo $sum;
  $sum = getWeeklyPoints($db,$user_id,$group_id,$season_year,$season_type,$week);

  $sql = "SELECT * FROM points WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'";
  $result = mysqli_query($db,$sql);
  if(mysqli_num_rows($result) > 0) { // user has points for week in db so update
    // update points total in points table for user
    $sql = "UPDATE points SET points='$sum' WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'";
    //$result = mysqli_query($db,$sql);
    if(mysqli_query($db,$sql)) {
      if($verbose) { echo "Point total for user_id $user_id in group_id $group_id for $season_year $season_type Week $week updated to $sum.<br>\n"; }
    } else {
      echo mysqli_error($db); 
    }  
  } else { //no point total has been added so insert

    $sql = "INSERT INTO points (points_id, user_id, group_id, season_year, season_type, week, points,wins) VALUES (NULL,'$user_id','$group_id','$season_year','$season_type','$week',$sum,NULL)";
    if(mysqli_query($db, $sql)) {
      if($verbose) { echo "Point total for user_id $user_id in group_id $group_id for $season_year $season_type Week $week inserted as $sum.<br>\n"; }
    } else {
      echo mysqli_error($db);
    }

  }    
}

function updateWins($db,$season_year,$season_type,$week) {
  // check picks table for games that match season/week and sum them
  //$sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$user_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'"; 
  //$result = mysqli_query($db,$sql);
  //$row = mysqli_fetch_assoc($result); 
  //$sum = $row['points_sum'];
  //echo $sum;
  //$sum = getWeeklyPoints($db,$user_id,$season_year,$season_type,$week);

  $sql = "SELECT SUM(points) AS points_sum FROM picks WHERE season_year='$season_year' AND season_type='$season_type' AND week='$week' ORDER BY points_sum";
  $result = mysqli_query($db,$sql);
  if(mysqli_num_rows($result) > 0) { // A winner was found
    $user_id = mysqli_insert_id($db);
    // update points total in points table for user
    $sql = "UPDATE points SET winner=1 WHERE user_id='$user_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'";
    //$result = mysqli_query($db,$sql);
    if(mysqli_query($db,$sql)) {
      //echo "Weekly winner updated";
    } else {
      echo mysqli_error($db); 
    }  
  }  

}


function getGameWinner($game_id) {

  // Performing SQL query
  $query = "SELECT * FROM game WHERE gsis_id='$game_id'";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $game = pg_fetch_array($result, null, PGSQL_ASSOC);
  if($game['home_score']>$game['away_score']) {
    return $game['home_team'];
  } elseif($game['home_score']==$game['away_score']) {
    return "tied";
  } else {
    return $game['away_team'];
  }

}

function getGameScore($game_id) {

  // Performing SQL query
  $query = "SELECT * FROM game WHERE gsis_id='$game_id'";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $game = pg_fetch_array($result, null, PGSQL_ASSOC);
  return $game['home_score'] + $game['away_score'];

}

function getNumberOfGames($season_year,$season_type,$week) {

  $query = "SELECT COUNT(gsis_id) as num_games FROM game WHERE season_year='$season_year' AND season_type='$season_type' AND week='$week'";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $game = pg_fetch_array($result, null, PGSQL_ASSOC);
  return $game['num_games'];

}

function getWeeklyPoints($db,$user_id,$group_id,$season_year,$season_type,$week) {
  //updatePoints($db,$user_id,$season_year,$season_type,$week);
  $sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week'"; 
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($result);
  if($row['points_sum'] > 0) {
    return $row['points_sum'];
  } else {
    return 0;
  }
}

function getWeeklyScore($db,$user_id,$group_id,$season_year,$season_type,$week) {
  //updatePoints($db,$user_id,$season_year,$season_type,$week);
  $sql = "SELECT score,game_id FROM picks WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week' AND score IS NOT NULL"; 
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($result);
  if($row['score'] >= 0) {
    return [$row['game_id'],$row['score']];
  } else {
    return [$row['game_id'],0];
  }
}

function getWeeklyWinner($db,$group_id,$season_year,$season_type,$week) { // pull out the user_id of the person with the highest point toal for the week for the group
  $sql = "SELECT * FROM points WHERE group_id='$group_id' AND season_year='$season_year' AND season_type='$season_type' AND week='$week' ORDER BY SUM(points)";
  $result = mysqli_query($db,$sql);
  if(mysqli_num_rows($result) == 1) { // A winner was found
    $row = mysqli_fetch_assoc($result);
    return $row['user_id'];
    
    //return mysqli_insert_id($db);
  } elseif(mysqli_num_rows($result) > 1) {
    return "tie";
  } else {
    return "no winner/error";
  }
}

function getUserWins($db,$user_id,$group_id,$season_year) { // pull out the user_id of the person with the highest point toal for the week
  $sql = "SELECT * FROM points WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year' ORDER BY SUM(points)";
  $result = mysqli_query($db,$sql);
  return mysqli_num_rows($result);
  /*
  if(mysqli_num_rows($result) == 1) { // A winner was found
    $row = mysqli_fetch_assoc($result);
    return $row['user_id'];
    
    //return mysqli_insert_id($db);
  } elseif(mysqli_num_rows($result) > 1) {
    return "tie";
  } else {
    return "no winner/error";
  }
  */
}

function getSeasonPoints($db,$user_id,$group_id,$season_year) {
  $sql = "SELECT SUM(points) AS points_sum FROM picks WHERE user_id='$user_id' AND group_id='$group_id' AND season_year='$season_year'"; 
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($result);
  if($row['points_sum'] > 0) {
    return $row['points_sum'];
  } else {
    return 0;
  }
}

function getUsers($db,$group_id = 'all') { // get all users from mysql db

	if($group_id == 'all') {
		$result = mysqli_query($db, "SELECT * FROM users");
	} else {
		$result = mysqli_query($db, "SELECT * FROM users INNER JOIN g_members ON users.user_id=g_members.user_id WHERE g_members.group_id='$group_id'");
	}
  while($user = mysqli_fetch_array($result)) {
    $users[] = $user;
  }
  return $users;

}

function getGroups($db,$user_id = 'all') {

	if($user_id == 'all') {
  	$result = mysqli_query($db, "SELECT * FROM groups");
  } else {
  	$result = mysqli_query($db, "SELECT * FROM groups WHERE user_id='$user_id'");
  }
  while($group = mysqli_fetch_array($result)) {
    $groups[] = $group;
  }
	mysqli_free_result($result);
  return $groups;
}

function getGroupName($db,$group_id) {
  $result = mysqli_query($db, "SELECT group_name FROM groups WHERE group_id='$group_id'");
  return mysqli_fetch_array($result)['group_name'];
}

function getSeasonYears() {

  $query = "SELECT DISTINCT season_year FROM game ORDER BY season_year ASC";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $all_results = pg_fetch_all($result);
  foreach($all_results as $year) {
    $display_years[] = $year['season_year'];
  }
  return $display_years;

}

function getSeasonTypes() {

  $query = "SELECT DISTINCT season_type FROM game";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $all_results = pg_fetch_all($result);
  foreach($all_results as $type) {
    $display_types[] = $type['season_type'];
  }
  return $display_types;

}

function getWeeks($season_year,$season_type) {

  $query = "SELECT DISTINCT week FROM game WHERE season_type='$season_type' AND season_year='$season_year' ORDER BY week ASC";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  //echo $result;
  //echo "$season_year $season_type";
  $all_results = pg_fetch_all($result);
  //print_r($all_results);
  if($all_results) {
    foreach($all_results as $week) {
      $display_weeks[] = $week['week'];
    }
  } else {
    $display_weeks = [];
  }
  return $display_weeks;

}

function calculateWinnings($users,$weeks,$wins,$anty) {
  //starting balance = weeks*anty
  //total pot = users * weeks * anty
  //winnings = (wins * users * anty) - (total_weeks*anty)
  return ($wins * count($users) * $anty) - ($weeks * $anty);
}

?>