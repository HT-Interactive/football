<?php //
    

  if(isset($_REQUEST['continue'])) {

  // Get Form Variables
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    $password2=$_REQUEST['password2'];
    $displayname=$_REQUEST['displayname'];
    if($password2==$password) {

    } else {
      $error="PasswordMismatch";
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;
    }
    $time = time()+3600;

    // Get DB connect info
    require("mysql.php");

   // Select proper user from table
    $user_result = mysqli_query($db, "SELECT * FROM users WHERE user_name='$username'");

    if($this_user = mysqli_fetch_array($user_result)) { // User name exists so continue

      if($_SERVER['HTTP_HOST']=="evose.com") { //on evose server
        $crypt_password = crypt($password);
      } else {
        $crypt_password = password_hash($password, PASSWORD_DEFAULT); //encrypt password
      }
      $sql = "UPDATE users SET user_password='$crypt_password', user_display_name='$displayname' WHERE user_name='$username'";
      
      if(mysqli_query($db,$sql)) {
     
      } else {
        die(mysqli_error($db));      
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
        setcookie("displayname",$displayname,$time);
        // echo "$username,$password,$crypt_password";
      // Point browser to user page (ref 1)
        $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
        header("Location: http://".$goTo);
        exit;



    } else { // User name not in DB

      $error="InvalidUsername";
      $goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php?message=".$error;
      header("Location: http://".$goTo);
      exit;

    }


  } else { // Request not from login form so...

    die("Request not from login");

  }

// --End Login

// **References**
// 1. www.php.net

?>