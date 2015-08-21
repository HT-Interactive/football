<?php
//create_cat.php
// Load Databases and Common functions
require("mysql.php");
include 'common.php';
include("functions.php"); //Site Functions
 
//try to guess the current week, function in get_winners
guessCurrentWeek();

include 'header.php';

if(isset($this_user_name)) { $_SESSION['signed_in'] = true; }
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //the form hasn't been posted yet, display it
    echo '<form method="post" action="">
        Category name: <input type="text" name="cat_name" />
        Category description: <textarea name="cat_description" /></textarea>
        <input type="submit" value="Add category" />
     </form>';
}
else
{
    //the form has been posted, so save it
    $sql = "INSERT INTO categories(cat_name, cat_description)
       VALUES('" . mysql_real_escape_string($_POST['cat_name']) . "',
             '" . mysql_real_escape_string($_POST['cat_description']) . "')";
    $result = mysqli_query($db,$sql);
    if(!$result)
    {
        //something went wrong, display the error
        echo 'Error' . mysqli_error($db);
    }
    else
    {
        echo 'New category successfully added.';
    }
}
include 'footer.php';
?>