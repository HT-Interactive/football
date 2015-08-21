<?php // Season Selector

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
  $all_results = pg_fetch_all($result);
  foreach($all_results as $week) {
    $display_weeks[] = $week['week'];
  }
  return $display_weeks;

}

$my_points = getWeeklyPoints($db,$this_userid,$this_season_year,$this_season_type,$this_week); // functions.php
$current_time = date("l h:iA T", time());

?>

<nav class="navbar navbar-inverse navbar-fixed-top weekSelector">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      
    </div>
    <p class="weekSelector-text">Your Picks for</p>
    <div id="navbar2" class="navbar-collapse collapse weekSelector">
      <ul class="nav navbar-nav weekSelector">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this_season_year; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header">Season Years</li>
            <?php
              $season_years = getSeasonYears();
              foreach($season_years as $display_year) {
                echo "<li><a href=\"index.php?season_year=$display_year&season_type=$this_season_type&week=$this_week\">$display_year</a></li>";
              }
            ?>      
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this_season_type; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header">Season Types</li>
            <?php
              $season_types = getSeasonTypes();
              foreach($season_types as $display_type) {
                echo "<li><a href=\"index.php?season_type=$display_type&season_year=$this_season_year&week=$this_week\">$display_type</a></li>";
              }
            ?>  
          </ul>
        </li>
       <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Week <?php echo $this_week; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header">Season Weeks</li>
            <?php
              $season_weeks = getWeeks($this_season_year,$this_season_type);
              foreach($season_weeks as $display_week) {
                echo "<li><a href=\"index.php?season_type=$this_season_type&season_year=$this_season_year&week=$display_week\">$display_week</a></li>";
              }
            ?>  
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

