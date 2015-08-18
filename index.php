<?php // Football Selection Site

// file: index.php
// date created: 16-Aug-2015
// date updated: 16-Aug-2015
// by: Jeff Moreland <jeff@evose.com>

// Load Databases
  require("mysql.php");

// Set Standard timezone to Eastern Daylight
  date_default_timezone_set('America/New_York');

// Start Session; if first visit test for cookies
  if(!isset($_COOKIE['cookies'])) {
    session_start();
    setcookie("cookies","yes");
  } else {
    session_start();
  }

// Test to see if user is logged in
  if(isset($_COOKIE['userid'])) { // A user is logged in so initialize variables
      $this_username = $_COOKIE['username']; 
      $this_userid = $_COOKIE['userid']; 
      $this_displayname = $_COOKIE['displayname'];
      $user_result = mysqli_query($db,"SELECT * FROM users WHERE user_name='$this_username'");
      $this_user = mysqli_fetch_array($user_result);
      extract($this_user,EXTR_PREFIX_ALL,"this");

    /* if(isset($_COOKIE['id']) && session_id()==$_COOKIE['id']) { // Request originated from original client login
      $this_username = $_COOKIE['username']; 
      $user_result = mysql_query("SELECT * FROM users WHERE username='$this_username'",$db);
      $this_user = mysql_fetch_array($user_result);
      extract($this_user,EXTR_PREFIX_ALL,"this");
    } else { // Unauthorized request
      header("Location: index.php?error=unauthorized");
      exit;
    }*/

  }

// Load Winner Script
  include("get_winner.php");

// Guess Current Week
  $query = "SELECT * FROM game WHERE finished=FALSE ORDER BY start_time ASC";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $next_game = pg_fetch_array($result, null, PGSQL_ASSOC);

  $current_season_year = $next_game['season_year'];
  $current_season_type = $next_game['season_type'];
  $current_week = $next_game['week'];

// Get any passed variables and extract them
  extract($_REQUEST,EXTR_PREFIX_ALL,"this");
  
  if(!isset($this_season_year)) { // user did not pass year so assume current
    $this_season_year = $current_season_year;
  }  
  if(!isset($this_season_type)) { // user did specify so assume current
    $this_season_type = $current_season_type;
  }
  if(!isset($this_week)) { // user did specify so assume current
    $this_week = $current_week;
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Jeff Moreland <jeff@evose.com>">
    <link rel="icon" href="../../favicon.ico">

    <title>Football Selection Site</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- <link href="css/navbar-fixed-top.css" rel="stylesheet"> -->
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="css/football.css" rel="stylesheet">

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

   <script src="js/football.js"></script>

  </head>

  <body>

    <?php include("navigator.php"); ?>
    <?php if(isset($this_username)) { include("selector.php"); } ?>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->


      <?php

        if(isset($this_username)) {

          if(isset($this_show) && $this_show == "standings") {

            include("current_standings.php");

          } else {

            //include("current_schedule.php");
            include("current_schedule_grouped.php");
          }

        } elseif(isset($_REQUEST['register'])) {

          //$username = $_REQUEST['register'];
          include("register.inc");

        } else {

          include("signin.inc");
        }

      ?>

    </div> <!-- /container -->

    <?php if(isset($this_username)) { include("footer.php"); } ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
