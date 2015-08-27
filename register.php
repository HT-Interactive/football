<?php // Register Script

// file: register.php
// date: 16-Aug-2015
// updated: 16-Aug-2015
// by: Jeff Moreland <jeff@evose.com>  

  if(isset($_REQUEST['register'])) { // check that the form was submitted corrrectly

    // Get Form Variables
    $user_email=$_REQUEST['user_email'];
    $password=$_REQUEST['password'];
    $password2=$_REQUEST['password2'];
    $user_name=$_REQUEST['user_name'];
    $group = $_REQUEST['group'];
    $time = time()+3600;
  
    // check that passwords match, this should be added to form itself
    if($password2==$password) {

    } else {
      $error="PasswordMismatch";
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;
    }

    // encrypt password
    $crypt_password = sha1($password);
    /*
    if($_SERVER['HTTP_HOST']=="evose.com") { //on evose server so use 5.4 code

      $crypt_password = crypt($password);

    } else { // use 5.6 code

      $crypt_password = password_hash($password, PASSWORD_DEFAULT); //encrypt password

    }
    */
    // Get DB connect info
    require("mysql.php");

    // make user user isn't already registered with this email address
    $sql = "SELECT * FROM users WHERE user_email='".mysql_real_escape_string($user_email)."'";
    $result = mysqli_query($db, $sql);
    if(mysqli_num_rows($result) > 0) { // user already registered so redirect back to login with message
      $message = urlencode('Email address '.$user_email.' is already registered. Please sign in below.');
    	$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&user_email='.$user_email.'&message='.$message;
      header("Location: http://".$goTo);
      exit;

    } else { 
    
        // make user user name isn't already registered 
        $sql = "SELECT * FROM users WHERE user_name='".$user_name."'";
    		$result = mysqli_query($db, $sql);
    		if(mysqli_num_rows($result) > 0) { // user already registered so redirect back to login with message
    			$user = mysqli_fetch_array($result);
      		    $message = urlencode('User name '.$user_name.' is already registered to '.$user['user_email'].' Please sign in below.');
    			$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php?action=login&user_email='.$user['user_email'].'&message='.$message;
      		header("Location: http://".$goTo);
      		exit;
      	} else {
          // add user to db
          $this_user_level = 0;
          //$user_email = mysql_real_escape_string($user_email);
          //$user_name = mysql_real_escape_string($user_name);
          $sql = "INSERT INTO users (user_id, user_email, user_name, user_pass, user_date, user_level,default_group) VALUES (NULL,'$user_email','$user_name','$crypt_password',NOW(),$this_user_level,$group)";
          //echo $sql;
          //exit;
          if(mysqli_query($db, $sql)) { //success, login and redirect to home

          // get the new user id
          	$this_user_id = mysqli_insert_id($db);
					//add user to group
          	$sql = "INSERT INTO g_members (g_member_id, group_id, user_id) VALUES (NULL,'$group','$this_user_id')";
			mysqli_query($db, $sql);
						
          // Set session id to unique value to prevent piggy-backing
          	$id = uniqid("");
          	session_id($id);

          // Continue session
          	session_start();
            
          //set the $_SESSION['signed_in'] variable to TRUE
          	$_SESSION['signed_in'] = true;
                         
           //we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
            $_SESSION['user_id']    = $this_user_id;
            $_SESSION['user_name']  = $user_name;
            $_SESSION['user_email']  = $user_email;
            $_SESSION['user_level'] = $this_user_level;   
                         
          // Save authenticated name-pass in cookie
            setcookie("user_name",$user_name,time()+60*60*24*365);
            setcookie("user_id",$this_user_id,time()+60*60*24*365);
            setcookie("user_email",$user_email,time()+60*60*24*365);
            setcookie("user_level",$this_user_level,time()+60*60*24*365);

          // Point browser to user page (ref 1)
            $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
            header("Location: http://".$goTo);
            exit;

        	} else { // go home with error

            // Point browser to user page (ref 1)
            $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".mysqli_error($db);
            header("Location: http://".$goTo);
            exit;
        	}//--end Add
        	
        }//-end if name
		}
  } else { // Request not from login form so...

    die("Request not from login");

  }

// --End Login

// **References**
// 1. www.php.net

?>