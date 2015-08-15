<?php

// Load DB
  require("mysql.php");

// Start Session; if first visit test for cookies
  if(!isset($_COOKIE['cookies'])) {
    session_start();
    $time = time()+3600;
    //header("Set-Cookie: cookies=yes; path=/;");
    setcookie("cookies","yes");
  } else {
    session_start();
  }

  if(isset($_COOKIE['username'])) {

      $this_username = $_COOKIE['username']; 
      $this_displayname = $_COOKIE['displayname'];
      $user_result = mysqli_query($db,"SELECT * FROM users WHERE user_name='$this_username'");
      $this_user = mysqli_fetch_array($user_result);
      extract($this_user,EXTR_PREFIX_ALL,"this");

    /* if(isset($_COOKIE['id']) && session_id()==$_COOKIE['id']) { // Request originated from original client login
      $this_username = $_COOKIE['username']; 
      $user_result = mysql_query("SELECT * FROM users WHERE username='$this_username'",$db);
      $this_user = mysql_fetch_array($user_result);
      extract($this_user,EXTR_PREFIX_ALL,"this");
    } else { // Unauthorized request
      header("Location: index.php?error=unauthorized");
      exit;
    }*/

  }

?>
<html>
<head>
<script>
function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}

function checkName(str) {
    if (str.length == 0) {
        document.getElementById("registerButton").style.visibility = "hidden";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                
                if (xmlhttp.responseText == 0) {
                    
                    document.getElementById("registerButton").style.visibility = "visible";
                    document.getElementById("loginButton").style.visibility = "hidden";
                } else {
                    
                    document.getElementById("registerButton").style.visibility = "hidden";
                    document.getElementById("loginButton").style.visibility = "visible";
                }
            }
        }
        xmlhttp.open("GET", "checkname.php?n=" + str, true);
        xmlhttp.send();
    }
}


</script>
</head>
<body>
<?php

  if(isset($this_username)) {

    echo "<div class=\"actionbar\">
    <span class=\"sampleHeader\">Welcome $this_displayname. <a href=\"login.php?logout=yes\">[Logout]</a></span>
    </div>";
    include("navigator.php");
    include("current_schedule.php");

  } elseif(isset($_REQUEST['register'])) {

    $username = $_REQUEST['register'];
    include("register.php");

  } else {

    echo "<div class=\"actionbar\">
    <span class=\"sampleHeader\">Welcome to the Wiater Family Football Site.</span>
    </div>";

    echo "<div class=\"displayer\">
    <div class=\"displayerContent\">";
    
      echo "<form action=\"login.php\" method=\"post\" class=\"loginTable\">
      <label for=\"username\">Email Address:</label>
      <input type=\"text\" name=\"username\" size=\"12\" onkeyup=\"checkName(this.value)\" />
      <input id=\"registerButton\" type=\"submit\" name=\"register\" value=\"Register\" class=\"login\" style=\"visibility: hidden;\" />
      <span id=\"loginButton\">
      <label for=\"password\">Password:</label>
      <input type=\"password\" name=\"password\" size=\"12\" />
      <input type=\"submit\" name=\"login\" value=\"Login\" class=\"login\" />
      </span>
      </form></div>";
    
  }
   



?>

</body>
</html>