<?php

if(isset($_REQUEST['Show'])) {
  extract($_REQUEST,EXTR_PREFIX_ALL,"this");
}

echo "<form action=\"index.php\" method=\"get\">
      <select name=\"year\">";

$years = [2015];

foreach($years as $year) {
  if($this_year == $year) {
    echo "<option selected>$year</option>";
  } else {
    echo "<option>$year</option>";
  }
}

echo "</select>
      <select name=\"phase\">";

$phases = ['Preseason','Regular','Postseason'];
foreach($phases as $phase) {
  if($this_phase == $phase) {
    echo "<option selected>$phase</option>";
  } else {
    echo "<option>$phase</option>";
  }
}

echo "</select>
<select name=\"week\">";

$weeks = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17];
foreach($weeks as $week) {
  if($this_week == $week) {
    echo "<option selected>$week</option>";
  } else {
    echo "<option>$week</option>";
  }
}

echo "</select>
<input type=\"submit\" name=\"Show\" value=\"Show\" />
</form>";

?>