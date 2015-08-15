<?php

// Connect to DB
  $db=mysqli_connect("localhost", "evosecom_nfl", "jmev0203","evosecom_wff");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $db_nfl = pg_connect("host=173.254.28.69 dbname=evosecom_nfldb user=evosecom_nfl password=jmev0203")
    or die('Could not connect: ' . pg_last_error());
?>