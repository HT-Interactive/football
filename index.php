<?php // Football Selection Site

// file: index.php
// date created: 16-Aug-2015
// date updated: 16-Aug-2015
// by: Jeff Moreland <jeff@evose.com>

// Load Databases and Common functions
require("mysql.php");
include('common.php');
include("functions.php"); //Site Functions
//include('include/test_include.php');

//try to guess the current week, function in get_winners
guessCurrentWeek();



//guessCurrentWeek();

if(isset($this_user_name)) {
    if(isset($this_action)) {
        include("header.php");
        switch($this_action) {
  		    case('reset_password'):
  			    include("password.inc");
  			    break;
  	    }
    } else {
      //redirect to picks  
      //$error=urlencode("Passwords do not match.");
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/picks.php";
      header("Location: http://".$goTo);
      exit;    
    }
} elseif(isset($_REQUEST['register'])) {
    include("header.php");
	include("register.inc");
} else {
    include("header.php");
	include("signin.inc");
}

include("footer.php");

?>
