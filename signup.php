<?php
//signup.php
require 'mysql.php';
include 'common.php';
include 'functions.php'; //Site Functions
//try to guess the current week, function in get_winners
guessCurrentWeek();
include 'header.php';


if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */
include 'signup.form';
	
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
        1.  Check the data
        2.  Let the user refill the wrong fields (if necessary)
        3.  Save the data 
    */
    $errors = array(); /* declare the array for later use */
     
    if(isset($_POST['user_name']))
    {
        //the user name exists
        //if(!ctype_alnum($_POST['user_name']))
        //{
        //    $errors[] = 'The username can only contain letters and digits.';
        //}
        //if(strlen($_POST['user_name']) > 30)
        //{
        //    $errors[] = 'The username cannot be longer than 30 characters.';
        //}
    }
    else
    {
        $errors[] = 'The username field must not be empty.';
    }
     
     
    if(isset($_POST['user_pass']))
    {
        if($_POST['user_pass'] != $_POST['user_pass_check'])
        {
            $errors[] = 'The two passwords did not match.';
        }
    }
    else
    {
        $errors[] = 'The password field cannot be empty.';
    }
     
    if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
    {
        include 'signup.form';
        echo '<div>Uh-oh.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
        {
            echo '<li>' . $value . '</li>'; /* this generates a nice error list */
        }
        echo '</ul>
        </div>';
    }
    else
    {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        $sql = "INSERT INTO
                    users(user_name, user_pass, user_email ,user_date, user_level)
                VALUES('" . mysql_real_escape_string($_POST['user_name']) . "',
                       '" . sha1($_POST['user_pass']) . "',
                       '" . mysql_real_escape_string($_POST['user_email']) . "',
                        NOW(),
                        0)";
                         
        $result = mysqli_query($db,$sql);
        if(!$result)
        {
            //something went wrong, display the error
            echo 'Something went wrong while registering. Please try again later.';
            echo mysqli_error($db); //debugging purposes, uncomment when needed
        }
        else
        {
          // get the new user id
        	$this_user_id = mysqli_insert_id($db);

      	// Set session id to unique value to prevent piggy-backing
        	$id = uniqid("");
        	session_id($id);

      	// Continue session
        	session_start();

      	// Save authenticated name-pass in cookie
        	setcookie("user_name",$user_name,time()+60*60*24*365);
        	setcookie("user_id",$this_user_id,time()+60*60*24*365);
        	setcookie("user_email",$user_email,time()+60*60*24*365);

     		// Point browser to user page (ref 1)
        	$goTo = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php";
        	header("Location: http://".$goTo);
        	exit;
        }
    }
}
 
include 'footer.php';
?>