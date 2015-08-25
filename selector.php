<?php // Season Selector

$my_points = getWeeklyPoints($db,$this_user_id,$this_group_id,$this_season_year,$this_season_type,$this_week); // functions.php
$current_time = date("l h:iA T", time());

?>

<nav class="navbar navbar-inverse navbar-fixed-top weekSelector">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
      </button>
    </div><!--/.navbar-header -->
    <p class="weekSelector-text">Your Picks for <?php echo "$this_season_year $this_season_type Week $this_week"; ?></p>
    <div id="navbar2" class="navbar-collapse collapse weekSelector">
     <ul class="nav navbar-nav weekSelector">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo getGroupName($db,$this_group_id); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header">Groups</li>
            <?php
              $groups = getGroups($db,$this_user_id);
              foreach($groups as $group) {
                echo '<li><a href="'.$THIS_PAGE.'?group_id='.$group['group_id'].'&season_year='.$this_season_year.'&season_type='.$this_season_type.'&week='.$this_week.'">'.$group['group_name'].'</a></li>';
              }
            ?>      
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this_season_year; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="dropdown-header">Season Years</li>
            <?php
              $season_years = getSeasonYears();
              foreach($season_years as $display_year) {
                echo "<li><a href=\"".$THIS_PAGE."?group_id=$this_group_id&season_year=$display_year&season_type=$this_season_type&week=$this_week\">$display_year</a></li>";
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
                echo "<li><a href=\"".$THIS_PAGE."?group_id=$this_group_id&season_type=$display_type&season_year=$this_season_year&week=$this_week\">$display_type</a></li>";
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
                echo "<li><a href=\"".$THIS_PAGE."?group_id=$this_group_id&season_type=$this_season_type&season_year=$this_season_year&week=$display_week\">$display_week</a></li>";
              }
            ?>  
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div><!--/container -->
</nav>

