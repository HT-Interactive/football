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
          <a class="navbar-brand" href="index.php">Football</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li <?php if(isset($this_show) && $this_show != 'standings') { echo "class=\"active\""; } ?>><a href="index.php">My Picks</a></li>
            <li <?php if(isset($this_show) && $this_show == 'standings') { echo "class=\"active\""; } ?>><a href="index.php?show=standings">Standings</a></li>
          </ul>                

<?php

  if(isset($this_username)) {

    echo "<form class=\"navbar-form navbar-right\" action=\"login.php\">";
    echo "<button type=\"submit\" name=\"logout\" value=\"yes\" class=\"btn btn-default\">Sign out</button>";
    echo "</form>";
    echo "<p class=\"navbar-text navbar-right\">Signed in as <a href=\"#\" class=\"navbar-link\">$this_displayname</a></p>";


  } else {

    echo "<form class=\"navbar-form navbar-right\" role=\"signin\" action=\"index.php\">";
    echo "<button type=\"submit\" name=\"login\" value=\"yes\" class=\"btn btn-default\">Sign in</button>";
    echo "</form>";

  }

?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
