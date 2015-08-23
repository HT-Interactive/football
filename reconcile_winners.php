<?php
    //get all weeks whose picks are all reconciled therefore the week itself is ready for reconciling 
    $sql = "SELECT season_year, season_type, week, null_picks FROM (\n"
    . " SELECT season_year, season_type, week, (COUNT(*)-COUNT(reconciled)) AS null_picks \n"
    . " FROM picks\n"
    . " GROUP BY season_year, season_type, week\n"
    . ") AS x\n"
    . "WHERE null_picks=0";

?>