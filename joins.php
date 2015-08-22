<?php

include("mysql.php");
include("functions.php");
 
function getWeeklyPointTotal($db,$user_id,$group_id,$season_year,$season_type,$week) {  
//SQL INNER JOIN example to return all user picks for the seasons defined by g_seasons
    $sql = "SELECT SUM(points)\n"
         . "FROM picks\n"
         . "INNER JOIN groups\n"
         . "ON picks.group_id=groups.group_id\n"
         . "INNER JOIN g_seasons\n"
         . "ON groups.group_id=g_seasons.group_id WHERE user_id='$user_id' AND picks.group_id='$group_id' AND picks.season_year='$season_year' AND picks.season_type='$season_type' AND picks.week='$week'\n"
         . "GROUP BY user_id";
    $result = mysqli_query($db, $sql) or die(mysqli_error($db));
    while($total_points = mysqli_fetch_array($result)) {
        echo $total_points[0];
    }
    
}
/*
SELECT picks.season_year, picks.season_type, picks.week, SUM(points) as sum_points, picks.user_id as winner
FROM picks
INNER JOIN groups
ON picks.group_id=groups.group_id
INNER JOIN g_seasons
ON groups.group_id=g_seasons.group_id 
GROUP BY picks.season_year, picks.season_type, picks.week
*/
/*
SELECT picks.user_id, picks.season_year, picks.season_type, picks.week, SUM(points) as sum_points, SUM(picks.score) as sum_score
FROM picks
INNER JOIN groups
ON picks.group_id=groups.group_id
INNER JOIN g_seasons
ON groups.group_id=g_seasons.group_id 
GROUP BY user_id, picks.season_year, picks.season_type, picks.week
*/
echo getWeeklyPointTotal($db,1,1,2015,'Preseason',1);

$sql = "SELECT picks.user_id, picks.season_year, picks.season_type, picks.week, SUM(points) as sum_points, SUM(picks.score) as sum_score\n"
     . "FROM picks\n"
     . "INNER JOIN groups\n"
     . "ON picks.group_id=groups.group_id\n"
     . "INNER JOIN g_seasons\n"
     . "ON groups.group_id=g_seasons.group_id \n"
     . "GROUP BY user_id, picks.season_year, picks.season_type, picks.week";
echo $sql;
$week_result = mysqli_query($db,$sql) or die(mysqli_error($db));
while($week = mysqli_fetch_array($week_result,MYSQLI_ASSOC)) {
    echo "<p>";
    print_r($week);
    echo "</p>";
}

?>