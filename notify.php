<?php //Email notification script

 //Search the users in the db for anyone with at leat one game that is not picked
 //Count games in week
 //Count games user has picked
 //compare
 //If picked < games || score IS NULL) = send email
 // Load Databases and Common functions
require("mysql.php");
include('common.php');
include("functions.php"); //Site Functions
//include('include/test_include.php');
//try to guess the current week, function in get_winners
guessCurrentWeek();

$num_games = getNumberOfGames($this_season_year,$this_season_type,$this_week);
$sql = "SELECT COUNT(pick_id) as num_picks, user_id FROM picks WHERE season_year='$this_season_year' AND season_type='$this_season_type' AND week='$this_week' GROUP BY user_id";
$result = mysqli_query($db, $sql) or die(mysqli_error($db));
while($user = mysqli_fetch_array($result)) {
    if($user['num_picks'] < $num_games) {
        echo 'User '.getUserNameFromId($db,$user['user_id']).' has only picked '.$user['num_picks'].' of '.$num_games.' send her an email at '.getUserEmailFromId($db,$user['user_id']);
        notifyUser(getUserEmailFromId($db,$user['user_id']),getUserNameFromId($db,$user['user_id']),$num_games,$user['num_picks']);
    }
}

?>
