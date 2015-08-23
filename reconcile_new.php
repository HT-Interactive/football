<?php
//New Reconcile Script
//Updated 8/22/15 8p

//pseudocode flow
//get all non reconciled PICKS, i.e. PICKS that don't belong to the same week as a reconciled POINT total (i.e. week). likely through a JOIN SQL statement     
//compare the PICK winner to the actual winner by querying the NFL-DB with the game_id
//if the game has started, determine if there is already a POINT awarded in the POINTS TABLE
//if no POINT exists for that 

?>