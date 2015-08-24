function resetPassword() {
    document.getElementById('hiddenField').name = "forgot";
    document.getElementById('hiddenField').value = "password";
	document.getElementById('form-signin').submit();
}

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
    if (str.length > 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                
                if (xmlhttp.responseText == 0) {
                    
                    document.getElementById("loginButton").style.color = "red";
                    document.getElementById("registerButton").style.visibility = "visible";

                } else {
                    
                    document.getElementById("loginButton").style.color = "green";
                    document.getElementById("registerButton").style.visibility = "hidden";
                }
            }
        }
        xmlhttp.open("GET", "checkname.php?n=" + str, true);
        xmlhttp.send();
        
    } else {

      return;
    }
}

function pickTeam(element,user,group,game,year,type,week,winner) { //dom_element,user,group,game_id,year,type,week,winner

    if(element.id == game+"_home") { // user picked home team
        document.getElementById(game+"_home").style.color = "green";
        document.getElementById(game+"_home").style.background = "LightGray";
        document.getElementById(game+"_away").style.color = "black";
        document.getElementById(game+"_away").style.background = "#eee";
    } else { // user picked away team
        document.getElementById(game+"_away").style.color = "green";
        document.getElementById(game+"_away").style.background = "LightGray";
        document.getElementById(game+"_home").style.color = "black";
        document.getElementById(game+"_home").style.background = "#eee";
    }
 
    var xmlhttp = new XMLHttpRequest();
    var thisGame = game;
    xmlhttp.onreadystatechange = function() {
    /*
    readyState 	Holds the status of the XMLHttpRequest. Changes from 0 to 4:
        0: request not initialized
        1: server connection established
        2: request received
        3: processing request
        4: request finished and response is ready
    */
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            document.getElementById(game+"_glyph").className = "glyphicon glyphicon-check";
            document.getElementById("server_status").className = "glyphicon glyphicon-refresh";
            document.getElementById("server_status").style.color = "green";
            //change glyph to something to indicate success
        } else if (xmlhttp.readyState == 1) {
          //change glyph to rotating
            document.getElementById(game+"_glyph").style.color = "blue";
            document.getElementById(game+"_glyph").className = "glyphicon glyphicon-refresh glyphicon-refresh-animate";
            document.getElementById("server_status").style.color = "blue";
            document.getElementById("server_status").className = "glyphicon glyphicon-refresh glyphicon-refresh-animate";
        }
    }
    xmlhttp.open("GET", "pick_team.php?user_id=" + user + "&group_id=" + group + "&game_id=" + game + "&season_year=" + year + "&season_type=" + type + "&week=" + week + "&winner=" + winner, true);
    xmlhttp.send();
 
}
function reconcileDB() { //dom_element,user,group,game_id,year,type,week,winner
/*
    if(element.id == game+"_home") { // user picked home team
        document.getElementById(game+"_home").style.color = "green";
        document.getElementById(game+"_home").style.background = "LightGray";
        document.getElementById(game+"_away").style.color = "black";
        document.getElementById(game+"_away").style.background = "#eee";
    } else { // user picked away team
        document.getElementById(game+"_away").style.color = "green";
        document.getElementById(game+"_away").style.background = "LightGray";
        document.getElementById(game+"_home").style.color = "black";
        document.getElementById(game+"_home").style.background = "#eee";
    }
*/ 
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        /*
        readyState 	Holds the status of the XMLHttpRequest. Changes from 0 to 4:
        0: request not initialized
        1: server connection established
        2: request received
        3: processing request
        4: request finished and response is ready
        */
        if (xmlhttp.readyState == 1) {
            //change glyph to rotating
            document.getElementById("server_status").style.color = "blue";
            document.getElementById("server_status").className = "glyphicon glyphicon-refresh glyphicon-refresh-animate";
        } else if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("server_status").className = "glyphicon glyphicon-refresh";
            document.getElementById("server_status").style.color = "green";
            //change glyph to something to indicate success
        }
    }
    xmlhttp.open("GET", "reconcile_picks.php");
    xmlhttp.send();
 
}

function enterScore(user,group,game,year,type,week,score) {
  alert('Score of '+score+' submitted.');
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;

      }
  }
  xmlhttp.open("GET", "pick_team.php?user_id=" + user + "&group_id=" + group + "&game_id=" + game + "&season_year=" + year + "&season_type=" + type + "&week=" + week + "&score=" + score, true);
  xmlhttp.send();

}

function showStandings(element,id) {
  
  if(id=='wins') {
    document.getElementById("ButtonSeasonWins").style.background = "white";
    document.getElementById("ButtonSeasonDollars").style.background = "#eee";
    document.getElementById("DivSeasonWins").style.visibility = "visible";
    document.getElementById("DivSeasonDollars").style.visibility = "hidden";
    document.getElementById("DivSeasonWins").style.position = "static";
    document.getElementById("DivSeasonDollars").style.position = "absolute";
  } else {
    document.getElementById("ButtonSeasonWins").style.background = "#eee";
    document.getElementById("ButtonSeasonDollars").style.background = "white";
    document.getElementById("DivSeasonWins").style.visibility = "hidden";
    document.getElementById("DivSeasonDollars").style.visibility = "visible";
    document.getElementById("DivSeasonWins").style.position = "absolute";
    document.getElementById("DivSeasonDollars").style.position = "static";
  }
  
}

function showDebugging(element) {
  
  if(document.getElementById("DivSeasonDebugging").style.display == "") {
    document.getElementById("DivSeasonDebugging").style.display = "none";
    /* document.getElementById("DivSeasonDebugging").style.position = "absolute"; */
  } else {
    document.getElementById("DivSeasonDebugging").style.display = "";
    /* document.getElementById("DivSeasonDebugging").style.position = "static"; */
  }
}

function myTimer(element,start) {
    var clock = document.getElementById(element)
        , targetDate = new Date.parse(start); 
    clock.innerHTML = countdown(targetDate).toString(); 
    setInterval(function(){
        clock.innerHTML = countdown(targetDate).toString();
    }, 1000);
}

function startTimer(element,date) {
    var clock = document.getElementById(element)
        , targetDate = new Date(date);
    clock.innerHTML = countdown(targetDate ).toString(); 
    setInterval(function(){
      clock.innerHTML = countdown(targetDate).toString();
    }, 1000);

}
function showTimer(element) {
    document.getElementById(element).style.display = ""; 
}
function hideTimer(element) {
    document.getElementById(element).style.display = "none"; 
}