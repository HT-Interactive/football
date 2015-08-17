<?php // Navigation Script
?>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Football</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Picks <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="index.php">Current Week</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">2015 Regular Season</li>
                <li><a href="index.php?Show=yes&year=2015&phase=Preseason&week=1">Week 1</a></li>
                <li><a href="index.php?Show=yes&year=2015&phase=Preseason&week=2">Week 2</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Standings <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Current Standings</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">2015 Regular Season</li>
                <li><a href="#">Week 1</a></li>
                <li><a href="#">Week 2</a></li>
                <li><a href="#">Final</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
<?php

  if(isset($this_username)) {

    echo "<li class=\"active\"><a href=\"login.php?logout=yes\">Sign Out</a></li>";
    echo "<li><a href=\"#\">$this_displayname</a></li>";

  } else {
    echo "<li class=\"active\"><a href=\"./\">Sign In</a></li>";

  }

?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
