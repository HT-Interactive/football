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

include 'header.php';
guessCurrentWeek();
			if(isset($this_user_name)) {
        if(isset($this_action)) {
        	switch($this_action) {
        		case('picks'):
        			include 'current_schedule_grouped.php';
  						break;
  					case('standings'):
  						include 'current_standings.php';
  						break;
  					case('reset_password'):
  						include 'password.inc';
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

include 'footer.php';

?>
