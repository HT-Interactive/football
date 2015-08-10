<?php // Evose Login Script

// Created By: Jeff Moreland <jeff@evose.com>
// Date: 18 Jan 2003 (Updated 08 Dec 2005)
// Copyright 2002, Evose <http://www.evose.com>
    
// print_r($_SERVER);
// die();
// Evose Login Script--

  if(isset($_POST['login'])) {

  // Get Form Variables
    $username=$_POST['username'];
    $password=$_POST['password'];
    $time = time()+3600;

    if(isset($_COOKIE['cookies'])) { // Cookies Enabled so continue

    // Get DB connect info
      require("mysql.php");

   // Select proper user from table
      $user_result = mysql_query("SELECT * FROM users WHERE UserName='$username'",$db);

      if($this_user = mysql_fetch_array($user_result)) { // User name exists so continue
        extract($this_user,EXTR_PREFIX_ALL,"this");
      // Check encrypted password by seeding crypt with original pass (ref 1)
        
        if(is_null($this_UserPassword)) {
          $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?tab=home&register=".$username;
          header("Location: http://".$goTo);
          exit;
        }
        if(crypt($password,$this_UserPassword) == $this_UserPassword) { // Password ok so continue

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
          setcookie("userid",$this_UserID,time()+60*60*24*365);
          setcookie("usertype",$this_UsertypeID,time()+60*60*24*365);

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

   } else { // Cookies aren't enabled

      $error="Cookies";
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