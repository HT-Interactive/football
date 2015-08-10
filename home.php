<?php

  if(isset($this_username)) {
    echo "<div class=\"actionbar\">
    <span class=\"sampleHeader\">Welcome $this_username. <a href=\"login.php?logout=yes\">[Logout]</a></span>
    </div>";
    switch ($this_UsertypeID) {
      case 0: // Guest
        print_r($_COOKIE);
      break;
      case 1: // Client
        print_r($_COOKIE);
      break;
      case 2: // Technician
        include("technician.php");
      break;
      case 3: // Administration
        include("technician.php");
      break;
}
    

  } else {
    echo "<div class=\"actionbar\">
    <span class=\"sampleHeader\">Welcome to Sample and Testing Request System.</span>
    </div>";

    echo "<div class=\"displayer\">
    <div class=\"displayerContent\">";

    if(isset($_REQUEST['register'])) {
      $this_username = $_REQUEST['register'];

      echo "Welcome $this_username. This is your first login. Please enter a password below.";
      echo "<form action=\"enter_password.php\" method=\"post\" class=\"loginTable\">
      <label for=\"password\">Enter a Password:</label>
      <input type=\"password\" name=\"password\" size=\"12\" />
      <br />
      <br />
      <label for=\"password2\">Repeat Password:</label>
      <input type=\"password\" name=\"password2\" size=\"12\" />
      <input type=\"hidden\" name=\"username\" value=\"$this_username\" />
      <input type=\"submit\" name=\"continue\" value=\"Continue::\" />
      </form>";

    } else {
      
      echo "<span>Login or continue as a Guest.</span>
      <form action=\"login.php\" method=\"post\" class=\"loginTable\">
      <label for=\"username\">UserName:</label>
      <input type=\"text\" name=\"username\" size=\"12\" />
      <br />
      <br />
      <label for=\"password\">Password:</label>
      <input type=\"password\" name=\"password\" size=\"12\" />
      <input type=\"submit\" name=\"login\" value=\"Login::\" class=\"login\" />
      </form>";
    }

  }

  echo "</div>
  </div>";

?>
