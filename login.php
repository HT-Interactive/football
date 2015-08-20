<?php // Evose Login Script

// Created By: Jeff Moreland <jeff@evose.com>
// Date: 18 Jan 2003 (Updated 08 Dec 2005)
// Copyright 2002, Evose <http://www.evose.com>
    
// print_r($_SERVER);
// die();
// Evose Login Script--

// Get DB connect info
  require("mysql.php");
  include 'common.php';

function resetPasswordLink($user,$sid) {
	global $SITE_HOST, $BASE_DIR;
	
	$to  = $user;
  $subject = 'PHP HTML Test';

  // message
  $message = '
  <html>
  <head>
   <title>'.$subject.'</title>
  </head>
  <body>
   <p>We received a request to reset your password.
      If you did not make this request, you can ignore this email.
      If you did make this request, please click this
   	 <a href="http://'.$SITE_HOST.'/'.$BASE_DIR.'/login.php?forgot=password&user_email='.$user.'&code='.$sid.'">link to reset your password.</a>
  </body>
  </html>
  ';

  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  // Additional headers
  //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
  //$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
  //$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
  //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

  // Mail it
  if(mail($to, $subject, $message, $headers)) {
  	echo 'Mail sent.';
  } else {
  	echo 'Mail Error';
  }
}

  if(!isset($_COOKIE['cookies'])) {
    $error=urlencode("Cookies not enabled.");
    $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
    header("Location: http://".$goTo);
    exit;
  } 


  if(isset($_REQUEST['register'])) {
      $user_email=$_REQUEST['user_email'];
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?register=".$user_email;
      header("Location: http://".$goTo);
      exit;

  } elseif(isset($_REQUEST['login'])) {

  // Get Form Variables
    $user_email=$_REQUEST['user_email'];
    $password=$_REQUEST['password'];
    $time = time()+3600;

 // Select proper user from table
    
    $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_email='$user_email'");

    if($this_user = mysqli_fetch_array($user_result)) { // User name exists so continue
      extract($this_user,EXTR_PREFIX_ALL,"this");
    // Check encrypted password by seeding crypt with original pass (ref 1)
      
    //if (hash_equals($this_user_password, crypt($password, $this_user_password))) { // Password ok so continue
      if(sha1($password) == $this_user_pass) {

      // Set session id to unique value to prevent piggy-backing
        $id = uniqid("");
        session_id($id);

     // Continue session
      	session_start();
              

      // Save authenticated name-pass in cookie
        // header("Set-Cookie: id=$id; path=/;");
        setcookie("id",$id,time()+60*60*24*365);
        // header("Set-Cookie: user_name=$user_name; path=/;");
        setcookie("user_name",$this_user_name,time()+60*60*24*365);
        setcookie("user_id",$this_user_id,time()+60*60*24*365);
        setcookie("user_email",$this_user_email,time()+60*60*24*365);
        setcookie("user_level",$this_user_level,time()+60*60*24*365);

      // Point browser to user page (ref 1)
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
        header("Location: http://".$goTo);
        exit;

      } else { // Bad password

        $error=urlencode("Passwords do not match.");
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
        header("Location: http://".$goTo);
        exit;

      }

    } else { // User name not in DB

      $error=urlencode("Invaliduser_name");
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;

    }

  } elseif(isset($_REQUEST['logout'])) { // Log user out

  // Delete Cookie
    setcookie("id","",-1);
    setcookie("user_name","",-1);
    setcookie("user_id","",-1);
    setcookie("user_email","",-1);
    setcookie("user_level","",-1);

  // Close Session
    session_start();
    session_unset();
    session_destroy();

  // Rediredt to referring page
    $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?action=login";
    header("Location: http://".$goTo);
    exit;

  } elseif(isset($_REQUEST['forgot'])) {
    
    if(isset($_REQUEST['user_email'])) {    
    	if(isset($_REQUEST['code'])) {
    	//compare code to 
    		if($_REQUEST['code'] == session_id()) {
    			if(isset($_REQUEST['password']) && isset($_REQUEST['password2'])) {
    				if($_REQUEST['password'] == $_REQUEST['password2']) {
    				//update user in db
    					$user_email = $_REQUEST['user_email'];
    					$crypt_password = sha1($_REQUEST['password']);
    				
    					$sql = "UPDATE users SET user_pass='$crypt_password' WHERE user_email='$user_email'";
      
              if(mysqli_query($db,$sql)) {
          			$message=urlencode("Your password has been changed. Please sign in now.");
          			$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&user_email='.urlencode($user_email).'&message='.$message;
         				header("Location: http://".$goTo);
          			exit;
              } else {
                echo mysqli_error($db); 
              } 
    				}//--End password match
    			
    			} else {
          	//show new form with double password entry
          	include 'header.php';
          	include 'password.inc';
          	include 'footer.php';
          	//$meassage=urlencode("Enter a new password");
          	//$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=password_reset&user_email='.$user_email.'&code='.$this_code.'&message='.$message;
          	//header("Location: http://".$goTo);
          	exit;
    			}//--End passwords benn entered
    		} else {
      	  //Redirect to login with link expired
          $error=urlencode("Link expired. Please try again.");
          $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&forgot=password&message='.$error;
          header("Location: http://".$goTo);
          exit;
      	}//--end link expiration
      } else {
        //user supplied password so send email
    		// Continue session
    		//session_start();
    		resetPasswordLink($_REQUEST['user_email'],session_id());
    		$message=urlencode('An email has been sent to with instructions on how to change your password.');
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&forgot=password&message='.$message;
        header("Location: http://".$goTo);
        exit;
      }//--End no code yet
    } else {
      //Redirect to login with no email notification
      $error=urlencode("Please enter your email address and try again.");
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&forgot=password&message='.$message;
      header("Location: http://".$goTo);
      exit;
    }//--End If User Email
  
  } else { // Request not from login form so...

    die();

  }

// --End Login

// **References**
// 1. www.php.net

?>