<?php // Navigation Script
?>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" id="top" role="banner">
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
          <?php

  					if(isset($this_user_name)) {
            	echo '<li '.(($SITE_PAGE == 'picks') ? 'class="active"':'').'><a href="'.$SITE_ROOT.'picks.php">My Picks</a></li>';
            	echo '<li '.(($SITE_PAGE == 'standings') ? 'class="active"':'').'><a href="'.$SITE_ROOT.'standings.php">Standings</a></li>';
            	echo '<li '.(($SITE_PAGE == 'forum') ? 'class="active"':'').'><a href="'.$SITE_ROOT.'forum.php">Forum</a></li>';
            	echo '<li><a href="#"><img width="20" alt="Former Clemson Player Stats" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Clemson_University_Tiger_Paw_logo.svg/32px-Clemson_University_Tiger_Paw_logo.svg.png"/> Stats</a></li>';
            }
          ?>
            <?php if($SITE_PAGE == 'login') { echo '<li class="active"><a href="'.$SITE_ROOT.'index.php?action=login">Sign In</a></li>'; } ?>
          </ul>                

<?php

  if(isset($this_user_name)) {

    echo '<form class="navbar-form navbar-right" action="'.$SITE_ROOT.'login.php">';
    echo "<button type=\"submit\" name=\"logout\" value=\"yes\" class=\"btn btn-default\">Sign out</button>";
    echo "</form>";
    echo "<p class=\"navbar-text navbar-right\">Signed in as <a href=\"#\" class=\"navbar-link\">$this_user_name</a></p>";

  } else {

    //echo "<form class=\"navbar-form navbar-right\" role=\"signin\" action=\"index.php\">";
    //echo "<button type=\"submit\" name=\"login\" value=\"yes\" class=\"btn btn-default\">Sign in</button>";
    //echo "</form>";

  }
  //echo '<p class="navbar-text navbar-right">SID '.session_id().'</p>';

?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
