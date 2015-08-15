<?php

  // Get DB connect info
  require("mysql.php");

  $username=$_REQUEST['n'];

  // Select proper user from table
  $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_name='$username'");

  if(mysqli_fetch_array($user_result)) {
    echo "1";
  } else {
    echo "0";
  }
?> 
