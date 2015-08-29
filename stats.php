<?php //Site Help Carousel
    
//--LOAD SITE HEADER
// Load Databases and Common functions
require("mysql.php");
include 'common.php';
include("functions.php"); //Site Functions
 
//try to guess the current week, function in get_winners
guessCurrentWeek();

include("header.php");
//--END SITE HEADER
?>
<h3>Clemson Stats</h3>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img data-src="js/holder.js/900x500/auto/#555:#555" alt="Third slide image">
      <div class="carousel-caption">
        <h3>Passing Stats</h3>
      </div>
        <div class="statsContainer">
            <table class="statsTable">
                <tr><th>Player Name</th><th>Position</th><th>Team</th><th>Passings Att</th><th>Passing Comp</th><th>Passing Yds</th><th>Passing TD's</th></tr>
        <?php
            $passing_stats = getClemsonPassingStats($this_season_year,$this_season_type); 
            //print_r($passing_stats);

            //if(!isset($stat['passing_att'])) { $stat['passing_att']=0; }
            //if(!isset($stat['passing_cmp'])) { $stat['passing_cmp']=0; }
            //if(!isset($stat['passing_yds'])) { $stat['passing_yds']=0; }
            //if(!isset($stat['passing_tds'])) { $stat['passing_tds']=0; }
            //print_r($stat);
            foreach($passing_stats as $stat) {
                echo '<tr><td>'.$stat['full_name'].'</td><td>'.$stat['position'].'</td><td>'.$stat['team'].'</td><td>'.$stat['passing_att'].'</td><td>'.$stat['passing_cmp'].'</td><td>'.$stat['passing_yds'].'</td><td>'.$stat['passing_tds'].'</td></tr>';    
            }
        ?>
         </table>

      
        </div>
    </div>
    <div class="item">
      <img src="..." alt="...">
      <div class="carousel-caption">
        Receiving Stats
      </div>
        Test Rec
    </div>
    <div class="item">
      <img src="..." alt="...">
      <div class="carousel-caption">
        Rushing Stats
      </div>
        Test Rush
    </div>
    ...
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<?php
//--Main Footer
include 'footer.php';

?>