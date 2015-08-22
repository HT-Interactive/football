function clearPassword() {
	document.getElementById('inputPassword').value = " ";
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

  // alert(w);
  //element.style.color = "green";
  //home_color = document.getElementById(g+"_home").style.color;
  //away_color = document.getElementById(g+"_away").style.color;
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
  //if(document.getElementById("score_span_"+gm)) {
  //  document.getElementById("score_span_"+gm).style.visibility = "visible";
  //}
  
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;

      }
  }
  xmlhttp.open("GET", "pick_team.php?user_id=" + user + "&group_id=" + group + "&game_id=" + game + "&season_year=" + year + "&season_type=" + type + "&week=" + week + "&winner=" + winner, true);
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