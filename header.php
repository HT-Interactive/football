<?php
//header.php
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Jeff Moreland <jeff@evose.com>">
    <link rel="icon" href="football.ico">

    <title>Football Selection Site</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- <link href="css/navbar-fixed-top.css" rel="stylesheet"> -->
    <!-- <link href="css/signin.css" rel="stylesheet"> -->
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="css/football.css" rel="stylesheet">

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- <script src="https://smalldo.gs/js/countdown.js"></script> -->
    <script src="js/countdown.min.js"></script>
    <script src="js/football.js"></script>
    <script src="js/holder.js"></script>

  </head>

  <body onload="updateServer()">

    <?php include("navigator.php"); ?>
    <?php if(isset($this_user_name)) { include("selector.php"); } ?>

    <div class="container">
    <?php
    if(isset($this_message)) {
    //display message alert
    echo '<div class="alert alert-warning alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.
  	$this_message.'</div>';
    }
    ?>
    	<!-- Main component for a primary marketing message or call to action -->