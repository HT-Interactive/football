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
    <link href="css/navbar-fixed-top.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/football.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

   <script src="js/football.js"></script>

  </head>

  <body>

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

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->

<?php

  if(isset($this_username)) {

    include("current_schedule.php");

  } elseif(isset($_REQUEST['register'])) {

    //$username = $_REQUEST['register'];
    include("register.inc");

  } else {

    include("signin.inc");
  }

?>


    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
