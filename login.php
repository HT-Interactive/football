<?php // Evose Login Script

// Created By: Jeff Moreland <jeff@evose.com>
// Date: 18 Jan 2003 (Updated 08 Dec 2005)
// Copyright 2002, Evose <http://www.evose.com>
    
// print_r($_SERVER);
// die();
// Evose Login Script--

  if(!isset($_COOKIE['cookies'])) {
    $error="Cookies";
    $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
    header("Location: http://".$goTo);
    exit;
  } 
  // Get DB connect info
  require("mysql.php");

  if(isset($_REQUEST['register'])) {
      $username=$_REQUEST['username'];

      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?register=".$username;
      header("Location: http://".$goTo);
      exit;

  } elseif(isset($_REQUEST['login'])) {

  // Get Form Variables
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    $time = time()+3600;

 // Select proper user from table
    $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_name='$username'");

    if($this_user = mysqli_fetch_array($user_result)) { // User name exists so continue
      extract($this_user,EXTR_PREFIX_ALL,"this");
    // Check encrypted password by seeding crypt with original pass (ref 1)
      
    //if (hash_equals($this_user_password, crypt($password, $this_user_password))) { // Password ok so continue
      if(crypt($password,$this_UserPassword) == $this_UserPassword) {

      // Set session id to unique value to prevent piggy-backing
        $id = uniqid("");
        session_id($id);

      // Continue session
        session_start();

      // Save authenticated name-pass in cookie
        // header("Set-Cookie: id=$id; path=/;");
        setcookie("id",$id,time()+60*60*24*365);
        // header("Set-Cookie: username=$username; path=/;");
        setcookie("username",$username,time()+60*60*24*365);
        setcookie("userid",$this_user_id,time()+60*60*24*365);
        setcookie("displayname",$this_user_display_name,time()+60*60*24*365);

      // Point browser to user page (ref 1)
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
        header("Location: http://".$goTo);
        exit;

      } else { // Bad password

        $error="InvalidPassword";
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
        header("Location: http://".$goTo);
        exit;

      }

    } else { // User name not in DB

      $error="InvalidUsername";
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;

    }

  } elseif(isset($_REQUEST['logout'])) { // Log user out

  // Delete Cookie
    setcookie("id","",-1);
    setcookie("username","",-1);
    setcookie("userid","",-1);
    setcookie("usertype","",-1);

  // Close Session
    session_start();
    session_unset();
    session_destroy();

  // Rediredt to referring page
    $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
    header("Location: http://".$goTo);
    exit;

  } else { // Request not from login form so...

    die();

  }

// --End Login

// **References**
// 1. www.php.net

?>