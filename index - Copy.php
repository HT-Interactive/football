<?php // Football Selection Site

// file: index.php
// date created: 16-Aug-2015
// date updated: 16-Aug-2015
// by: Jeff Moreland <jeff@evose.com>

// Load Databases and Common functions
require("mysql.php");
include 'common.php';
include("get_winner.php"); //Site Functions
 
//try to guess the current week, function in get_winners
guessCurrentWeek();

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
    <?php if(isset($this_user_name)) { include("selector.php"); } ?>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->

			<?php

			//print_r($_SERVER);
      //echo $_SERVER['PHP_SELF'].' Site Page: '.$SITE_PAGE;

			if(isset($this_user_name)) {
        if(isset($this_action)) {
        	switch($this_action) {
        		case('picks'):
        			include 'current_schedule_grouped.php';
  						break;
  					case('standings'):
  						include 'current_standings.php';
  						break;
  					default:
  						include 'current_schedule_grouped.php';
  						break;
  				}
        } else {
        	include 'current_schedule_grouped.php';
        }
      } elseif(isset($_REQUEST['register'])) {
				include("register.inc");
			} else {
				include("signin.inc");  	
      }

      ?>

    </div> <!-- /container -->

    <?php if(isset($this_user_name)) { include("footer.php"); } ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
