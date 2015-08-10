<?php
  $password = $_REQUEST['password'];
  $crypt_password = crypt($password);
  echo "$crypt_password ($password)";
?>