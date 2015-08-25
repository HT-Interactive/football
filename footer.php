  	
    </div> <!-- /container -->

   	<footer class="footer">
    	<div class="container">
      	<p class="text-muted">
            <?php 
                if(isset($this_user_id)) {
                    echo '<span id="server_status" class="glyphicon glyphicon-refresh" aria-hidden="true" style="color: green;" onclick="reconcileDB()"></span>';        	
        	        echo 'Server Response: <span id="txtHint"></span>';
                    include("reconcile_picks.php");
                    echo '<br>';
        	        if(isset($this_user_name)) { 
        	          if(!$my_points) { $my_points = 0; }
        		        echo 'You have <b>'.$my_points.' points</b> this week. <a href="'.$SITE_ROOT.'index.php?season_year='.$current_season_year.'&season_type='.$current_season_type.'&week='.$current_week.'" class="navbar-link">Show picks for the Current Week</a>.';
        	        }
                }
        	?>
        </p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover(); 
    });
    </script>
  </body>
</html>