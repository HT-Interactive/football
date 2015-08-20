<?php

//print_r($_SERVER);
//list($user, $pass, $uid, $gid, $gecos, $home, $shell) = explode(":", $data);
echo 'All Vars:';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
echo 'Site Vars Limit 1';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],1));
echo 'Site Vars Limit 2';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],2));
echo 'Site Vars Limit 3';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],3));
echo 'Site Vars Limit 4';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],4));
echo 'Site Vars Limit -1';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],-1));
echo 'Site Vars Limit -2';
print_r(explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],-2));

?>