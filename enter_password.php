<?php //
    

  if(isset($_POST['continue'])) {

  // Get Form Variables
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    if($password2==$password) {

    } else {
      $error="PasswordMismatch";
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;
    }
    $time = time()+3600;

    if(isset($_COOKIE['cookies'])) { // Cookies Enabled so continue

    // Get DB connect info
      require("mysql.php");

   // Select proper user from table
      $user_result = mysql_query("SELECT * FROM users WHERE UserName='$username'",$db);

      if($this_user = mysql_fetch_array($user_result)) { // User name exists so continue

        $crypt_password = crypt($password); //encrypt password
        $sql = "UPDATE users SET UserPassword='$crypt_password' WHERE UserName='$username'";
        
        if(mysql_query($sql)) {
       
        } else {
          $message = mysql_error();
        
        } 

        // Set session id to unique value to prevent piggy-backing
          $id = uniqid("");
          session_id($id);

        // Continue session
          session_start();

        // Save authenticated name-pass in cookie
          // header("Set-Cookie: id=$id; path=/;");
          setcookie("id",$id,$time);
          // header("Set-Cookie: username=$username; path=/;");
          setcookie("username",$username,$time);
          // echo "$username,$password,$crypt_password";
        // Point browser to user page (ref 1)
          $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
          header("Location: http://".$goTo);
          exit;



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

  } else { // Request not from login form so...

    die();

  }

// --End Login

// **References**
// 1. www.php.net

?>