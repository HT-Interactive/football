<?php
  
  require("mysql.php"); 

  $sql = "INSERT INTO users (user_id, user_name, user_password, user_display_name) VALUES (NULL,'$username',NULL,NULL)";
  if(mysqli_query($db, $sql)) {
    echo "<p>Welcome $username. This is your first login. Please enter a password and display name below.</p>";
    echo "<form action=\"enter_password.php\" method=\"post\" class=\"loginTable\">
    <label for=\"password\">Enter a Password:</label>
    <input type=\"password\" name=\"password\" size=\"12\" />
    <br />
    <label for=\"password2\">Repeat Password:</label>
    <input type=\"password\" name=\"password2\" size=\"12\" />
    <br />
    <label for=\"displayname\">Enter a Display Name:</label>
    <input type=\"text\" name=\"displayname\" size=\"12\" />
    <input type=\"hidden\" name=\"username\" value=\"$username\" />
    <input type=\"submit\" name=\"continue\" value=\"Continue::\" />
    </form>";
  } else {
    die(mysqli_error($db));
  }



?>