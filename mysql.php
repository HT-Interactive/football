<?php

// Connect to MySQL DB
  $db=mysqli_connect("localhost", "evosecom_nfl", "jmev0203","evosecom_wff");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Connect to PostgreSQL DB
  if($_SERVER['HTTP_HOST']=="evose.com") { //on evose server
    $db_nfl = pg_connect("host=localhost dbname=evosecom_nfldb user=evosecom_nfl password=jmev0203")
              or die('Could not connect: ' . pg_last_error()); //something is wrong
  } else { //on local development
    $db_nfl = pg_connect("host=173.254.28.69 dbname=evosecom_nfldb user=evosecom_nfl password=jmev0203")
              or die('Could not connect: ' . pg_last_error()); //something is wrong
  }

?>