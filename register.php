<?php // Register Script

// file: register.php
// date: 16-Aug-2015
// updated: 16-Aug-2015
// by: Jeff Moreland <jeff@evose.com>  

  if(isset($_REQUEST['register'])) { // check that the form was submitted corrrectly

    // Get Form Variables
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    $password2=$_REQUEST['password2'];
    $displayname=$_REQUEST['displayname'];
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
    if($_SERVER['HTTP_HOST']=="evose.com") { //on evose server so use 5.4 code

      $crypt_password = crypt($password);

    } else { // use 5.6 code

      $crypt_password = password_hash($password, PASSWORD_DEFAULT); //encrypt password

    }

    // Get DB connect info
    require("mysql.php");

    // make user user isn't already registered with this email address
    $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_name='$username'");
    if(mysqli_num_rows($user_result) > 0) { // user already registered so do something

    } else { // add user to db
      $sql = "INSERT INTO users (user_id, user_name, user_password, user_display_name) VALUES (NULL,'$username','$crypt_password','$displayname')";
      if(mysqli_query($db, $sql)) { //success, login and redirect to home

      // get the new user id
        $userid = mysqli_insert_id($db);

      // Set session id to unique value to prevent piggy-backing
        $id = uniqid("");
        session_id($id);

      // Continue session
        session_start();

      // Save authenticated name-pass in cookie
        setcookie("username",$username,time()+60*60*24*365);
        setcookie("userid",$userid,time()+60*60*24*365);
        setcookie("displayname",$displayname,time()+60*60*24*365);

      // Point browser to user page (ref 1)
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
        header("Location: http://".$goTo);
        exit;

      } else { // go home with error

        // Point browser to user page (ref 1)
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".mysqli_error($db);
        header("Location: http://".$goTo);
        exit;
      }
    }

  } else { // Request not from login form so...

    die("Request not from login");

  }

// --End Login

// **References**
// 1. www.php.net

?>