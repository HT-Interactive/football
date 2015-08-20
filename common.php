<?php
//common.php
//sets common stie variables

// Set Standard timezone to Eastern Daylight
date_default_timezone_set('America/New_York');

//extract anf passed requests
extract($_REQUEST,EXTR_PREFIX_ALL,"this");


//Set host-specifc
$SITE_HOST = $_SERVER['HTTP_HOST'];
$BASE_DIR = 'football';
$FORUM_DIR = 'forum';
$SITE_ROOT = '';
$FORUM_ROOT = $FORUM_DIR.'/';

//determine current tab/page

switch($_SERVER['PHP_SELF']) {
	case('/'.$BASE_DIR.'/index.php'): //
		if(isset($this_action)) {
			switch($this_action) {
				case('picks'):
					$SITE_PAGE = 'picks';
					break;
				case('standings'):
					$SITE_PAGE = 'standings';
					break;
				case('login'):
					$SITE_PAGE = 'login';
					break;
			}
		} else {
			$SITE_PAGE = 'picks';
		}
		break;
	case('/'.$BASE_DIR.'/'.$FORUM_ROOT.'index.php'):
		$SITE_PAGE = 'forum';
		$SITE_ROOT = '../';
		break;
	default:
		$SITE_PAGE = 'picks';
		break;
}

// Start Session; if first visit test for cookies
if(!isset($_COOKIE['cookies'])) {
  session_start();
  setcookie("cookies","yes");
} else {
  session_start();
}
// Test to see if user is logged in
if(isset($_COOKIE['user_id'])) { // A user is logged in so initialize variables
  $this_user_name = $_COOKIE['user_name']; 
  $this_user_id = $_COOKIE['user_id']; 
  $this_user_email = $_COOKIE['user_email'];
  $user_result = mysqli_query($db,"SELECT * FROM users WHERE user_id='$this_user_id'");
  $this_user = mysqli_fetch_array($user_result);
  extract($this_user,EXTR_PREFIX_ALL,"this");
  //set the $_SESSION['signed_in'] variable to TRUE
  $_SESSION['signed_in'] = true;
               
 //we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
  $_SESSION['user_id']    = $this_user_id;
  $_SESSION['user_email']  = $this_user_email;
  $_SESSION['user_name']  = $this_user_name;
  $_SESSION['user_level'] = $this_user_level; 

  /* if(isset($_COOKIE['id']) && session_id()==$_COOKIE['id']) { // Request originated from original client login
    $this_user_name = $_COOKIE['user_name']; 
    $user_result = mysql_query("SELECT * FROM users WHERE user_name='$this_user_name'",$db);
    $this_user = mysql_fetch_array($user_result);
    extract($this_user,EXTR_PREFIX_ALL,"this");
  } else { // Unauthorized request
    header("Location: index.php?error=unauthorized");
    exit;
  }*/
 
}
  
?>