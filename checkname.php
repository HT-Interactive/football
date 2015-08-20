<?php

  // Get DB connect info
  require("mysql.php");

  $user_name=$_REQUEST['n'];

  // Select proper user from table
  $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_email='$user_email'");

  if(mysqli_fetch_array($user_result)) {
    echo "1";
  } else {
    echo "0";
  }
?> 
