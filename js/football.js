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

function pickTeam(element,u,g,y,t,wk,w) { //dom_element,user,game_id,year,type,week,winner

  // alert(w);
  //element.style.color = "green";
  //home_color = document.getElementById(g+"_home").style.color;
  //away_color = document.getElementById(g+"_away").style.color;
  if(element.id == g+"_home") { // user picked home team
    document.getElementById(g+"_home").style.color = "green";
    document.getElementById(g+"_home").style.background = "LightGray";
    document.getElementById(g+"_away").style.color = "black";
    document.getElementById(g+"_away").style.background = "#eee";
  } else { // user picked away team
    document.getElementById(g+"_away").style.color = "green";
    document.getElementById(g+"_away").style.background = "LightGray";
    document.getElementById(g+"_home").style.color = "black";
    document.getElementById(g+"_home").style.background = "#eee";
  }
  //if(document.getElementById("score_span_"+g)) {
  //  document.getElementById("score_span_"+g).style.visibility = "visible";
  //}

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;

          /*
          if (xmlhttp.responseText == 0) {
              
              document.getElementById("registerButton").style.visibility = "visible";
              document.getElementById("loginButton").style.visibility = "hidden";
          } else {
              
              document.getElementById("registerButton").style.visibility = "hidden";
              document.getElementById("loginButton").style.visibility = "visible";
          }
          */
      }
  }
  xmlhttp.open("GET", "pick_team.php?user_id=" + u + "&game_id=" + g + "&season_year=" + y + "&season_type=" + t + "&week=" + wk + "&winner=" + w, true);
  xmlhttp.send();

}

function enterScore(u,g,y,t,wk,s) {
  alert('Score of '+s+' submitted.');
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;

      }
  }
  xmlhttp.open("GET", "pick_team.php?user_id=" + u + "&game_id=" + g + "&season_year=" + y + "&season_type=" + t + "&week=" + wk + "&score=" + s, true);
  xmlhttp.send();

}