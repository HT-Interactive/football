
<?php
// Connecting, selecting database
//$dbconn = pg_connect("host=173.254.28.69 dbname=evosecom_nfldb user=evosecom_nfl password=jmev0203")
//    or die('Could not connect: ' . pg_last_error());

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
$query = "SELECT * FROM game WHERE season_year='$this_year' AND season_type='$this_phase' AND week='$this_week'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
echo "<h>$this_year $this_phase Week $this_week</h>";
echo "<table>\n";
while ($games = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
    //$home_team = $games['home_team'];
    extract($games,EXTR_PREFIX_ALL,"this");
    echo "\t\t<td>$this_away_team at $this_home_team</td>\n";
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($db_nfl);
?>
